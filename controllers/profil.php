<?php

/**
 * profil
 *
 * PHP Version 7
 *
 * @category  PPE
 * @author    TRUONG Jacky
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
if (!$uc) {
    $uc = 'profil';
}

if ($estConnecte) {
    include 'views/profil.php';
} else {
    include 'views/accueil.php';
}

