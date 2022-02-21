<?php

/**
 * Gestion de la déconnexion
 *
 * PHP Version 7
 *
 * @category  PPE
 * @author    TRUONG Jacky
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
if (!$uc) {
    $uc = 'demandeconnexion';
}

switch ($action) {
    case 'demandeDeconnexion':
        include 'views/deconnexion.php';
        break;
    case 'valideDeconnexion':
        if (estConnecte()) {
            include 'views/deconnexion.php';
        } else {
            ajouterErreur("Vous n'êtes pas connecté");
            include 'views/erreurs.php';
            include 'views/connexion.php';
        }
        break;
    default:
        include 'views/connexion.php';
        break;
}
