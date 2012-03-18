<?php

require_once(dirname(__FILE__) . '/ctrl.config.inc.php');

class DB {

    const FECTH_TYPE_ROW = 0;
    const FETCH_TYPE_ALL = 1;

    private $connection;
    private static $sharedInstance;

    private function __construct() {
        global $CONFIG;

        try {
            $this->connection = new PDO('mysql:host=' . $CONFIG['db']['host'] . ';port=' . $CONFIG['db']['port'] . ';dbname=' . $CONFIG['db']['dbname'], $CONFIG['db']['username'], $CONFIG['db']['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        } catch (Exception $e) {
            echo "DB : Incorrect parameters";
        }
    }

    //Retourne une connection à la BDD   
    public static function GetConnection() {
        if (!isset(self::$sharedInstance)) {
            self::$sharedInstance = new self();
        }

        return self::$sharedInstance->connection;
    }

    //Appelle des procedures stockées
    public static function &CallStoredProc($_procName, $_params, $_fetch_type, $_className = NULL, $_fetch_opt = NULL) {
        $bind_params = '';

        foreach ($_params as $value) {
            $bind_params .= '?, ';
        }

        $bind_params = trim($bind_params, ', ');

        if ($_className != NULL && class_exists($_className))
            return self::Prepare("CALL $_procName($bind_params)", $_params, $_fetch_type, PDO::FETCH_CLASS, $_className);
        else if ($_fetch_opt != NULL)
            return self::Prepare("CALL $_procName($bind_params)", $_params, $_fetch_type, $_fetch_opt, $_className);
        else
            return self::Prepare("CALL $_procName($bind_params)", $_params, $_fetch_type);
    }

    //Envoi d'une requête préparée
    public static function &Prepare($_query, $_params, $_fetchType = self::FECTH_TYPE_ROW, $_fetch_param = PDO::FETCH_ASSOC, $_fetch_opt = NULL) {
        $stmt = self::GetConnection()->prepare($_query);
        $res = NULL;
        try {
            if ($stmt != false && $stmt->execute($_params) != false) {
                if ($_fetchType == self::FECTH_TYPE_ROW) {
                    if ($_fetch_opt == NULL)
                        $res = $stmt->fetch($_fetch_param);
                    else if ($_fetch_param == PDO::FETCH_CLASS)
                        $res = $stmt->fetchObject($_fetch_opt);
                }
                else {
                    if ($_fetch_opt == NULL)
                        $res = $stmt->fetchAll($_fetch_param);
                    else
                        $res = $stmt->fetchAll($_fetch_param, $_fetch_opt);
                }
            }
        } catch (Exception $e) {
            error_log('Erreur : ' . $e->getMessage() . '\n');
            error_log('Numero : ' . $e->getCode());
        }

        return $res;
    }

    public static function ShowErrors() {
        print_r(self::GetConnection()->errorInfo());
    }

    public function __clone() {
        trigger_error('DB : Cloning this object is not permitted', E_USER_ERROR);
    }

}

/*
Utilisation  : 
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
?>


