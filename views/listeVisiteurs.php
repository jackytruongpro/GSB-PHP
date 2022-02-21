<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<div class="container">
    <div class="row">

        <hr>
        <?php
        switch ($uc) {                                                          //Changement de l'url retournée en fonction du cas d'utilisation afin d'utiliser les mêmes listes déroulantes
            case 'validerFrais':
                ?>                                                 
                <form action="index.php?uc=validerFrais" 
                      method="post" role="form">
                          <?php break;
                      case 'suivrePaiementFiche':
                          ?>
                    <form action="index.php?uc=suivrePaiementFiche" 
                          method="post" role="form">
                              <?php break;
                      }
                      ?>    

                <div class="form-group">
                    <div class="col-md-1 ">
                        <label for="lstVisiteurs">Visiteur :</label>
                    </div>
                    <div class="col-md-2">
                        <select title="visiteur" name="lstVisiteurs" class="form-control search-list"
                                onchange="this.form.submit();">
                            <option selected disabled>Choisir un visiteur</option>
                            <?php
                            foreach ($lesVisiteurs as $unVisiteur) {
                                $id = $unVisiteur['id'];
                                $nom = $unVisiteur['nom'];
                                $prenom = $unVisiteur['prenom'];


                                if ($idVisiteur == $id) {
                                    ?>              
                                    <option selected="selected" value="<?php echo $id ?>">
                                        <?php echo $nom . ' ' . $prenom ?>
                                    </option>
                                <?php } else { ?>
                                    <option value="<?php echo $id ?>"> 
                                    <?php echo $nom . ' ' . $prenom ?>
                                    </option>                    
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>    

<?php
if ($uc == 'validerFrais' || $uc == 'suivrePaiementFiche') {  //On contrôle le cas d'utilisation et on inclu la liste des mois si un Comptable cherche à valider les frais;
    ?>    

                    <div class="col-md-1">
                        <label  for="lstMois" accesskey="n">Mois :</label>
                    </div>
                    <div class="col-md-2">
                        <select id="lstMois" name="lstMois" class="form-control search-list"
                                onchange="this.form.submit()">
                            <option selected disabled>Choisir un mois</option>
                            <?php
                            include 'listeMois.php';
                            echo '</div>';
                        }
                        ?>                                         
                </div>
            </form> 
            
            </div>
            </div>       




