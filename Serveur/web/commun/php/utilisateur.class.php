<?php

require_once dirname(__FILE__) . '/base.inc.php';
inclure_fichier('commun', 'bd.inc', 'php');

class Utilisateur {

	/* Constantes liées aux utilisateurs */
	const UTILISATEUR_ETUDIANT	= 0;
	const UTILISATEUR_ENSEIGNANT	= 1;
	const UTILISATEUR_ENTREPRISE	= 2;
	const UTILISATEUR_ADMIN		= 3;

	/****************  Attributs  ******************/
	private $id;
	private $login;
	private $nom;
	private $prenom;
	private $annee;
	private $mail;
	private $premiereConnexion;
	private $typeUtilisateur;


	/**
	* Constructeur
	* $login : chaîne de caractère contenant le nom de l'utilisateur à créer
	* @throws Exception si impossibilité de créer l'utilisateur
	*/
	public function __construct( $login ) {
        
		$result = $this->_fetchData( $login );

		/* Si l'objet n'a pas pu être créé, c'est sans doute que c'est une auth via le CAS et que l'user est pas en base */
		if( $result == false ) {

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
	}

	/**
	* Fonction récupérant tous les attributs de l'utilisateur
	* $login : Identifiant de l'utilisateur a connecter
	* @return True si tout est ok, false sinon
	*/
	private function _fetchData( $login ) {

		/* Requête à la base pour récupérer le bon utilisateur et construire l'objet */
		$result = BD::executeSelect( 'SELECT * FROM UTILISATEUR WHERE login = :login', array( 'login' => $login ), BD::RECUPERER_UNE_LIGNE );

		if( $result == null )
			return false;

		$this->id = $result['id'];
		$this->login = $result['login'];
		$this->nom = $result['nom'];
		$this->prenom = $result['prenom'];
		$this->annee = $result['annee'];
		$this->mail = $result['mail'];
		$this->premiereConnexion = $result['premiere_connexion'];
		$this->typeUtilisateur = $result['type'];

		return true;
	}

	/**
	* Fonction faisant le changement du mot de passe de l'utilisateur
	* @return Vrai si tout est ok, faux sinon
	*/
	public function changePassword( $mdp ) {

		/* Requête à la base */
		$result = BD::executeModif( 'UPDATE UTILISATEUR SET mdp = :mdp WHERE id = :id', array( 'mdp' => $mdp, 'id' => $this->id ) );

		return ($result == 1);
	}

	/**
	* Change les informations personnelles de l'utilisateur
	* @return Vrai si tout est ok, faux sinon
	*/
	public function changeInfoPerso( $nom, $prenom, $mail, $annee ) {

		/* Requête à la base */
		$result = BD::executeModif( 'UPDATE UTILISATEUR SET nom = :nom, prenom = :prenom, annee = :annee, mail = :mail, premiere_connexion = 0 WHERE id = :id',
			array( 'nom' => $nom, 'prenom' => $prenom, 'annee' => $annee, 'mail' => $mail, 'id' => $this->id ) );

		if( $result == 0 ) {
			return false;
		}

		$this->nom = $nom;
		$this->prenom = $prenom;
		$this->annee = $annee;
		$this->mail = $mail;
		$this->premiereConnexion = false;

		return true;
	}

	/**
	* Change le type d'utilisateur
	* @return Vrai si tout est ok, faux sinon
	*/
	public function changeUtilisateurType( $type ) {

		/* Requête de mise à jour */
		$result = BD::executeModif( 'UPDATE UTILISATEUR SET type = :type WHERE id = :id', array( 'type' => $type, 'id' => $this->id ) );

		if( $result == 0 ) {
			return false;
		}

		$this->typeUtilisateur = $type;

		return true;
	}


	/**
	* Détermine si c'est la première connexion de l'utilisateur ou non
	* @return Vrai si c'est le cas, faux sinon
	*/
	public function premiereConnexion() {

		return $this->premiereConnexion;
	}

	public function getTypeUtilisateur() {
	
		return $this->typeUtilisateur;
	}

	/**
	* Retourne le nom d'utilisateur de l'utilisateur
	*/
	public function getLogin() {
		return $this->login;
	}

	public function getId() {
		return $this->id;
	}

	public function getNom() {
		return $this->nom;
	}

	public function getPrenom() {
		return $this->prenom;
	}

	public function getAnnee() {
		return $this->annee;
	}

	public function getMail() {
		return $this->mail;
	}
}

?>
