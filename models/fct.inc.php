<?php
/**
 * Fonctions pour l'application GSB
 *
 * PHP Version 7
 *
 * @category  PPE
 * @author    TRUONG Jacky
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

/**
 * Teste si un quelconque visiteur est connecté
 *
 * @return bool
 */
function estConnecte()
{
    if(isset($_SESSION['idVisiteur'])){
        return true;
    }else if (isset($_SESSION['idComptable'])){
        return true;
    }
    return false;
    
}

/**
 * Enregistre dans une variable session les infos d'un visiteur
 *
 * @param String $idVisiteur ID du visiteur
 * @param String $nom        Nom du visiteur
 * @param String $prenom     Prénom du visiteur
 * @param String $adresse     adresse du visiteur
 *
 * @return null
 */
function connecter($idVisiteur, $nom, $prenom, $adresse)
{
    $_SESSION['idVisiteur'] = $idVisiteur;
    $_SESSION['nom'] = $nom;
    $_SESSION['prenom'] = $prenom;
    $_SESSION['adresse'] = $adresse;

}

function connecterComptable($idComptable, $nom, $prenom, $adresse )
{
    $_SESSION['idComptable'] = $idComptable;
    $_SESSION['nom'] = $nom;
    $_SESSION['prenom'] = $prenom;
    $_SESSION['adresse'] = $adresse;

}
/**
 * Détruit la session active
 *
 * @return null
 */
function deconnecter()
{
    return session_destroy();
}

/**
 * Transforme une date au format français jj/mm/aaaa vers le format anglais
 * aaaa-mm-jj
 *
 * @param String $maDate au format  jj/mm/aaaa
 *
 * @return false|string
 */
function dateFrancaisVersAnglais($maDate)
{
    @list($jour, $mois, $annee) = explode('/', $maDate);
    return date('Y-m-d', mktime(0, 0, 0, $mois, $jour, $annee));
}
/**
 * Retourne la date actuelle
 * @return false|string
 */
function dateActuelle(){
    return date('Y-m-d');
}

/**
 * Transforme une date au format format anglais aaaa-mm-jj vers le format
 * français jj/mm/aaaa
 *
 * @param String $maDate au format  aaaa-mm-jj
 *
 * @return string
 */
function dateAnglaisVersFrancais($maDate)
{
    @list($annee, $mois, $jour) = explode('-', $maDate);
    $date = $jour . '/' . $mois . '/' . $annee;
    return $date;
}

/**
 * Retourne le mois au format aaaamm selon le jour dans le mois
 *
 * @param String $date au format  jj/mm/aaaa
 *
 * @return String Mois au format aaaamm
 */
function getMois($date)
{
    @list($jour, $mois, $annee) = explode('/', $date);
    unset($jour);
    if (strlen($mois) == 1) {
        $mois = '0' . $mois;
    }
    return $annee . $mois;
}

/**
 * Retourne le mois suivant au format aaaamm selon le mois en cours
 *
 * @param String $date au format aaaamm
 * @return string
 */
function getMoisSuivant($date)
{
    $annee = (int)substr($date, 0, 4);
    $mois = (int)substr($date,4, 2 );
 
    if($mois < 12)
    {
        $mois += 1;
    }
    else
    {
        $mois = 1;
        $annee += 1;
    }
    
    if($mois < 10){
        return (string)($annee . '0' . $mois);
    }
    
    return (string)($annee . $mois);
}



/* gestion des erreurs */

/**
 * Indique si une valeur est un entier positif ou nul
 *
 * @param Integer $valeur Valeur
 *
 * @return Boolean vrai ou faux
 */
function estEntierPositif($valeur)
{
    return preg_match('/[^0-9]/', $valeur) == 0;
}

/**
 * Indique si un tableau de valeurs est constitué d'entiers positifs ou nuls
 *
 * @param Array $tabEntiers Un tableau d'entier
 *
 * @return Boolean vrai ou faux
 */
