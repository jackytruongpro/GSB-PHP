<?php

/**
 * Classe d'accès aux données.
 *
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO
 * $monPdoGsb qui contiendra l'unique instance de la classe
 *
 * PHP Version 7
 * @category  PPE
 * @author    TRUONG Jacky
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

class PdoGsb
{
    private static $serveur = 'mysql:host=localhost';
    private static $bdd = 'dbname=gsb_frais';
    private static $user = 'root';
    private static $mdp = '';
    private static $monPdo;
    private static $monPdoGsb = null;

    /**
     * Constructeur privé, crée l'instance de PDO qui sera sollicitée
     * pour toutes les méthodes de la classe
     */
    public function __construct()
    {
        PdoGsb::$monPdo = new PDO(
            PdoGsb::$serveur . ';' . PdoGsb::$bdd,
            PdoGsb::$user,
            PdoGsb::$mdp
        );
        PdoGsb::$monPdo->query('SET CHARACTER SET utf8');
    }

    /**
     * Méthode destructeur appelée dès qu'il n'y a plus de référence sur un
     * objet donné, ou dans n'importe quel ordre pendant la séquence d'arrêt.
     */
    public function __destruct()
    {
        PdoGsb::$monPdo = null;
    }

    /**
     * Fonction statique qui crée l'unique instance de la classe
     * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
     *
     * @return null l'unique objet de la classe PdoGsb
     */
    public static function getPdoGsb()
    {
        if (PdoGsb::$monPdoGsb == null) {
            PdoGsb::$monPdoGsb = new PdoGsb();
        }
        return PdoGsb::$monPdoGsb;
    }

    /**
     * Retourne les informations d'un visiteur
     *
     * @param String $login Login du visiteur
     * @param String $mdp   Mot de passe du visiteur
     *
     * @return tableau contenant l'id, le nom et le prénom
     */
    public function getInfosVisiteur($login, $mdp)
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT visiteur.id AS id, visiteur.nom AS nom, '
            . 'visiteur.prenom AS prenom, visiteur.adresse AS adresse, visiteur.mdp AS mdp '
            . 'FROM visiteur '
            . 'WHERE visiteur.login = :unLogin'
        );
        $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);
        // $requetePrepare->bindParam(':unMdp', $mdp, PDO::PARAM_STR);
        $requetePrepare->execute();
                
        $ligne = $requetePrepare->fetch();

        if(password_verify($mdp, $ligne['mdp']))
        {
            return $ligne;
        }
        else
        {
            return null;
        }
    }
    
    /**
     * Retourne les informations d'un comptable
     *
     * @param String $login Login du comptable
     * @param String $mdp   Mot de passe du comptable
     *
     * @return tableau contenant l'id, le nom et le prénom
     */
    public function getInfosComptable($login, $mdp)
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT comptable.id AS id, comptable.nom AS nom, '
                . 'comptable.prenom AS prenom, comptable.mdp AS mdp '
                . 'FROM comptable '
                . 'WHERE comptable.login = :unLogin'
        );
        $requetePrepare->bindParam(':unLogin', $login, PDO::PARAM_STR);
        $requetePrepare->execute();
        $ligne = $requetePrepare->fetch();
        
        if(password_verify($mdp, $ligne['mdp']))
        {
            return $ligne;
        }
        else
        {
            return null;
        }
    }
    
    
    /**
     * Retourne une liste de nom et prénoms de l'ensemble des visiteurs
     */
    public function getListeVisiteur()
    {
        $requete = PdoGsb::$monPdo->query(
            'SELECT * '
            . ' FROM visiteur'
            );
        return $requete->fetchAll();
    }
    
    /**
     * Retourne le nom et prénom du visiteur dont l'id est en parametre
     * @param String $idVisiteur
     * @return tableau contenant le nom et le prénom d'un visiteur
     */
    function getNomPrenomVisiteur($idVisiteur){
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT visiteur.nom, visiteur.prenom, visiteur.adresse'
                . 'FROM visiteur '
                . 'WHERE visiteur.id = :unIdVisiteur'
                );
                $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
                $requetePrepare->execute();
                return $requetePrepare->fetchAll();        
    }

    /**
     * Retourne une liste de visiteurs dont les fiches de frais sont à l'état passé en parametre
     *
     * @param $etatFiche
     * @return tableau contenant la liste des visiteurs en
     * fonction de l'etat de leur fiche
     */
    public function getLstVisiteurParEtatFiche($etatFiche){
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT DISTINCT visiteur.id, visiteur.nom, visiteur.prenom '
                . 'FROM visiteur JOIN fichefrais '
                . 'ON visiteur.id = fichefrais.idvisiteur '
                . 'WHERE fichefrais.idetat = :unEtatFrais'
                );
        $requetePrepare->bindParam(':unEtatFrais', $etatFiche, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Retourne les information des fiches de frais Validées, Mises en Paiement ou Remboursées
     * @param String $etat
     * @return tableau d'informations de fiche
     */
    public function getInfosFicheParEtat($etat){
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT visiteur.nom AS nom, visiteur.prenom AS prenom, visiteur.id AS idvisiteur, '
                . 'fichefrais.mois AS mois, fichefrais.montantvalide AS montantvalide, '
                . 'fichefrais.datemodif AS datemodif, etat.libelle AS etat '
                . 'FROM fichefrais '
                . 'JOIN visiteur ON fichefrais.idvisiteur = visiteur.id '
                . 'JOIN etat ON fichefrais.idetat = etat.id '
                . 'WHERE idetat = :unEtat'           
                );
        $requetePrepare->bindParam(':unEtat', $etat, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }
    
    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * hors forfait concernées par les deux arguments.
     * La boucle foreach ne peut être utilisée ici car on procède
     * à une modification de la structure itérée - transformation du champ date-
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return tableau contenant tous les champs des lignes de frais hors forfait
     */
    public function getLesFraisHorsForfait($idVisiteur, $mois)
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT * FROM lignefraishorsforfait '
            . 'WHERE lignefraishorsforfait.idvisiteur = :unIdVisiteur '
            . 'AND lignefraishorsforfait.mois = :unMois '
                . 'ORDER BY lignefraishorsforfait.date asc'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesLignes = $requetePrepare->fetchAll();
        for ($i = 0; $i < count($lesLignes); $i++) {
            $date = $lesLignes[$i]['date'];
            $lesLignes[$i]['date'] = dateAnglaisVersFrancais($date);
        }
        return $lesLignes;
    }

    /**
     * Retourne le nombre de justificatif d'un visiteur pour un mois donné
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return nombre de justificatifs
     */
    public function getNbjustificatifs($idVisiteur, $mois)
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT fichefrais.nbjustificatifs as nb FROM fichefrais '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        return $laLigne['nb'];
    }

    /**
     * Retourne sous forme d'un tableau associatif toutes les lignes de frais
     * au forfait concernées par les deux arguments
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return tableau contenant l'id, le libelle et la quantité
     */
    public function getLesFraisForfait($idVisiteur, $mois)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'SELECT fraisforfait.id as idfrais, '
            . 'fraisforfait.libelle as libelle, '
            . 'fraisforfait.montant, '
            . 'lignefraisforfait.quantite as quantite '
            . 'FROM lignefraisforfait '
            . 'INNER JOIN fraisforfait '
            . 'ON fraisforfait.id = lignefraisforfait.idfraisforfait '
            . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
            . 'AND lignefraisforfait.mois = :unMois '
            . 'ORDER BY lignefraisforfait.idfraisforfait'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }
    
    
    /**
     * Retourne tous les id de la table FraisForfait
     *
     * @return tableau associatif
     */
    public function getLesIdFrais()
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT fraisforfait.id as idfrais '
            . 'FROM fraisforfait ORDER BY fraisforfait.id'
        );
        $requetePrepare->execute();
        return $requetePrepare->fetchAll();
    }

    /**
     * Met à jour la table ligneFraisForfait
     * Met à jour la table ligneFraisForfait pour un visiteur et
     * un mois donné en enregistrant les nouveaux montants
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param Array  $lesFrais   tableau associatif de clé idFrais et
     *                           de valeur la quantité pour ce frais
     *
     * @return null
     */
    public function majFraisForfait($idVisiteur, $mois, $lesFrais)
    {
        $lesCles = array_keys($lesFrais);
        foreach ($lesCles as $unIdFrais) {
            $qte = $lesFrais[$unIdFrais];
            $requetePrepare = PdoGSB::$monPdo->prepare(
                'UPDATE lignefraisforfait '
                . 'SET lignefraisforfait.quantite = :uneQte '
                . 'WHERE lignefraisforfait.idvisiteur = :unIdVisiteur '
                . 'AND lignefraisforfait.mois = :unMois '
                . 'AND lignefraisforfait.idfraisforfait = :idFrais'
            );
            $requetePrepare->bindParam(':uneQte', $qte, PDO::PARAM_INT);
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->bindParam(':idFrais', $unIdFrais, PDO::PARAM_STR);
            $requetePrepare->execute();
        }
    }
    
    /**
     * Met à jour la table ligneFraisHorsForfait avec les données passées en paramètres
     * 
     * @param string $idFrais
     * @param string $idVisiteur
     * @param string $mois
     * @param string $libelle
     * @param string $dateFrais
     * @param string $montant
     * 
     * @return null
     */
    public function majLigneFraisHorsForfait($idFrais, $idVisiteur, $mois, $libelle, 
            $dateFrais, $montant)
    {
        $dateFraisAnglais = dateFrancaisVersAnglais($dateFrais);
        
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'UPDATE lignefraishorsforfait '
                . 'SET lignefraishorsforfait.date = :uneDate, '
                    .  'lignefraishorsforfait.libelle = :unLibelle, '                    
                    .  'lignefraishorsforfait.montant = :unMontant '
                . 'WHERE lignefraishorsforfait.idvisiteur = :unVisiteur '
                . 'AND lignefraishorsforfait.mois = :unMois '
                . 'AND lignefraishorsforfait.id = :idFrais'
                );
        $requetePrepare->bindParam(':uneDate', $dateFraisAnglais, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unLibelle', $libelle, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMontant', $montant, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':idFrais', $idFrais, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
    
    /**
     * Met à jour le nombre de justificatifs de la table ficheFrais
     * pour le mois et le visiteur concerné
     *
     * @param String  $idVisiteur      ID du visiteur
     * @param String  $mois            Mois sous la forme aaaamm
     * @param Integer $nbJustificatifs Nombre de justificatifs
     *
     * @return null
     */
    public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'UPDATE fichefrais '
            . 'SET nbjustificatifs = :unNbJustificatifs '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(
            ':unNbJustificatifs',
            $nbJustificatifs,
            PDO::PARAM_INT
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return bool
     */
    public function estPremierFraisMois($idVisiteur, $mois)
    {
        $boolReturn = false;
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT fichefrais.mois FROM fichefrais '
            . 'WHERE fichefrais.mois = :unMois '
            . 'AND fichefrais.idvisiteur = :unIdVisiteur'
        );
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        if (!$requetePrepare->fetch()) {
            $boolReturn = true;
        }
        return $boolReturn;
    }

    /**
     * Retourne le dernier mois en cours d'un visiteur
     *
     * @param String $idVisiteur ID du visiteur
     *
     * @return mois sous la forme aaaamm
     */
    public function dernierMoisSaisi($idVisiteur)
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'SELECT MAX(mois) as dernierMois '
            . 'FROM fichefrais '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        $dernierMois = $laLigne['dernierMois'];
        return $dernierMois;
    }

    /**
     * Crée une nouvelle fiche de frais et les lignes de frais au forfait
     * pour un visiteur et un mois donnés
     *
     * Récupère le dernier mois en cours de traitement, met à 'CL' son champs
     * idEtat, crée une nouvelle fiche de frais avec un idEtat à 'CR' et crée
     * les lignes de frais forfait de quantités nulles
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return null
     */
    public function creeNouvellesLignesFrais($idVisiteur, $mois)
    {
        $dernierMois = $this->dernierMoisSaisi($idVisiteur);
        $laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur, $dernierMois);
        $typeMoteur = $this->getTypeVehicule($idVisiteur);
        
        if ($laDerniereFiche['idEtat'] == 'CR') {
            $this->majEtatFicheFrais($idVisiteur, $dernierMois, 'CL');
        }
        $requetePrepare = PdoGsb::$monPdo->prepare(
            'INSERT INTO fichefrais (idvisiteur,mois,nbjustificatifs,'
            . 'montantvalide,datemodif,idetat, idvehicule) '
            . "VALUES (:unIdVisiteur,:unMois,0,0,now(),'CR', :unTypeMoteur)"
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unTypeMoteur', $typeMoteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesIdFrais = $this->getLesIdFrais();
        foreach ($lesIdFrais as $unIdFrais) {
            $requetePrepare = PdoGsb::$monPdo->prepare(
                'INSERT INTO lignefraisforfait (idvisiteur,mois,'
                . 'idfraisforfait,quantite) '
                . 'VALUES(:unIdVisiteur, :unMois, :idFrais, 0)'
            );
            $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
            $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
            $requetePrepare->bindParam(
                ':idFrais',
                $unIdFrais['idfrais'],
                PDO::PARAM_STR
            );
            $requetePrepare->execute();
        }
    }

    /**
     * Crée un nouveau frais hors forfait pour un visiteur un mois donné
     * à partir des informations fournies en paramètre
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $libelle    Libellé du frais
     * @param String $date       Date du frais au format français jj//mm/aaaa
     * @param Float  $montant    Montant du frais
     *
     * @return null
     */
    public function creeNouveauFraisHorsForfait(
        $idVisiteur,
        $mois,
        $libelle,
        $date,
        $montant
    ) 
    {
        $dateFr = dateFrancaisVersAnglais($date);
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'INSERT INTO lignefraishorsforfait '
            . 'VALUES (null, :unIdVisiteur,:unMois, :unLibelle, :uneDateFr,'
            . ':unMontant, 0) '
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unLibelle', $libelle, PDO::PARAM_STR);
        $requetePrepare->bindParam(':uneDateFr', $dateFr, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMontant', $montant, PDO::PARAM_INT);
        $requetePrepare->execute();
    }

    /**
     * Supprime le frais hors forfait dont l'id est passé en argument
     *
     * @param String $idFrais ID du frais
     *
     * @return null
     */
    public function supprimerFraisHorsForfait($idFrais)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'DELETE FROM lignefraishorsforfait '
            . 'WHERE lignefraishorsforfait.id = :unIdFrais'
        );
        $requetePrepare->bindParam(':unIdFrais', $idFrais, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Retourne les mois pour lesquel un visiteur a une fiche de frais à valider
     * (etat à 'CR')
     *
     * @param String $idVisiteur ID du visiteur
     *
     * @return array tableau associatif de clé un mois -aaaamm- et de valeurs
     *         l'année et le mois correspondant
     */
    public function getLesMoisDisponibles($idVisiteur)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'SELECT fichefrais.mois AS mois FROM fichefrais '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'ORDER BY fichefrais.mois desc'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesMois = array();
        while ($laLigne = $requetePrepare->fetch()) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois[] = array(
                'mois' => $mois,
                'numAnnee' => $numAnnee,
                'numMois' => $numMois
            );
        }
        return $lesMois;
    }

    /**
     * Retourne un tableau de mois en fonction de l'état des fiches d'un visiteur
     * @param String $idVisiteur
     * @param String $etat
     * @return array contenant des mois
     */
        public function getLesMoisParEtatFiche($idVisiteur, $etat)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'SELECT fichefrais.mois AS mois FROM fichefrais '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.idetat = :unEtat '
            . 'ORDER BY fichefrais.mois desc'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unEtat', $etat, PDO::PARAM_STR);
        $requetePrepare->execute();
        $lesMois = array();
        while ($laLigne = $requetePrepare->fetch()) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois[] = array(
                'mois' => $mois,
                'numAnnee' => $numAnnee,
                'numMois' => $numMois
            );
        }
        return $lesMois;
    }

    /**
     * Retourne les mois qui contiennent des fiches déjà validées
     *
     * @return array contenant des mois (-aaamm-)
     */
    public function getMoisSuiviFiche()
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'SELECT DISTINCT fichefrais.mois AS mois '
                . 'FROM fichefrais '
                . 'WHERE fichefrais.idetat = \'VA\' '
                . 'AND fichefrais.idetat = \'MP\' '
                . 'AND fichefrais.idetat = \'RB\' '
                . 'ORDER BY fichefrais.mois desc'
        );
        $requetePrepare->execute();
        $lesMois = array();
        while($laLigne = $requetePrepare->fetch()) {
            $mois = $laLigne['mois'];
            $numAnnee = substr($mois, 0, 4);
            $numMois = substr($mois, 4, 2);
            $lesMois[] = array(
                'mois' => $mois,
                'numAnnee' => $numAnnee,
                'numMois' => $numMois
            );
        }
        return $lesMois;
    }
    
    /**
     * Retourne les informations d'une fiche de frais d'un visiteur pour un
     * mois donné
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     *
     * @return tableau avec des champs de jointure entre une fiche de frais
     *         et la ligne d'état
     */
    public function getLesInfosFicheFrais($idVisiteur, $mois)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'SELECT fichefrais.idetat as idEtat, '
            . 'fichefrais.datemodif as dateModif,'
            . 'fichefrais.nbjustificatifs as nbJustificatifs, '
            . 'fichefrais.montantvalide as montantValide, '
            . 'etat.libelle as libEtat '                
            . 'FROM fichefrais '
            . 'INNER JOIN etat ON fichefrais.idetat = etat.id '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $laLigne = $requetePrepare->fetch();
        return $laLigne;
    }

    /**
     * Notifie le refus d'une fiche de frais et passe la valeur "refus" à 1
     * 
     * @param String $idVisiteur
     * @param String $mois
     */
    public function refusLigneFraisHF($unFrais)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
                'UPDATE  lignefraishorsforfait '
                . 'SET refus = 1 '
                . 'WHERE lignefraishorsforfait.id = :unFrais'
        );
        $requetePrepare->bindParam(':unFrais', $unFrais, PDO::PARAM_STR);
        $requetePrepare->execute();
    }
    
    /**
     * Modifie l'état et la date de modification d'une fiche de frais.
     * Modifie le champ idEtat et met la date de modif à aujourd'hui.
     *
     * @param String $idVisiteur ID du visiteur
     * @param String $mois       Mois sous la forme aaaamm
     * @param String $etat       Nouvel état de la fiche de frais
     *
     * @return null
     */
    public function majEtatFicheFrais($idVisiteur, $mois, $etat)
    {
        $requetePrepare = PdoGSB::$monPdo->prepare(
            'UPDATE fichefrais '
            . 'SET idetat = :unEtat, datemodif = now() '
            . 'WHERE fichefrais.idvisiteur = :unIdVisiteur '
            . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unEtat', $etat, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    /**
     * Retourne l'état d'une fiche de frais
     *
     * @param String $idVisiteur
     * @param String $mois
     * @return  L'Etat d'une fiche de frais
     */
    public function getEtatFiche($idVisiteur, $mois){
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT idetat '
                . 'FROM fichefrais '
                . 'WHERE idvisiteur = :unIdVisiteur '
                . 'AND mois = :unMois '
                );
        $requetePrepare->bindParam(':unIdVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
        return $requetePrepare->fetch(); 
    }
    
/**
 * Modifie la valeur montantvalide d'une fiche de frais
 * 
 * @param String $idVisiteur
 * @param String $mois
 * @param String $montant
 * 
 * @return null
 */
    public function majMontantValideFicheFrais($idVisiteur, $mois, $montant)
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'UPDATE fichefrais '
                . 'SET montantvalide = :unMontant '
                . 'WHERE fichefrais.idvisiteur = :unVisiteur '
                . 'AND fichefrais.mois = :unMois'              
        );
        $requetePrepare->bindParam(':unMontant', $montant, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $mois, PDO::PARAM_STR);
        $requetePrepare->execute();
    }

    
    /**
     * Retourne le type de véhicule enregistré pour un visiteur
     * @param type $idVisiteur
     * @return type
     */
    public function getTypeVehicule($idVisiteur)
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT vehicule.id '
                . 'FROM vehicule JOIN visiteur ON vehicule.id = visiteur.typevehicule '
                . 'WHERE visiteur.id = :unVisiteur'
                );
        $requetePrepare->bindParam(':unVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        $typeMoteur = $requetePrepare->fetch();
        return $typeMoteur['id'];
    }
    
    /**
     * Retourn le type de véhicule enregistré pour UNE fiche donnée
     * @param type $idVisiteur
     * @param type $idMois
     * @return type
     */
        public function getTypeVehiculeFiche($idVisiteur, $idMois)
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT idvehicule '
                . 'FROM fichefrais '
                . 'WHERE idvisiteur = :unVisiteur AND mois = :unMois'
                );
        $requetePrepare->bindParam(':unVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $idMois, PDO::PARAM_STR);
        $requetePrepare->execute();
        $typeMoteur = $requetePrepare->fetch();
    
        return $typeMoteur['idvehicule'];
    }
    
    /**
     * Retourne le tarif par défaut des frais kilométriques
     * enregistrés dans FraisForfaits
     * @return type
     */
    public function getMontantFraisKmDefaut()
    {
         $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT montant '
                . 'FROM fraisforfait '
                . 'WHERE id = \'KM\''
                );
        $requetePrepare->execute();
        $montant = $requetePrepare->fetch();
       
        return $montant['montant'];
    }
    
