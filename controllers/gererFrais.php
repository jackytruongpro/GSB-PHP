<?php

/**
 * Gestion des frais
 *
 * PHP Version 7
 * @category  PPE
 * @author    TRUONG Jacky
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
$idVisiteur = $_SESSION['idVisiteur'];

$mois = getMois(date('d/m/Y'));
$numAnnee = substr($mois, 0, 4);
$numMois = substr($mois, 4, 2);
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$infosFiche = $pdo->getLesInfosFicheFrais($idVisiteur, $mois);
switch ($action) {
    case 'saisirFrais':

            if ($pdo->estPremierFraisMois($idVisiteur, $mois)) {
                $pdo->creeNouvellesLignesFrais($idVisiteur, $mois);
            }
            $infosFiche = $pdo->getLesInfosFicheFrais($idVisiteur, $mois);
        break;
    case 'validerMajFraisForfait':
        $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
        if (lesQteFraisValides($lesFrais)) {
            $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
        } else {
            ajouterErreur('Les valeurs des frais doivent être numériques');
            include 'views/erreurs.php';
        }
        break;
    case 'validerCreationFrais':
        $dateFrais = filter_input(INPUT_POST, 'dateFrais', FILTER_SANITIZE_STRING);
        $libelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_STRING);
        $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
        valideInfosFrais($dateFrais, $libelle, $montant);
        if (nbErreurs() != 0) {
            include 'views/erreurs.php';
        } else {
            $pdo->creeNouveauFraisHorsForfait(
                $idVisiteur,
                $mois,
                $libelle,
                $dateFrais,
                $montant
            );
        }
        break;
    case 'supprimerFrais':
        $idFrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_STRING);
        $pdo->supprimerFraisHorsForfait($idFrais);
        break;
}


if ($infosFiche['idEtat'] === 'CR') {
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);
    //$numAnnee = substr($idMois, 0, 4);
    //$numMois = substr($idMois, 3, 2 );
    require 'views/listeFraisForfait.php';
    require 'views/listeFraisHorsForfait.php';
}else{
    ajouterSucces('La fiche de de ' . date('m/Y') . ' a déjà été '
            . 'soumise à validation');
    include 'views/succes.php';
}
