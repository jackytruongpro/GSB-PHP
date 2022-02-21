<?php

/**
 * Gestion du suivi du paiement des fiches de frais
 *
 * PHP Version 7
 * @category  PPE
 * @author    TRUONG Jacky
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

//Récupération du choix de mise à jour de fiche de l'utilisateur
//S'il n'y a aucune demande de mise en Paiement, on contrôle s'il y a une confirmation
//de remboursement.

$choixMajEtat = filter_input(INPUT_POST, 'miseEnPaiement', FILTER_SANITIZE_STRING);
if (!$choixMajEtat) {
    $choixMajEtat = filter_input(INPUT_POST, 'confirmerRemboursement', FILTER_SANITIZE_STRING);
}


$idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
$idMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);

//Récupération de la date actuelle au format mm/aaaa aprés conversion au format FR
$moisActuel = getMois(dateAnglaisVersFrancais(dateActuelle()));

if (isset($_POST['visiteurs'])) {
    foreach ($_POST['visiteurs'] as $i => $infosFiche) {
        //Reconstitution de l'identifiant de la fiche
        $idFiche = explode('|', $infosFiche);
        //Valorisation de chaque ligne de la sélection avec un visiteur et un mois
        $selectionFiche[$i] = ['visiteur' => $idFiche[0],
            'mois' => $idFiche[1]];
    }
}


switch ($action) {
    case 'majEtatFiche':
        switch ($choixMajEtat) {
            case 'miseEnPaiement':
                if (isset($_POST['visiteurs'])) {                               //Si une sélection a été faite
                    if (count($selectionFiche) === 1) {                         //Si l'utilisateur sélectionne UNE SEULE ligne dans le tableau
                        $etatAControler = $pdo->getEtatFiche(                   //On cherche à déterminer l'etat de la fiche sélectionnée
                            $selectionFiche[0]['visiteur'],
                            $selectionFiche[0]['mois']
                        );
                        if ($etatAControler['idetat'] === 'VA') {               //Si la fiche est à l'état "VA" (Validée)
                            $pdo->majEtatFicheFrais(                            //Mise à jour de l'état vers "Mise en paiement" (MP)
                                $selectionFiche[0]['visiteur'],                 //On choisit l'index [0] de la sélection puisque ce tableau ne contient qu'une ligne
                                $selectionFiche[0]['mois'],
                                'MP'
                            );

                            ajouterSucces('Mise en paiement confirmée');        //Message de confirmation
                            include 'views/succes.php';
                            ////////////////////////////////////////////////////
                            //                                                //
                            //               Transaction bancaire             //
                            //                                                //
                            ////////////////////////////////////////////////////
                        } else {                                                //Si la fiche n'est pas à 'VA'
                            ajouterErreur('Mise en paiement impossible : la '   //Affichage d'un message d'erreur
                                    . 'fiche sélectionnée doit être "Validée"'
                                    . ' pour être mise en paiement');
                            include 'views/erreurs.php';
                        }
                    } else {                                                    //S'il y a plus d'une fiche : les fiches sélectionnées dont l'état est à VA sont passent à MP
                        foreach ($selectionFiche as $i => $idFiche) {           //Récupération des identifiants des fiches de la sélection ligne par ligne ...
                            $etatAControler = $pdo->getEtatFiche(               //Récupération de l'état de la fiche en utilisant ses identifiants
                                $idFiche['visiteur'],
                                $idFiche['mois']
                            );
                            if ($etatAControler['idetat'] === 'VA') {           //Pour chaque ligne dont l'idEtat de la fiche est à 'Validée' (VA)
                                $pdo->majEtatFicheFrais(                        //Mise à jour de l'état vers "Mise en paiement" (MP)
                                    $idFiche['visiteur'],
                                    $idFiche['mois'],
                                    'MP'
                                );
                            }
                        }
                        ajouterSucces('Mise en paiement confirmée');            //Message de confirmation
                        include 'views/succes.php';

                        ///////////////////////////////////////////////////////
                        //                                                   //
                        //               Transaction bancaire                //
                        //                                                   //
                        ///////////////////////////////////////////////////////         
                    }
                } else {                                                          //Si aucune sélection n'a été faite
                    ajouterErreur('Aucune fiche n\'a été sélectionné');         //Un message indique à l'utilisateur qu'aucune fiche n'a été sélectionnée
                    include 'views/erreurs.php';
                }
                break;
                
            case 'confirmerRemboursement' :
                if (isset($_POST['visiteurs'])) {                                 //Si une sélection a été faite
                    if (count($selectionFiche) === 1) {                           //Si l'utilisateur sélectionne UNE SEULE ligne dans le tableau
                        $etatAControler = $pdo->getEtatFiche(//On cherche à déterminer l'etat de la fiche sélectionnée
                            $selectionFiche[0]['visiteur'],
                            $selectionFiche[0]['mois']
                        );
                        if ($etatAControler['idetat'] === 'MP') {                 //Si la fiche est à l'état "MP" (Mise en paiement)
                            $pdo->majEtatFicheFrais(//Mise à jour de l'état vers "Remboursée" (RB)
                                $selectionFiche[0]['visiteur'], //On choisit l'index [0] de la sélection puisque ce tableau ne contient qu'une ligne
                                $selectionFiche[0]['mois'],
                                'RB'
                            );

                            ajouterSucces('Mise en paiement confirmée');            //Message de confirmation du remboursement
                            include 'views/succes.php';
                        } else {                                                  //Si la fiche n'est pas à 'Mise en Paiement' ('MP')
                            ajouterErreur('Confirmation de remboursement'
                                    . 'impossible : la fiche sélectionnée '     //Affichage d'un message d'erreur
                                    . 'doit être "Mise en paiement" '
                                    . 'pour pouvoir confirmer le remboursement');
                            include 'views/erreurs.php';
                        }
                    } else {                                                      //Si l'utilisateur sélectionne plusieurs fiches : les fiches sélectionnées dont l'état est à MP sont passent à RB
                        foreach ($selectionFiche as $i => $idFiche) {             //Récupération des identifiants des fiches de la sélection ligne par ligne ...
                            $etatAControler = $pdo->getEtatFiche(//Récupération de l'état de la fiche en utilisant ses identifiants
                                $idFiche['visiteur'],
                                $idFiche['mois']
                            );
                            if ($etatAControler['idetat'] === 'MP') {            //Pour chaque ligne dont l'idEtat de la fiche est à 'Mise en paiement' ('MP')
                                $pdo->majEtatFicheFrais(                        //Mise à jour de l'état vers "Remboursée" ('RB')
                                    $idFiche['visiteur'],
                                    $idFiche['mois'],
                                    'RB'
                                );
                            }
                        }
                        ajouterSucces('Mise en paiement confirmée');            //Message de confirmation des remboursements
                        include 'views/succes.php';
                    }
                } else {                                                          //Si aucune sélection n'a été faite
                    ajouterErreur('Aucune fiche n\'a été sélectionné');         //Un message indique à l'utilisateur qu'aucune fiche n'a été sélectionnée
                    include 'views/erreurs.php';
                }
                break;
        }
        break;

    case 'afficherFiche':
        //Si le visiteur et le mois sont renseignés
        //Affichage de la fiche de frais validée en fonction du visiteur et du mois
        //Affichage des frais forfait et hors forfait

        $idVisiteur = filter_input(INPUT_GET, 'visiteur', FILTER_SANITIZE_STRING);
        $idMois = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_STRING);

        $numAnnee = substr($idMois, 0, 4);
        $numMois = substr($idMois, 4, 2);

        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $idMois);  //Récupération des infos frais hors forfait
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $idMois);          //Récupération des infos frais forfait

        $infosVisiteur = $pdo->getNomPrenomVisiteur($idVisiteur);
        $nom = $infosVisiteur[0]['nom'];
        $prenom = $infosVisiteur[0]['prenom'];
        //récupération du nom et du prénom du visiteur
        //récupération de toutes les informations de la fiche
        $laFiche = $pdo->getLesInfosFichefrais($idVisiteur, $idMois);
        //récupération du libellé de l'état de la fiche
        $libEtat = $laFiche['libEtat'];
        //récupération de la date de modification
        $dateModif = dateAnglaisVersFrancais($laFiche['dateModif']);
        //récupération du montant validé
        $montantValide = $laFiche['montantValide'];
        //récupération du nombre de justificatifs
        $nbJustificatifs = $laFiche['nbJustificatifs'];
        include 'views/affichageFiche.php';
        die();
        break;
}


// Concaténation de tableaux pour éviter de créer une fonction pdo faisant appel à une requête sql spécifique
// getInfosFicheParEtat est davantage réutilisable qu'une fonction qui retourne uniquement les
// informations de fiches qui ont le statut 'VA', 'MP' ou 'RB'.
// lesFiches sera trié par le plugin jquery Tablesorter en fonction des choix de l'utilisateur
$lesFiches = array_merge(
    $pdo->getInfosFicheParEtat('VA'),
    $pdo->getInfosFicheParEtat('MP'),
    $pdo->getInfosFicheParEtat('RB')
);
include 'views/suivrePaiementFiche.php';





