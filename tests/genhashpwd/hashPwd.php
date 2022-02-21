<?php

try{
    $pdo = new PDO('mysql:host=localhost;dbname=gsb_frais', 'root', 'root');
    $pdo->query('SET CHARACTER SET utf8');
}
catch(PDOException $e)
{
    echo "Echec de connexion : " . $e->getMessage();
}



$infoVisiteurs = $pdo->query('SELECT id, login, mdp, adresse FROM visiteur');
$lesVisiteurs = $infoVisiteurs->fetchAll();
$infoComptables = $pdo->query('SELECT id, login, mdp, adresse FROM comptable');
$lesComptables = $infoComptables->fetchAll();

foreach($lesVisiteurs as $key => $unVisiteur)
{
    //Récupération des informations du visiteur
    $idVisiteur = $unVisiteur['id'];
    $login = $unVisiteur['login'];
    //Hash du mot de passe courant
    $mdp = password_hash($unVisiteur['mdp'], PASSWORD_BCRYPT);
    
    if(strlen($unVisiteur['mdp']) < 40 )
    {
    //Update des données dans la base
    $requetePrepare = $pdo->prepare(
        "UPDATE visiteur 
        SET mdp = '" . $mdp 
        ."' WHERE id = '" . $idVisiteur . "'"
    );
    //$requetePrepare->bindParam(':unMdp', $mdp, PDO::PARAM_STR);
    //$requetePrepare->bindParam(':unId', $idVisiteur, PDO::PARAM_STR);
    
    $requetePrepare->execute();
    var_dump($requetePrepare);
    }
    
}

foreach($lesComptables as $key => $unComptable)
{
    //Récupération des informations du visiteur
    $idComptable = $unComptable['id'];
    $login = $unComptable['login'];
    //Hash du mot de passe courant
    $mdp = password_hash($unComptable['mdp'], PASSWORD_BCRYPT);

    if(strlen($unComptable['mdp']) < 40 )
    {
    //Update des données dans la base
    $requetePrepare = $pdo->prepare(
        "UPDATE comptable 
        SET mdp = '" . $mdp 
        ."' WHERE id = '" . $idComptable . "'"
    );
    //$requetePrepare->bindParam(':unMdp', $mdp, PDO::PARAM_STR);
    //$requetePrepare->bindParam(':unId', $idVisiteur, PDO::PARAM_STR);
    
    $requetePrepare->execute();
    var_dump($requetePrepare);
    }
}