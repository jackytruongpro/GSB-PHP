<?php declare(strict_types=1) ?>
<?php
/**
 * Vue Entête Comptable
 *
 * PHP Version 7
 * @category  PPE
 * @author    TRUONG Jacky
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta charset="UTF-8">
        <title>Intranet du Laboratoire Galaxy-Swiss Bourdin</title> 
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="./styles/select2/select2.min.css" rel="stylesheet" />         <!-- Theme Select 2 saisie synamique -->
        <link href="./styles/bootstrap/bootstrap.css" rel="stylesheet">           <!-- Theme bootstrap -->
        <link href="./styles/style.css" rel="stylesheet">                         <!-- Aspect général -->
        <link href="./styles/styleComptable.css" rel="stylesheet">                <!-- Aspect de la partie comptable -->  
        <link href="./styles/tablesorter/theme.bootstrap_3.css" rel="stylesheet"> <!-- Theme du tableau dynamique-->
        <link href="./styles/tablesorter/jquery.tablesorter.pager.css" rel="stylesheet"> <!-- Theme de l'addon de tablesorter Pager-->


        

    </head>
    <body>
        <div class="container">
            <?php
            $uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_STRING);
            if ($estConnecte) {
                ?>
            <div class="header">
                <div class="row vertical-align">
                    <div class="col-md-4">
                        <h1>
                            <img src="./images/logo.jpg" class="img-responsive" 
                                 alt="Laboratoire Galaxy-Swiss Bourdin" 
                                 title="Laboratoire Galaxy-Swiss Bourdin">
                        </h1>
                    </div>
                    <div class="col-md-8">
                        <ul class="nav nav-pills pull-right" role="tablist">
                            <li <?php if (!$uc || $uc == 'accueil') { ?>class="active" <?php } ?>>
                                <a href="index.php">
                                    <span class="glyphicon glyphicon-home"></span>
                                    Accueil
                                </a>
                            </li>
                            <li <?php if ($uc == 'validerFrais') { ?>class="active"<?php } ?>>                  
                                <a href="index.php?uc=validerFrais">
                                    <span class="glyphicon glyphicon-ok"></span>
                                    Valider les fiches de frais
                                </a>                                    
                            </li>
                            
                            <li <?php if ($uc == 'suivrePaiementFiche') { ?>class="active"<?php } ?>>
                                <a href="index.php?uc=suivrePaiementFiche&action=afficherTableauFrais">
                                    <span class="glyphicon glyphicon-euro"></span>
                                    Suivre le paiement des fiches de frais
                                </a>                                       
                            </li>
                            <li 
                            <?php if ($uc == 'deconnexion') { ?>class="active"<?php } ?>>
                                <a href="index.php?uc=deconnexion&action=demandeDeconnexion">
                                    <span class="glyphicon glyphicon-log-out"></span>
                                    Déconnexion
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php
            } else {
                ?>   
                <h1>
                    <img src="./images/logo.jpg"
                         class="img-responsive center-block"
                         alt="Laboratoire Galaxy-Swiss Bourdin"
                         title="Laboratoire Galaxy-Swiss Bourdin">
                </h1>
                <?php
            }
            ?>
