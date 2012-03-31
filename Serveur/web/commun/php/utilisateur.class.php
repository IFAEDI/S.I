<?php

require_once dirname(__FILE__) . '/base.inc.php';
inclure_fichier('commun', 'personne.class', 'php');

class Utilisateur {

	/****************  Attributs  ******************/
	private $id;
	private $login;
	private $service;
	private $banni;

	private $personne;

	/**
	* Constructeur
	* $login : chaîne de caractère contenant le nom de l'utilisateur à créer
	* @throws Exception si impossibilité de créer l'utilisateur
	*/
	public function __construct( $login ) {
        
		$result = $this->_fetchData( $login );

		/* Si l'objet n'a pas pu être créé, c'est sans doute que c'est une auth via le CAS et que l'user est pas en base */
		if( $result == false ) {

			echo "pouet";

			/* On s'en occupe donc ! */
			/* Ajout en base */
			$result = BD::executeModif( 'INSERT INTO UTILISATEUR(login, nom, service, premiere_connexion) VALUES( :login, :nom, :service, 1 )', array( 'login' => $login, 'nom' => $login, 'service' => Authentification::AUTH_CAS ) );

			if( $result == 0 ) {
				throw new Exception( 'Impossible d\'insérer le nouvel utilisateur en base.' );
			} 

			/* Et on rappelle pour fetcher les éléments */
			$result = $this->_fetchData( $login );
			if( $result == false ) {
				throw new Exception( 'Impossible de construire l\'utilisateur (erreur de bdd).' );
			}
		}

		/* Et maintenant on crée l'objet Personne associé */
		$this->personne = new Personne( $this );
	}

	/**
	* Fonction récupérant tous les attributs de l'utilisateur
	* $login : Identifiant de l'utilisateur a connecter
	* @return True si tout est ok, false sinon
	*/
	private function _fetchData( $login ) {

		/* Requête à la base pour récupérer le bon utilisateur et construire l'objet */
		$result = BD::executeSelect( 'SELECT * FROM UTILISATEUR WHERE login = :login', array( 'login' => $login ), BD::RECUPERER_UNE_LIGNE );

		if( $result == null ) {
			return false;
		}

		$this->id = $result['ID_UTILISATEUR'];
		$this->login = $result['LOGIN'];
		$this->service = $result['AUTH_SERVICE'];
		$this->banni = $result['BANNI'];

		return true;
	}

	/**
	* Fonction faisant le changement du mot de passe de l'utilisateur
	* @return Vrai si tout est ok, faux sinon
	*/
	public function changePassword( $mdp ) {

		/* Requête à la base */
		$result = BD::executeModif( 'UPDATE UTILISATEUR SET PASSWD = :mdp WHERE ID_UTILISATEUR = :id', 
							array( 'mdp' => $mdp, 'id' => $this->id ) );

		return ($result == 1);
	}


	/**
	* Retourne la personne associée à l'utilisateur
	*/
	public function getPersonne() {
		return $this->personne;
	}

	/**
	* Retourne le nom d'utilisateur de l'utilisateur
	*/
	public function getLogin() {
		return $this->login;
	}

	/**
	* Retourne l'identifiant de l'utilisateur
	*/
	public function getId() {
		return $this->id;
	}

	/**
	* Retourne le type d'authentification
	*/
	public function getService() {
		return $this->service;
	}

	/**
	* Récupère l'ensemble des utilisateurs en base
	* @return Un tableau d'instances
	* @throws Une exception en cas d'erreur
	*/
	public static function RecupererTousLesUtilisateurs() {

		$obj = array();

		/* Requête à la base pour récupérer les logins et construire les objets */
		$result = BD::executeSelect( 'SELECT login FROM UTILISATEUR', array(), BD::RECUPERER_TOUT );

		$i = 0;
		foreach( $result as $row ) {

			$obj[$i] = new Utilisateur( $row['login'] );
			$i++;
		}

		return $obj;
	}
}

?>
