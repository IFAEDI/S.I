<?php

/*
 * Utilisation  :
 * DB::Prepare("REPLACE INTO users (jid, lat, lon, last_update, fname, lname) VALUES (:jid, :lat, :lon, CURRENT_TIMESTAMP, :fname, :lname);", $arrayvar);
 * Remplace des valeurs de la BDD par les valeur contenu dans $arrayvar (['jid']=>3,['lat']=>56,....)
 * $res = DB::Prepare("SELECT jid FROM users WHERE jid = :jid", $arrayvar);
 * Retournera une ligne sous la forme $res['jid'] ==> value
 * 
 * Pour avoir plusieur lignes il faut faire avant la requete : 
 * $res = DB::Prepare("SELECT jid FROM users WHERE jid = :jid", $arrayvar,DB::FETCH_TYPE_ALL);
 * Retournera un tableau de cette forme : $res[0]['jid'] ==> value,$res[1]['jid'] ==> value,$res[2]['jid'] ==> value,....
 * Meme si le retour n'est que d'une seul ligne
 * 
 * Pour $object = DB::Prepare("SELECT jid FROM users WHERE jid = :jid", $arrayvar,DB::FETCH_TYPE_ALL);
 * cela retourne une instance de l'objet dans lequel on est (depend de votre class) : Attention les noms des attribut de la classe doivent correspondre aux noms de colonnes de la BDD
 * 
 * Le systeme peut aussi gerer les requete préparées : 
 * $object = DB::CallStoredProc('select_favoritesRecipes_byIdMember', array($_id), DB::FETCH_TYPE_ALL, __CLASS__);
 * Execute la requete stocké "select_favoritesRecipes_byIdMember"
 */


require_once(dirname(__FILE__) . '/ctrl.config.inc.php');

class DB {

    const FECTH_TYPE_ROW = 0;
    const FETCH_TYPE_ALL = 1;

    private $connection;
    private static $partageInstance;

    private function __construct() {
        global $CONFIG;

        try {
            $this->connection = new PDO('mysql:host=' . $CONFIG['db']['host'] . ';port=' . $CONFIG['db']['port'] . ';dbname=' . $CONFIG['db']['dbname'], $CONFIG['db']['username'], $CONFIG['db']['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        } catch (Exception $e) {
            echo "DB : Incorrect parameters";
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
    public static function &AppellerProcedureStocke($_nomProcedure, $_params, $_type_recuperation, $_nom_classe = NULL, $_option_recuperation = NULL) {
        $bind_params = '';

        foreach ($_params as $value) {
            $bind_params .= '?, ';
        }

        $bind_params = trim($bind_params, ', ');

        if ($_nom_classe != NULL && class_exists($_nom_classe))
            return self::Prepare("CALL $_nomProcedure($bind_params)", $_params, $_type_recuperation, PDO::FETCH_CLASS, $_nom_classe);
        else if ($_option_recuperation != NULL)
            return self::Prepare("CALL $_nomProcedure($bind_params)", $_params, $_type_recuperation, $_option_recuperation, $_nom_classe);
        else
            return self::Prepare("CALL $_nomProcedure($bind_params)", $_params, $_type_recuperation);
    }

    //Envoi d'une requête préparée
    public static function &Prepare($_requete, $_params, $_type_recuperation = self::FECTH_TYPE_ROW, $_parametre_recuperation = PDO::FETCH_ASSOC, $_option_recuperation = NULL) {
        $stmt = self::GetConnection()->prepare($_requete);
        $res = NULL;
        try {
            if ($stmt != false && $stmt->execute($_params) != false) {
                if ($_type_recuperation == self::FECTH_TYPE_ROW) {
                    if ($_option_recuperation == NULL)
                        $res = $stmt->fetch($_parametre_recuperation);
                    else if ($_parametre_recuperation == PDO::FETCH_CLASS)
                        $res = $stmt->fetchObject($_option_recuperation);
                }
                else {
                    if ($_option_recuperation == NULL)
                        $res = $stmt->fetchAll($_parametre_recuperation);
                    else
                        $res = $stmt->fetchAll($_parametre_recuperation, $_option_recuperation);
                }
            }
        } catch (Exception $e) {
            error_log('Erreur : ' . $e->getMessage() . '\n');
            error_log('Numero : ' . $e->getCode());
        }

        return $res;
    }

    public static function MontrerErreur() {
        print_r(self::GetConnection()->errorInfo());
    }

    public function __clone() {
        trigger_error('DB : Cloner cet objet est interdit', E_USER_ERROR);
    }

}
?>


