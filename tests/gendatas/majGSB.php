<?php
/**
 * Génération d'un jeu d'essai
 *
 * PHP Version 7
 *
 * @category  PPE
 * @author    TRUONG Jacky
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

$moisDebut = '201811';
require './fonctions.php';

$pdo = new PDO('mysql:host=localhost;dbname=gsb_frais', 'root', 'root');
$pdo->query('SET CHARACTER SET utf8');

set_time_limit(0);
creationFichesFrais($pdo);
creationFraisForfait($pdo);
creationFraisHorsForfait($pdo);
majFicheFrais($pdo);
echo '<br>' . getNbTable($pdo, 'fichefrais') . ' fiches de frais créées !';
echo '<br>' . getNbTable($pdo, 'lignefraisforfait')
        . ' lignes de frais au forfait créées !';
echo '<br>' . getNbTable($pdo, 'lignefraishorsforfait')
        . ' lignes de frais hors forfait créées !';
