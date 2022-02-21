     
<div class="container">  
    <hr>
    <h1> Validation des fiches de frais </h1>
    <hr>

    <div class="row">
        <div class="col-md-3">       

            <!-------------------------- Frais forfait -------------------------->

            <h3>Eléments forfaitisés</h3>
            <div>
                <form method="post" 
                      action="index.php?uc=validerFrais&action=corrigerFraisForfait" 
                      role="form">
                    <input name="lstVisiteurs" value="<?php echo $idVisiteur; ?>" 
                           type="hidden">
                    <input name="lstMois" value="<?php echo $idMois; ?>" 
                           type="hidden">
                    <fieldset>       
                        <?php
                        foreach ($lesFraisForfait as $unFrais) {
                            $idFrais = $unFrais['idfrais'];
                            $libelle = htmlspecialchars($unFrais['libelle']);
                            $quantite = $unFrais['quantite'];
                            ?>
                            <div class="form-group">
                                <label for="idFrais"><?php echo $libelle ?></label>                             
                                <input type="text" id="idFrais" 
                                       name="lesFrais[<?php echo $idFrais ?>]"                                    
                                       value="<?php echo $quantite ?>" 
                                       class="form-control">

                            </div>
                            <?php
                        }
                        ?>
                        <button class="btn btn-success" type="submit">Corriger
                            <span class="glyphicon glyphicon-pencil"></span>
                        </button>
                        <button class="btn btn-primary" type="reset">Réinitialiser
                            <span class="glyphicon glyphicon-refresh"></span>
                        </button>
                    </fieldset>
                </form>
            </div>
        </div>

        <!-------------------------- Frais hors forfait -------------------------->

        <div class="col-md-9">
            <h3>Eléments Hors-Forfait</h3>

            <div class="panel panel-info">
                <div class="panel-heading">Descriptif des éléments hors forfait</div>
                <table class="table table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th class="date">Date</th>
                            <th class="libelle">Libellé</th>  
                            <th class="montant">Montant</th>  
                            <th class="action col-md-3">&nbsp;</th> 
                        </tr>
                    </thead>  
                    <tbody>
                        <?php
                        foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                            $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                            $date = $unFraisHorsForfait['date'];
                            $montant = $unFraisHorsForfait['montant'];
                            $id = $unFraisHorsForfait['id'];
                            $estRefuse = $unFraisHorsForfait['refus'];
                            ?>           

                            <tr>
                        <form method="post" 
                              action="index.php?uc=validerFrais&action=traiterFraisHorsForfait" 
                              role="form">
                            <input name="lstVisiteurs" value="<?php echo $idVisiteur; ?>" 
                                   type="hidden">
                            <input name="lstMois" value="<?php echo $idMois; ?>" 
                                   type="hidden">
                            <input name="idFrais" value="<?php echo $id; ?>" 
                                   type="hidden">



                            <?php
                            if ($estRefuse) {                              //Si les frais sont refusés, les input affichés ne sont pas cliquable, les frais ne sont plus modifiables
                                ?> 

                                <td> <input type="text" 
                                            id ="dateFraisHf" 
                                            name="dateFraisHf"
                                            class="form-control"
                                            value="<?php echo $date ?>"
                                            disabled></td>
                                <td> <input type="text"
                                            id="libelleFraisHf"
                                            name="libelleFraisHf"
                                            class="form-control"
                                            value="<?php echo $libelle ?>"
                                            disabled></td>
                                <td><input type="text"
                                           id="montantFraisHf"
                                           name="montantFraisHf"
                                           class="form-control"
                                           value="<?php echo $montant ?>"
                                           disabled></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-success" disabled>
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </button>

                                    <button type="button" class="btn btn-sm btn-primary" disabled>
                                        <span class="glyphicon glyphicon-refresh"></span>
                                    </button>

                                    <button type="button" class="btn btn-sm btn-danger" disabled>
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </button>

                                    <button type="button" class="btn btn-sm btn-warning" disabled>
                                        <span class="glyphicon glyphicon-share-alt"></span>
                                    </button> 

    <?php } else { ?>

                                <td> <input type="text" 
                                            id ="dateFraisHf" 
                                            name="dateFraisHf"
                                            class="form-control"
                                            value="<?php echo $date ?>"></td>
                                <td> <input type="text"
                                            id="libelleFraisHf"
                                            name="libelleFraisHf"
                                            class="form-control"
                                            value="<?php echo $libelle ?>"></td>
                                <td><input type="text"
                                           id="montantFraisHf"
                                           name="montantFraisHf"
                                           class="form-control"
                                           value="<?php echo $montant ?>"></td>
                                <td>
                                    <button type="submit" data-toggle="tooltip" title="Corriger" class="btn btn-sm btn-success" name="corrigerFraisHf" value="corrigerFraisHf">
                                        <span class="glyphicon glyphicon-pencil"></span>
                                    </button>

                                    <button type="reset" data-toggle="tooltip" title="Réinitialiser" class="btn btn-sm btn-primary" >
                                        <span class="glyphicon glyphicon-refresh"></span>
                                    </button>

                                    <button id="refusFrais" type="submit" data-toggle="tooltip" title="Refuser" class="btn btn-sm btn-danger"  name="refuserFrais" value="refuserFrais">
                                        <span class="glyphicon glyphicon-remove"></span>
                                    </button>

                                    <button type="submit" data-toggle="tooltip" title="Reporter" class="btn btn-sm btn-warning" name="repporterFrais" value="repporterFrais">
                                        <span class="glyphicon glyphicon-share-alt"></span>
                                    </button>  
    <?php } ?>
                            </td>
                        </form>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>  
                </table>
            </div>
        </div>
    </div>
    <br />
    <hr>
    <br />

    <!-------------------------- Nombres de justificatifs -------------------------->

    <div class="row justify-content-md-center">
        <div class="form-group">    
            <form method="post" 
                  action="index.php?uc=validerFrais&action=majNbJustificatifs" 
                  role="form">
                <input name="lstVisiteurs" value="<?php echo $idVisiteur; ?>" type="hidden">
                <input name="lstMois" value="<?php echo $idMois; ?>" type="hidden">
                <div class="col-md-2">
                    <label for="nbJustificatifs">Nombre de justificatifs</label>
                </div>
                <div class="col-md-1">
                    <input type="number" class="form-control" name='nbJustificatifs' value="<?php echo $nbJustificatifs ?>" > 
                </div>

                <div class="col-md-2">
                    <button type="submit" class="form-control btn btn-success" value="Valider">Valider 
                        <span class="glyphicon glyphicon-ok"></span>
                </div>
                <div class="col-md-2">
                    <button type="reset" class="form-control btn btn-primary" >Réinitialiser
                        <span class="glyphicon glyphicon-refresh"></span>
                    </button>
                </div>
            </form>
        </div>

    </div>
    <br />    

    <!-------------------------- Validation de la fiche -------------------------->

    <hr>
    <br />
    <div class="row">
        <form method="post" action="index.php?uc=validerFrais&action=validerFicheFrais" role="form">
            <input name="lstVisiteurs" value="<?php echo $idVisiteur; ?>" type="hidden">
            <input name="lstMois" value="<?php echo $idMois; ?>" type="hidden">
            <button class="btn btn-lg btn-success center-block" type="submit" name="validerFiche" value="validerFiche">Valider la fiche de frais
                <span class="glyphicon glyphicon-ok"></span>
            </button>
        </form>
    </div>
</div>