function estTableauEntiers($tabEntiers)
{
    $boolReturn = true;
    foreach ($tabEntiers as $unEntier) {
        if (!estEntierPositif($unEntier)) {
            $boolReturn = false;
        }
    }
    return $boolReturn;
}

/**
 * Vérifie si une date est inférieure d'un an à la date actuelle
 *
 * @param String $dateTestee Date à tester
 *
 * @return Boolean vrai ou faux
 */
function estDateDepassee($dateTestee)
{
    $dateActuelle = date('d/m/Y');
    @list($jour, $mois, $annee) = explode('/', $dateActuelle);
    $annee--;
    $anPasse = $annee . $mois . $jour;
    @list($jourTeste, $moisTeste, $anneeTeste) = explode('/', $dateTestee);
    return ($anneeTeste . $moisTeste . $jourTeste < $anPasse);
}

/**
 * Vérifie la validité du format d'une date française jj/mm/aaaa
 *
 * @param String $date Date à tester
 *
 * @return Boolean vrai ou faux
 */
function estDateValide($date)
{
    $tabDate = explode('/', $date);
    $dateOK = true;
    if (count($tabDate) != 3) {
        $dateOK = false;
    } else {
        if (!estTableauEntiers($tabDate)) {
            $dateOK = false;
        } else {
            if (!checkdate($tabDate[1], $tabDate[0], $tabDate[2])) {
                $dateOK = false;
            }
        }
    }
    return $dateOK;
}

/**
 * Vérifie que le tableau de frais ne contient que des valeurs numériques
 *
 * @param Array $lesFrais Tableau d'entier
 *
 * @return Boolean vrai ou faux
 */
function lesQteFraisValides($lesFrais)
{
    return estTableauEntiers($lesFrais);
}

/**
 * Vérifie la validité des trois arguments : la date, le libellé du frais
 * et le montant
 *
 * Des message d'erreurs sont ajoutés au tableau des erreurs
 *
 * @param String $dateFrais Date des frais
 * @param String $libelle   Libellé des frais
 * @param Float  $montant   Montant des frais
 *
 * @return null
 */
function valideInfosFrais($dateFrais, $libelle, $montant)
{
    if ($dateFrais == '') {
        ajouterErreur('Le champ date ne doit pas être vide');
    } else {
        if (!estDatevalide($dateFrais)) {
            ajouterErreur('Date invalide');
        } else {
            if (estDateDepassee($dateFrais)) {
                ajouterErreur(
                    "date d'enregistrement du frais dépassé, plus de 1 an"
                );
            }
        }
    }
    if ($libelle == '') {
        ajouterErreur('Le champ description ne peut pas être vide');
    }
    if ($montant == '') {
        ajouterErreur('Le champ montant ne peut pas être vide');
    } elseif (!is_numeric($montant)) {
        ajouterErreur('Le champ montant doit être numérique');
    }
}

/**
 * Ajoute le libellé d'une erreur au tableau des erreurs
 *
 * @param String $msg Libellé de l'erreur
 *
 * @return null
 */
function ajouterErreur($msg)
{
    if (!isset($_REQUEST['erreurs'])) {
        $_REQUEST['erreurs'] = array();
    }
    $_REQUEST['erreurs'][] = $msg;
}

/**
 * Ajoute le libellé d'un message de succes au tableau des "succes"
 *
 * @param String $msg Libellé du succes
 *
 * @return null
 */
function ajouterSucces($msg)
{
    if (!isset($_REQUEST['succes'])) {
        $_REQUEST['succes'] = array();
    }
    $_REQUEST['succes'][] = $msg;
}

function ajouterMessageInfo($msg)
{
    if(!isset($_REQUEST['messageInfo'])) {
        $_REQUEST['messageInfo'] = array();
    }
    $_REQUEST['messageInfo'][] = $msg;
}
/**
 * Retoune le nombre de lignes du tableau des erreurs
 *
 * @return Integer le nombre d'erreurs
 */
function nbErreurs()
{
    if (!isset($_REQUEST['erreurs'])) {
        return 0;
    } else {
        return count($_REQUEST['erreurs']);
    }
}

