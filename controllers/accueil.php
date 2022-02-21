<?php

/**
 * Gestion de l'accueil
 *
 * PHP Version 7
 *
 * @category  PPE
 * @author    TRUONG Jacky
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
if ($estConnecte) {
    include 'views/accueil.php';
} else {
    include 'views/connexion.php';
}
