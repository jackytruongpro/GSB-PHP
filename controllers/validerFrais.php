<?php

/**
 * Gestion de la correction de la validation des fiches de frais
 *          - Correction des données des frais forfait d'une fiche
 *          - Validation des frais hors forfait (refus ou report des frais)
 *          - Validation de la fiche, enregistrement du montant total validé,
 *              changement du statut ('Validée')
 *
 * PHP Version 7
 * @category  PPE
 * @author    TRUONG Jacky
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
$idMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);


switch ($action) {
    
    case 'corrigerFraisForfait' :
        $lesFrais = filter_input(INPUT_POST, 'lesFrais',
                FILTER_DEFAULT, FILTER_FORCE_ARRAY);
        if (lesQteFraisValides($lesFrais)) {
            $pdo->majFraisForfait($idVisiteur, $idMois, $lesFrais);
            ajouterSucces('Modification des éléments forfaitisés enregistrée');
            include 'views/succes.php';
        } else {
            ajouterErreur('Les valeurs des frais doivent être numériques');
            include 'views/erreurs.php';
        }
        break;

    case 'traiterFraisHorsForfait' :

        $idMoisSuivant = getMoisSuivant($idMois);
        $libelle = filter_input(INPUT_POST, 'libelleFraisHf', FILTER_SANITIZE_STRING);
        $dateFrais = filter_input(INPUT_POST, 'dateFraisHf', FILTER_SANITIZE_STRING);
        $montant = filter_input(INPUT_POST, 'montantFraisHf', FILTER_SANITIZE_STRING);
        $idFrais = filter_input(INPUT_POST, 'idFrais', FILTER_SANITIZE_STRING);
        $refus = false;

        if (isset($_POST['corrigerFraisHf'])) {
            $pdo->majLigneFraisHorsForfait(
                    $idFrais,
                    $idVisiteur,
                    $idMois,
                    $libelle,
                    $dateFrais,
                    $montant);
            ajouterSucces('La ligne de frais hors forfait a bien été corrigée.');
            include 'views/succes.php';
        } elseif (isset($_POST['repporterFrais'])) {
            if ($pdo->estPremierFraisMois($idVisiteur, $idMoisSuivant)) {
                $pdo->creeNouvellesLignesFrais($idVisiteur, $idMoisSuivant);
                $pdo->majEtatFicheFrais($idVisiteur, $idMois, 'CR');            // Le mois en cours est toujours en cours de création 
            }
            $pdo->creeNouveauFraisHorsForfait(
                    $idVisiteur,
                    $idMoisSuivant,
                    $libelle,
                    $dateFrais,
                    $montant
            );
            $pdo->supprimerFraisHorsForfait($idFrais);
            ajouterSucces('Les frais on été repporté au mois suivant');
            include 'views/succes.php';
        } else {
            $pdo->refusLigneFraisHF($idFrais);                                  //La valeur refus de la ligne prend la valeur 1
            $libelleRefus = substr('REFUSE : ' . $libelle, 0, 40);              //Création du nouveau libellé en ajoutant REFUSE : et en limitant la chaine à 40 caractères
            $pdo->majLigneFraisHorsForfait($idFrais, $idVisiteur, $idMois, //Mise à jour de la ligne de frais avec le nouveau libellé
                    $libelleRefus, $dateFrais, $montant);
            ajouterSucces('Les frais pour "' . $libelle . '" on été refusés');  //Un message indique que les frais ont été refusé
            include 'views/succes.php';
        }
        break;

    case 'majNbJustificatifs':
        $nbJustificatifs = filter_input(INPUT_POST, 'nbJustificatifs',
                FILTER_SANITIZE_STRING);                                           //Récupération du nouveau nombre de justificatifs
        $pdo->majNbJustificatifs($idVisiteur, $idMois, $nbJustificatifs);
        ajouterSucces('Le nombre de justificatifs pour cette fiche a été mis à jour');  //Un message indique que le nombre de justificatifs a été modifié
        include 'views/succes.php';
        break;

    case 'validerFicheFrais':

        // Calcul du montant total des fiches de la fiche de frais
        $montantTotal = 0; //montant initié à 0
        //Récupération des lignes de frais forfait
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $idMois);

        foreach ($lesFraisForfait as $unFraisForfait) {
            
            //Gestion de l'indemnité kilométrique 
            //Avec prise en compte de la puissance du véhicule
            if($unFraisForfait['idfrais'] == 'KM')
            {
                $tarifKm = $pdo->getMontantFraisKm($idVisiteur);
                $montantTotal += $unFraisForfait['quantite'] * $tarifKm;
            }
            else
            {
                // Montant * Quantité pour chaque frais afin de connaitre le montant des frais forfaits
                $montantTotal += $unFraisForfait['quantite'] * $unFraisForfait['montant']; 
            }
            
        }

        //récupération du montant des frais hors forfait
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $idMois);

        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
            if (!$unFraisHorsForfait['refus']) {                              //Si un frais n'a pas été refusé
                $montantTotal += $unFraisHorsForfait['montant'];            //Montant total est incrémenté de la valeur du montant de la ligne
            }
        }
        $pdo->majMontantValideFicheFrais($idVisiteur, $idMois, $montantTotal);
        $pdo->majEtatFicheFrais($idVisiteur, $idMois, 'VA');
        ajouterSucces('La fiche de frais est validée');
        include 'views/succes.php';
        break;
}

//Affichage liste déroulantes lstVisiteur et lstMois
$uc = 'validerFrais';
$lesVisiteurs = $pdo->getLstVisiteurParEtatFiche('CR');
$lesMois = $pdo->getLesMoisParEtatFiche($idVisiteur, 'CR');
$moisASelectionner = $idMois;
include 'views/listeVisiteurs.php';


//Récupération des données à afficher
$infosFiche = $pdo->getLesInfosFicheFrais($idVisiteur, $idMois);
$libelleEtat = $infosFiche['libEtat'];
$etatFiche = $infosFiche['idEtat'];
$dateValidation = $infosFiche['dateModif'];
$nbJustificatifs = $infosFiche['nbJustificatifs'];
$lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $idMois);
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $idMois);


if ($etatFiche !== 'VA') {
    if (!empty($lesFraisForfait) || !empty($lesFraisHorsForfait)) {
        include 'views/validerFrais.php';
    }
}




