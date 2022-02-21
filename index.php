<?php
/**
 * Index du projet GSB
 * @author    TRUONG Jacky
 */

require_once 'models/fct.inc.php';
require_once 'models/class.pdogsb.inc.php';

session_start();
$pdo = PdoGsb::getPdoGsb();
$estConnecte = estConnecte();
if(isset($_SESSION['idVisiteur']))
{
    require 'views/header.php';
}
else
{
    require 'views/HeaderComptable.php';
}

$uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_STRING);
if ($uc && !$estConnecte) {
    $uc = 'connexion';
} elseif (empty($uc)) {

    if(isset($_SESSION['idVisiteur']))
    {
        $uc = 'accueil';
    }else
    {
        $uc = 'accueilComptable';
    }

}
switch ($uc) {

    case 'connexion':
        include 'controllers/connexion.php';
        break;

    case 'accueil':
        include 'controllers/accueil.php';
        break;

    case 'accueilComptable':
        include 'controllers/accueilComptable.php';
        break;

    case 'gererFrais':
        include 'controllers/gererFrais.php';
        break;

    case 'etatFrais':
        include 'controllers/etatFrais.php';
        break;

    case 'validerFrais' :
        include 'controllers/validerFrais.php';
        break;

    case 'suivrePaiementFiche' :
        include 'controllers/suivrePaiementFiche.php';
        break;

    case 'profil' :
        include 'controllers/profil.php';
        break;

    case 'deconnexion':
        include 'controllers/deconnexion.php';
        break;

}
require 'views/footer.php';
