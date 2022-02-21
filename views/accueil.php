<?php
/**
 * Vue Accueil
 *
 * PHP Version 7
 * @category  PPE
 * @author    TRUONG Jacky
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
?>

<div id="accueil">
    <h2>
        Gestion des frais<small> - Visiteur : 
            <?php 
            echo $_SESSION['prenom'] . ' ' . $_SESSION['nom'];
            ?></small>
    </h2>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="glyphicon glyphicon-bookmark"></span>
                    Navigation
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <p>Bonjour <?php echo $_SESSION['prenom']."  ".$_SESSION['nom']  ?>, bienvenu sur l'application de suivi du remboursement des frais. Ici, vous pourrez saisir les quantités de frais forfaitisés et les frais hors forfait engagés pour le mois écoulé.
                            Vous aurez accès en modification à la fiche tout au long du mois et vous pourrez y ajouter de nouvelles données ou supprimer des éléments saisis.
                            Les frais saisis peuvent remonter jusqu’à un an en arrière.
                        </p>
                        <p>La fiche est clôturée au dernier jour du mois. Cette clôture sera réalisée par l’application selon l’une des modalités suivantes :
                        <li>A la première saisie pour le mois N par le visiteur, sa fiche du mois précédent est clôturée si elle ne l’est pas</li>
                        <li>Au début de la campagne de validation des fiches par le service comptable, un script est lancé qui clôture toutes les fiches non clôturées du mois qui va être traité</li></p>

                        <p>Nous restons à votre disposition pour toutes autres questions.</p>
                        <a href="index.php?uc=gererFrais&action=saisirFrais"
                           class="btn btn-success btn-lg" role="button">
                            <span class="glyphicon glyphicon-pencil"></span>
                            <br>Renseigner la fiche de frais</a>
                        <a href="index.php?uc=etatFrais&action=selectionnerMois"
                           class="btn btn-primary btn-lg" role="button">
                            <span class="glyphicon glyphicon-list-alt"></span>
                            <br>Afficher mes fiches de frais</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>