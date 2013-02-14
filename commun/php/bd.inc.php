<?php
/*
 * @author Loïc Gevrey
 
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
 * Pour $object = BD::Prepare("SELECT jid FROM users WHERE jid = :jid", $arrayvar,BD::RECUPERER_TOUT,PDO::FETCH_CLASS,__CLASS__);
 * cela retourne une instance de l'objet dans lequel on est (depend de votre class) : Attention les noms des attribut de la classe doivent correspondre aux noms de colonnes de la BDD
 * 
 * Le systeme peut aussi gerer les requete préparées : 
 * $object = BD::CallStoredProc('select_favoritesRecipes_byIdMember', array($_id), BD::RECUPERER_TOUT, __CLASS__);
 * Execute la requete stocké "select_favoritesRecipes_byIdMember"
 */


require_once(dirname(__FILE__) . '/../../config/config.inc.php');

class BD {

	const RECUPERER_UNE_LIGNE = 0;
	const RECUPERER_TOUT = 1;

	private $connection;
	private static $partageInstance;

	private static $derniereErreur;

	/**
	* Constructeur : initialisation de la connexion à la BD MySQL via PDO
	*/
	private function __construct() {
		global $CONFIG;

		/* Initialisation */
		$this->connection = null;
		self::$derniereErreur = '';

		try {

			$dn  = 'mysql:host='.$CONFIG['bd']['hote'].';';
			$dn .= 'port='.$CONFIG['bd']['port'].';';
			$dn .= 'dbname='.$CONFIG['bd']['bdnom'];

			$this->connection = new PDO( $dn, $CONFIG['bd']['nom_utilisateur'], $CONFIG['bd']['mot_de_passe'], array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
		} catch (Exception $e) {

			self::$derniereErreur = $e->getMessage();
			throw $e;
		}
	}


	/**
	* Retourne l'unique connexion à la BDD (singleton)
	* /!\ Peut retourner NULL si l'initialisation a foiré !
	*/
	public static function GetConnection() {

		/* Si l'objet n'est pas instancié, on le fait */
		if (!isset(self::$partageInstance)) {
			self::$partageInstance = new self();
		}

		return self::$partageInstance->connection;
	}



	/**
	* Prépare une requête et l'exécute
	* @return Un objet PDOStatement si tout est ok, ou null sinon
	* @throws Exception en cas d'erreur
	* @deprecated
	*/
	public static function &Prepare($_requete, $_parametres, $_type_recuperation = self::RECUPERER_UNE_LIGNE, $_parametre_recuperation = PDO::FETCH_ASSOC, $_option_recuperation = NULL) {

		$resultat = NULL;
		$enregistrement = NULL;

		/* Si l'initialisation a foiré, on évite de faire foirer en cascade ! */
		if (self::GetConnection() == NULL) {
			return $resultat;
		}

		$enregistrement = self::GetConnection()->prepare($_requete);

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
			throw $e;
		}

		return $resultat;
	}



	/**
	* Execute une requête SQL de type SELECT
	* @return Le ou les lignes via un PDOStatement en cas de succès
	* @throws Exception si une erreur survient avec le message relative à l'erreur
	*/
	public static function &executeSelect($_requete, $_parametres, $_type_recuperation = self::RECUPERER_UNE_LIGNE, $_parametre_recuperation = PDO::FETCH_ASSOC, $_option_recuperation = NULL) {

		$enregistrement = NULL;
		$resultat = NULL;

		/* On évite que ça nous pète à la gueule */
		if( self::GetConnection() == NULL ) {
			return null;
		}

		/* Préparation de la requête à la base */
		$enregistrement = self::getConnection()->prepare( $_requete );

		if( $enregistrement == false ) {
			throw new Exception( 'La préparation de la requête a échoué.' );
		}

		/* Puis exécution */
		$resultat = $enregistrement->execute( $_parametres );
		if( $resultat == false ) {
			$error = $enregistrement->errorInfo();
			self::$derniereErreur = $error[2];
			throw new Exception( 'L\'exécution de la requête a échoué.' );
		}


		/* Mise en forme des sets */
		if( $_type_recuperation == self::RECUPERER_UNE_LIGNE ) {

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

		return $resultat;
	}

	
	/**
	* Execute une requête SQL de type INSERT, UPDATE ou DELETE
	* @return Le nombre de lignes affectées par la requête
	* @throws Exception si une erreur survient avec le message relative à l'erreur
	*/
	public static function &executeModif( $_requete, $_parametres ) {

                $enregistrement = NULL;
                $resultat = NULL;

                /* On évite que ça nous pète à la gueule */
                if( self::GetConnection() == NULL ) {
                        return null;
                }

                /* Préparation de la requête à la base */
                $enregistrement = self::getConnection()->prepare( $_requete );

                if( $enregistrement == false ) {
                        throw new Exception( 'La préparation de la requête a échoué.' );
                }

                /* Puis exécution */
                $resultat = $enregistrement->execute( $_parametres );
                if( $resultat == false ) {
			$error = $enregistrement->errorInfo();
			self::$derniereErreur = $error[2];
                        throw new Exception( 'L\'exécution de la requête a échoué.' );
                }

		return $resultat;
	}

	/**
	 * Retourne le dernier ID inséré
	 * @return Le dernier ID inséré
 	 */
	public static function getDernierID() {
		return self::getConnection()->lastInsertId();
	}

	/**
	* Retourne la dernière erreur qui est survenue lors d'une requête.
	*/
	public static function getDerniereErreur() {

		return self::$derniereErreur;
	}


public static function MontrerErreur() {

if (self::GetConnection() == NULL) {
echo "L'initialisation de la connexion a échoué.";
return;
}

print_r(self::GetConnection()->errorInfo());
    }

    public function __clone() {
        trigger_error('BD : Cloner cet objet est interdit', E_USER_ERROR);
    }

}
?>
