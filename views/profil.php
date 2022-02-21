<?php
/**
 * Vue Profil
 *
 * PHP Version 7
 * @category  PPE
 * @author    TRUONG Jacky
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
?>

<link rel="stylesheet" href="/scripts/style.css">
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Cinzel" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Nunito+Sans" rel="stylesheet">

<link href=" https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.1.1/min/dropzone.min.css" rel="stylesheet">

<div class="col-md-6 col-md-offset-3">
    <div class="panel">
        <div class="panel-body">

            <div class="profile-layout">

                <div class="profile-section">

                    <div class="profile-img-section">
                        <img src="https://png.pngtree.com/png-vector/20191104/ourmid/pngtree-businessman-avatar-cartoon-style-png-image_1953664.jpg" class="img-responsive profile-img">
                    </div>
                    <div class="text-information">
                        <h2 class="main-name"><?php
                            echo $_SESSION['prenom'] . ' ' . $_SESSION['nom'];
                            ?>
                        </h2>
                    </div>
                    <div class="tab-section">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#home" ><i class="fa fa-info-circle fa-lg"></i></a></li>
                            <li><a data-toggle="tab" href="#menu2"><i class="fa fa-key fa-lg"></i></a></li>
                            <li><a data-toggle="tab" href="#menu3"><i class="fa fa-picture-o fa-lg"></i></a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="home" class="tab-pane fade in active">

                                <div class="info-section no-edit-forms">
                                    <div class="form-group text-right">
                                        <a class="label label-info " id="edit-info">Éditer</a>
                                        <a class="label label-info hide" id="cancel-info">Annuler</a>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-user fa-lg"></i></span>
                                            <input id="" type="text" class="form-control" name="prenom"  value="
                                             <?php
                                            echo $_SESSION['prenom'];
                                            ?>
                                            ">
                                            <input id="" type="text" class="form-control" name="nom" value="
                                             <?php
                                            echo $_SESSION['nom'];
                                            ?>
                                            ">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-map-marker fa-lg"></i></span>
                                            <input id="" type="text" class="form-control" name="adresse" value="<?php echo $_SESSION['adresse']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-key fa-lg"></i></span>
                                            <input id="" type="password" class="form-control" name="email" placeholder="Activation key" value="<?php echo $_SESSION['mdp']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-clock-o fa-lg"></i></span>
                                            <label for=""></label><input id="" type="text" class="form-control" name="email" placeholder="Activation Since" value="<?php echo $_SESSION['dateembauche']; ?>">
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <a class="btn-primary btn btn-submit">Modifier</a>
                                    </div>
                                </div>
                            </div>
                            <div id="menu2" class="tab-pane fade">
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-key fa-lg"></i></span>
                                        <input id="email" type="text" class="form-control" name="email" placeholder="Password">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-key fa-lg"></i></span>
                                        <input id="email" type="text" class="form-control" name="email" placeholder="Confirm Password">
                                    </div>
                                </div>

                                <div class="text-right">
                                    <a class="btn btn-primary btn-submit">Modifier</a>
                                </div>
                            </div>
                            <div id="menu3" class="tab-pane fade">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fas fa-image"></i></span>
                                    <input id="email" type="url" class="form-control" name="image" placeholder="Placer url de l'image">
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.1.1/min/dropzone.min.js"></script>




    <script>
        $(document).ready(function(){
            $("#edit-info").on("click",function(){

                $(".info-section").removeClass("no-edit-forms");
                $(this).hide();
                $("#cancel-info").removeClass("hide");
            });
            $("#cancel-info").on("click",function(){
                window.location.reload();

            });
            $(".upload-image").dropzone();
        });
    </script>