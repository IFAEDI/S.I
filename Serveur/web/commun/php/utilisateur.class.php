<?php

require_once dirname(__FILE__) . '/base.inc.php';
inclure_fichier('modele', 'personne.class', 'php');

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
        
		/* Le login est null, on crée un objet vide */
		if( $login == null ) return;

		$result = $this->_fetchData( $login );

		/* Si l'objet n'a pas pu être créé, c'est sans doute que c'est une auth via le CAS et que l'user est pas en base */
		if( $result == false ) {

			/* On s'en occupe donc ! */
			/* Ajout en base */
			$result = BD::executeModif( 'INSERT INTO UTILISATEUR(LOGIN, PASSWD, AUTH_SERVICE) VALUES( :login, NULL, :service )', 
				array( 'login' => $login, 'service' => Authentification::AUTH_CAS ) );

			if( $result == 0 ) {
				throw new Exception( 'Impossible d\'insérer le nouvel utilisateur en base.' );
			} 

			/* Et on rappelle pour fetcher les éléments */
			$result = $this->_fetchData( $login );
			if( $result == false ) {
				throw new Exception( 'Impossible de construire l\'utilisateur (erreur de bdd).' );
			}
		}

		$this->personne = null;
	}

	/**
	* Fonction récupérant tous les attributs de l'utilisateur
	* $login : Identifiant de l'utilisateur a connecter
	* @return True si tout est ok, false sinon
	*/
	private function _fetchData( $login ) {

		/* Requête à la base pour récupérer le bon utilisateur et construire l'objet */
		$result = BD::executeSelect( 'SELECT * FROM UTILISATEUR WHERE LOGIN = :login', array( 'login' => $login ), BD::RECUPERER_UNE_LIGNE );

		if( $result == null ) {
			return false;
		}
	
		$this->_autoComplete( $result );
		return true;
	}

	/**
	* Met à jour les attributs de l'instance avec les données récupérées par la requête
	* $result : Un result set contenant les résultats d'une requête SELECT
	*/
	private function _autoComplete( $result ) {

		$this->id = $result['ID_UTILISATEUR'];
                $this->login = $result['LOGIN'];
                $this->service = $result['AUTH_SERVICE'];
                $this->banni = $result['BANNI'];
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
        * Fonction faisant le changement du login de l'utilisateur
        * @return Vrai si tout est ok, faux sinon
        */
        public function changeLogin( $login ) {

                /* Requête à la base */
                $result = BD::executeModif( 'UPDATE UTILISATEUR SET LOGIN = :login WHERE ID_UTILISATEUR = :id',
                                                        array( 'login' => $login, 'id' => $this->id ) );

		if( $result == 1 ) {
			$this->login = $login;
			return true;
		}

                return false;
        }

	/**
	* Supprime un utilisateur et potentiellement la personne associée de la base
	* $supprimerPersonne : Booléen disant s'il on doit supprimer ou non la personne associée
	* @return Vrai si tout est ok, faux sinon
	*/
	public function supprimerUtilisateur( $supprimerPersonne ) {

		$result = BD::executeModif( 'DELETE FROM UTILISATEUR WHERE ID_UTILISATEUR = :id', array( 'id' => $this->id ) );

		if( $result == 0 ) {
			return false;
		}

		if( $supprimerPersonne == true ) {
			$p = $this->getPersonne();
			return $p->supprimerPersonne();
		}

		return true;
	}

	/**
	* Retourne la personne associée à l'utilisateur
	*/
	public function getPersonne() {
	
		if( $this->personne == null ) {
			$this->personne = new Personne( $this );
		}

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
		$result = BD::executeSelect( 'SELECT LOGIN FROM UTILISATEUR', array(), BD::RECUPERER_TOUT );

		$i = 0;
		foreach( $result as $row ) {

			$obj[$i] = new Utilisateur( $row['LOGIN'] );
			$i++;
		}

		return $obj;
	}

        /**
        * Recupère un utilisateur associé à une personne
        * $id : L'identifiant de l'utilisateur à rechercher
        * $personne : Une instance de la classe personne (optionnel)
        * @return Une instance utilisateur si un utilisateur a été trouvé, null sinon
        */
        public static function RecupererUtilisateur( $id, $personne = null ) {

		/* Recherche dans la base */
                $result = BD::executeSelect( 'SELECT LOGIN FROM UTILISATEUR WHERE ID_UTILISATEUR = :id', array( 'id' => $id ) );

                if( $result == null ) {
                        return null;
                }

		/* Création de l'utilisateur avec le login */
		$utilisateur = new Utilisateur( $result['LOGIN'] );

		/* Si une instance de Personne a été passée, on l'associe pour éviter trop de requêtes */
		if( $personne != null ) {
			$utilisateur->personne = $personne;
		}

                return $utilisateur;
        }

	/**
	* Ajoute un nouvel utilisateur en base
	* $login : Le login du nouvel utilisateur
	* $pwd : Le mot de passe du nouvel utilisateur
	* @return La nouvelle instance créée ou null
	*/
	public static function AjouterUtilisateur( $login, $pwd ) {

		$result = BD::executeModif( 'INSERT INTO UTILISATEUR( LOGIN, PASSWD, AUTH_SERVICE) VALUES( :login, :pwd, :service )',
					array( 'login' => $login, 'pwd' => $pwd, 'service' => Authentification::AUTH_NORMAL ) );

		if( $result == 0 ) {
			return null;
		}

		return new Utilisateur( $login );
	}

	/**
	* Recherche un utilisateur avec ce login
	* $login : Le login à rechercher
	* @return Vrai s'il existe, faux sinon
	*/
	public static function UtilisateurExiste( $login ) {

		$result = BD::executeSelect( 'SELECT COUNT(*) AS CPT FROM UTILISATEUR WHERE LOGIN = :login', array( 'login' => $login ) );

		if( $result == null ) {
			return false;
		}

		return ($result['CPT'] > 0);
	}

}

?>
