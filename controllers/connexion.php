<?php

/**
 * Gestion de la connexion
 *
 * PHP Version 7
 *
 * @category  PPE
 * @author    TRUONG Jacky
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

require_once 'models/class.pdogsb.inc.php';

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
if (!$uc) {
    $uc = 'demandeconnexion';
}

switch ($action) {
    case 'valideConnexion':
        $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
        $mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_STRING);
        $visiteur = $pdo->getInfosVisiteur($login, $mdp);
        $comptable = $pdo->getInfosComptable($login, $mdp);

        if (!is_array($visiteur) && (!is_array($comptable))) {
            ajouterErreur('Login ou mot de passe incorrect');
            include 'views/erreurs.php';
            include 'views/connexion.php';
        } elseif (is_array($visiteur)) {
            $id = $visiteur['id'];
            $nom = $visiteur['nom'];
            $prenom = $visiteur['prenom'];
            $adresse = $visiteur['adresse'];
            connecter($id, $nom, $prenom, $adresse);
            header('Location: index.php');
        } elseif (is_array($comptable)) {
            $id = $comptable['id'];
            $nom = $comptable['nom'];
            $prenom = $comptable['prenom'];
            $adresse = $comptable['adresse'];
            connecterComptable($id, $nom, $prenom, $adresse);
            header('Location: index.php');
        }
        break;
    default:
        include 'views/connexion.php';
        break;
}
