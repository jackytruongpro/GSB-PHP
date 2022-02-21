<?php

/**
 * Gestion de l'accueil des comptables.
 *
 * PHP Version 7
 *
 * @category  PPE
 * @author    TRUONG Jacky
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
if ($estConnecte) {
    include 'views/accueilComptable.php';
} else {
    include 'views/connexion.php';
}
