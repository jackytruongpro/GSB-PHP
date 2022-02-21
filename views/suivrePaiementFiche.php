<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="container">

    <?php include 'views/v_commandesSuiviFiche.php' ?>

    <!---------- Tableau des fiches de frais ---------->

    <div class="panel panel-info">
        <div class="panel-heading">Fiches de frais</div>
        <table id="tableau-fiches" class="table table-bordered table-responsive tablesorter">
            <thead>
                <tr>
                    <th class="col-md-1 filter-false"><input type="checkbox" id="tout_cocher" class="tout_cocher"
                                                             onclick="toutCocher('tout_cocher');"></th>
                    <th class="col-md-2">Visiteur</th>
                    <th class="col-md-2 filter-select sorter-ddmmyy" data-placeholder="Tous les mois" 
                        data-value="<?php echo substr($moisActuel, 0, 4) . "-" .  substr($moisActuel, 4, 2); ?>">
                        Mois (aaaa-mm)</th>
                    <th class="col-md-1">Montant</th>
                    <th class="col-md-2">Date de modification</th>
                    <th class="filter-select filter-exact"
                        data-placeholder="Tous les états">Etat fiche</th>
                    <th class="col-md-1 filter-false">Action</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th class="col-md-1"><input type="checkbox" id="tout_cocher_foot" class="tout_cocher" 
                                                onclick="toutCocher('tout_cocher_foot');"></th>
                    <th class="col-md-2">Visiteur</th>
                    <th class="col-md-2">Mois (aaaa-mm)</th>
                    <th class="col-md-1">Montant</th>
                    <th class="col-md-2">Date de modification</th>
                    <th class="col-md-2">Etat fiche</th>
                    <th class="col-md-1">Action</th>
                </tr>
                <tr>
                    <th colspan="7" class="ts-pager form-inline">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-default first">
                                <span class="glyphicon glyphicon-step-backward"></span>
                            </button>
                            <button type="button" class="btn btn-default prev">
                                <span class="glyphicon glyphicon-backward"></span>
                            </button>
                        </div>
                        <span class="pagedisplay"></span>
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-default next">
                                <span class="glyphicon glyphicon-forward"></span>
                            </button>
                            <button type="button" class="btn btn-default last">
                                <span class="glyphicon glyphicon-step-forward"></span>
                            </button>
                        </div>
                        <select class="form-control input-sm pagesize" id="lstNbLignes" 
                                title="Selectionnez le nombre de lignes a faire apparaître">
                            <option selected="selected" value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="all">Toutes les fiches</option>
                        </select>
                        <select class="form-control input-sm pagenum" title="Sélectionnez le numéro de page"></select>
                    </th>
                </tr>
            </tfoot>  
            <tbody> 
                <?php foreach ($lesFiches as $i => $uneFiche) { ?>
                    <tr>
                        <?php
                        $idVisiteur = $uneFiche['idvisiteur'];                    //Récupération des identifiant de la fiche
                        $idMois = $uneFiche['mois'];
                        $mois = substr($idMois, 4, 2);
                        $annee = substr($idMois, 0, 4);
                        $idFiche = ['visiteur' => $idVisiteur,
                            'mois' => $idMois];
                        ?>
                        <td>
                            <input type="checkbox" id="visiteur<?php echo $i ?>"
                                   name ="visiteurs[]" value="<?php echo implode('|', $idFiche) ?>">
                        </td>
                        <td>
                            <?php echo $uneFiche['nom'] . " " . $uneFiche['prenom'] ?>
                        </td>
                        <td data-text="<?php echo $annee . "-" . $mois ?>">
                            <span class="hidden"><?php echo substr($idMois) ?></span>
                            <?php echo $mois . "/" . $annee ?>
                        </td>
                        <td>
                            <?php echo $uneFiche['montantvalide'] ?>
                        </td>
                        <td>
                            <?php echo dateAnglaisVersFrancais($uneFiche['datemodif']); ?>
                        </td>
                        <td>
                            <?php echo $uneFiche['etat'] ?>
                        </td>
                        <td>
                            <input type="hidden" name="idvisiteur" value="<?php echo $idVisiteur; ?>">
                            <input type="hidden" name="idmois" value="<?php echo $idMois; ?>">
                            <a data-toggle="tooltip" title="Consulter la fiche" 
                               href="index.php?uc=suivrePaiementFiche&action=afficherFiche&visiteur=<?php echo $idVisiteur ?>&mois=<?php echo $idMois ?>"
                               target="_blank">
                                <span class="glyphicon glyphicon-eye-open" style="font-size:20px;"></span></a>
                        </td>
                    </tr>

                <?php }
                ?>
            </tbody> 
        </table>
    </div>
</form>
</div>
</div>