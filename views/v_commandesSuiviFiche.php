    <!---------- Panneau de commande - Changer le statut des fiches ---------->
    <hr>
    <h2>Suivi des fiches de frais</h2>
    <?php ajouterMessageInfo("Sélectionnez un ou plusieurs visiteurs dans le tableau ci-dessous et actualisez l'état des fiches de frais."); ?>
    <?php ajouterMessageInfo("Triez et consultez les informations via les champs situés dans l'entête du tableau"); ?>
    <?php ajouterMessageInfo("Cliquez sur l'icone \"oeil\" pour consulter une fiche"); ?>
    <?php include 'messageInfo.php' ?>
    <br/>
    <form method="post" name="formulaire"
        action="index.php?uc=suivrePaiementFiche&action=majEtatFiche" 
        role="form">
        <div class="form-group">
            <button type="submit" class="btn btn-md btn-info" name="miseEnPaiement" value="miseEnPaiement">
            <span class="glyphicon glyphicon-piggy-bank"></span>  Mettre en paiement
            </button>
            <button type="sumbit" class="btn btn-md btn-success" name="confirmerRemboursement" value="confirmerRemboursement">
            <span class="glyphicon glyphicon-ok"></span> Confirmer remboursement
            </button>
        </div>

        

            
                
            