/**
 * Récupère le prix unitaire des frais kilométrique en fonction du véhicule du
 * visiteur. Retourne le montant par défaut si la puissance du véhicule n'est 
 * pas renseignée
 * @param type $idVisiteur
 * @return type
 */
    public function getMontantFraisKm($idVisiteur)
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT vehicule.montant '
                . 'FROM vehicule JOIN visiteur ON vehicule.id = visiteur.typevehicule '
                . 'WHERE visiteur.id = :unVisiteur'
        );
        $requetePrepare->bindParam(':unVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->execute();
        
        //Récupération d'un array contenant :
        // Un montant en fonction du type de véhicule pour le visiteur
        // Un montant d'indemnité kilométrique par défaut (table fraisforfait)
        $tarifKm = $requetePrepare->fetch();
        
        //Si le montant retourné n'est pas null, le type du véhicule est renseigné
        //Retour du montant en question
        if($tarifKm['montant'])
        {
            return $tarifKm['montant'];
        }
        return PdoGsb::getMontantFraisKmDefaut();   
    }
    
/**
 * Récupère le prix unitaire des frais kilométrique en fonction du véhicule du
 * visiteur pour une fiche donnée. Retourne le montant par défaut si la 
 * puissance du véhicule n'est pas renseignée
 * @param type $idVisiteur
 * @param type $idMois
 * @return type
 */
    public function getMontantFraisKmFiche($idVisiteur, $idMois)
    {
        $requetePrepare = PdoGsb::$monPdo->prepare(
                'SELECT vehicule.montant '
                . 'FROM vehicule JOIN fichefrais '
                . 'ON vehicule.id = fichefrais.idvehicule '
                . 'WHERE fichefrais.idvisiteur = :unVisiteur '
                . 'AND fichefrais.mois = :unMois'
        );
        $requetePrepare->bindParam(':unVisiteur', $idVisiteur, PDO::PARAM_STR);
        $requetePrepare->bindParam(':unMois', $idMois, PDO::PARAM_STR);
        $requetePrepare->execute();
        
        //Récupération d'un array contenant :
        // Un montant en fonction du type de véhicule pour le visiteur
        // Un montant d'indemnité kilométrique par défaut (table fraisforfait)
        $tarifKm = $requetePrepare->fetch();
        
        //Si le montant retourné n'est pas null, le type du véhicule est renseigné
        //Retour du montant en question
        if($tarifKm['montant'])
        {
            return $tarifKm['montant'];
        }
        return PdoGsb::getMontantFraisKmDefaut();   
    }
    
}

