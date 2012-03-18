<?php

/*
 * Utilisation  :
 * BD::Prepare("REPLACE INTO users (jid, lat, lon, last_update, fname, lname) VALUES (:jid, :lat, :lon, CURRENT_TIMESTAMP, :fname, :lname);", $arrayvar);
 * Remplace des valeurs de la BDD par les valeur contenu dans $arrayvar (['jid']=>3,['lat']=>56,....)
 * $resultat = BD::Prepare("SELECT jid FROM users WHERE jid = :jid", $arrayvar);
 * Retournera une ligne sous la forme $resultat['jid'] ==> value
 * 
 * Pour avoir plusieur lignes il faut faire avant la requete : 
 * $resultat = BD::Prepare("SELECT jid FROM users WHERE jid = :jid", $arrayvar,BD::RECUPERER_TOUT);
 * Retournera un tableau de cette forme : $resultat[0]['jid'] ==> value,$resultat[1]['jid'] ==> value,$resultat[2]['jid'] ==> value,....
 * Meme si le retour n'est que d'une seul ligne
 * 
 * Pour $object = BD::Prepare("SELECT jid FROM users WHERE jid = :jid", $arrayvar,BD::RECUPERER_TOUT);
 * cela retourne une instance de l'objet dans lequel on est (depend de votre class) : Attention les noms des attribut de la classe doivent correspondre aux noms de colonnes de la BDD
 * 
 * Le systeme peut aussi gerer les requete préparées : 
 * $object = BD::CallStoredProc('select_favoritesRecipes_byIdMember', array($_id), BD::RECUPERER_TOUT, __CLASS__);
 * Execute la requete stocké "select_favoritesRecipes_byIdMember"
 */


require_once(dirname(__FILE__) . '/ctrl.config.inc.php');

class BD {

    const RECUPERER_UNE_LIGNE = 0;
    const RECUPERER_TOUT = 1;

    private $connection;
    private static $partageInstance;

    private function __construct() {
        global $CONFIG;

        try {
            $this->connection = new PDO('mysql:host=' . $CONFIG['bd']['hote'] . ';port=' . $CONFIG['bd']['port'] . ';dbname=' . $CONFIG['bd']['bdnom'], $CONFIG['bd']['nom_utilisateur'], $CONFIG['bd']['mot_de_passe'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        } catch (Exception $e) {
            echo "BD : parametres incorrects";
        }
    }

    //Retourne une connection à la BDD   
    public static function ObtenirConnection() {
        if (!isset(self::$partageInstance)) {
            self::$partageInstance = new self();
        }

        return self::$partageInstance->connection;
    }

    //Appelle des procedures stockées
    public static function &AppellerProcedureStocke($_nomProcedure, $_parametres, $_type_recuperation, $_nom_classe = NULL, $_option_recuperation = NULL) {
        $assigner_parametres = '';

        foreach ($_parametres as $valeur) {
            $assigner_parametres .= '?, ';
        }

        $assigner_parametres = trim($assigner_parametres, ', ');

        if ($_nom_classe != NULL && class_exists($_nom_classe))
            return self::Prepare("CALL $_nomProcedure($assigner_parametres)", $_parametres, $_type_recuperation, PDO::FETCH_CLASS, $_nom_classe);
        else if ($_option_recuperation != NULL)
            return self::Prepare("CALL $_nomProcedure($assigner_parametres)", $_parametres, $_type_recuperation, $_option_recuperation, $_nom_classe);
        else
            return self::Prepare("CALL $_nomProcedure($assigner_parametres)", $_parametres, $_type_recuperation);
    }

    //Envoi d'une requête préparée
    public static function &Prepare($_requete, $_parametres, $_type_recuperation = self::RECUPERER_UNE_LIGNE, $_parametre_recuperation = PDO::FETCH_ASSOC, $_option_recuperation = NULL) {
        $enregistrement = self::GetConnection()->prepare($_requete);
        $resultat = NULL;
        try {
            if ($enregistrement != false && $enregistrement->execute($_parametres) != false) {
                if ($_type_recuperation == self::RECUPERER_UNE_LIGNE) {
                    if ($_option_recuperation == NULL)
                        $resultat = $enregistrement->fetch($_parametre_recuperation);
                    else if ($_parametre_recuperation == PDO::FETCH_CLASS)
                        $resultat = $enregistrement->fetchObject($_option_recuperation);
                }
                else {
                    if ($_option_recuperation == NULL)
                        $resultat = $enregistrement->fetchAll($_parametre_recuperation);
                    else
                        $resultat = $enregistrement->fetchAll($_parametre_recuperation, $_option_recuperation);
                }
            }
        } catch (Exception $e) {
            error_log('Erreur : ' . $e->getMessage() . '\n');
            error_log('Numero : ' . $e->getCode());
        }

        return $resultat;
    }

    public static function MontrerErreur() {
        print_r(self::GetConnection()->errorInfo());
    }

    public function __clone() {
        trigger_error('BD : Cloner cet objet est interdit', E_USER_ERROR);
    }

}
?>


