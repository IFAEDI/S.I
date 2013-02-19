<?php
/***************************************
* Author : Sébastien Mériot	       *
* Date   : 27.03.2012                  *
* Description : Classe gestion l'auth- *
* entification des utilisateurs.       *
***************************************/

/* Considère que les sessions sont déjà initialisées */

// inclure_fichier( 'commun', 'config.inc', 'php' );
require_once( dirname( __FILE__ ) . '/../../config/config.inc.php' );
inclure_fichier( 'commun', 'CAS',    'php' );
inclure_fichier( 'commun', 'bd.inc',     'php' );
inclure_fichier( 'commun', 'utilisateur.class', 'php' );


class Authentification {

	/* Constantes partagées concernant le mode d'authentification */
	const AUTH_CAS 		= 1;	
	const AUTH_NORMAL	= 2;

	public static $AUTH_TYPES = array( self::AUTH_CAS => 'CAS', self::AUTH_NORMAL => 'Classique' );

	/* La même mais pour les indices de session */
	const S_AUTH_METHOD	= 'AUTH_METHOD';
	const S_IS_USER_AUTH	= 'IS_USER_AUTH';
	const S_USER_OBJ	= 'USER_OBJ';

	/* Code d'erreur */
	const ERR_OK		= 0;
	const ERR_ID_INVALIDE   = 1;
	const ERR_AMBIGUITE     = 2;
	const ERR_BD		= 3;


	private $debug;

	/**
	* Constructeur
	*/
	public function __construct() {
		global $CONFIG;

		/* On fixe les paramètre du serveur CAS à interroger (contenus dans config.inc.php) */
		phpCAS::client( CAS_VERSION_2_0, $CONFIG['sso']['server'], $CONFIG['sso']['port'], $CONFIG['sso']['root'], 'true', 'saml' );
		phpCAS::setNoCasServerValidation();

		$this->debug = $CONFIG['debug']['auth'];
	}



	/* Force l'authentification par le CAS */
	public function authentificationCAS() {

		$_SESSION[self::S_AUTH_METHOD] = self::AUTH_CAS;
		phpCAS::forceAuthentication();
	}

	/**
	* Force l'authentification normale avec le couple login/passwd
	* $login : chaîne de caractère contenant l'utilisateur
	* $mdp : mot de passe codé en SHA1
	* @return ERR_OK si l'auth a réussi, ERR_ID_INVALIDE si les identifiants sont incorrectes, ERR_AMBIGUITE s'il y a plusieurs tuples identiques en base
	*/
	public function authentificationNormale( $login, $mdp ) {

		$_SESSION[self::S_AUTH_METHOD] = self::AUTH_NORMAL;

		/* Requête à la base de données pour essayer de trouver l'utilisateur */
		try {
			$result = BD::executeSelect( 'SELECT COUNT(*) AS CPT FROM UTILISATEUR WHERE LOGIN = :login AND PASSWD = :passwd AND AUTH_SERVICE = :service', array( ':login' => $login, ':passwd' => $mdp, ':service' => self::AUTH_NORMAL ) );
		}
		catch( Exception $e ) {

			/* Mode debug, on retourne une erreur via l'exception plus intéressante ! */
			if( $this->debug == true ) {
				throw new Exception( $e->getMessage() . ' - ' . BD::getDerniereErreur() );
			}

			return self::ERR_BD;
		}


		/* On regarde que l'on a bien un objet et on fait la vérification */
		if( $result == null ) {
			return self::ERR_BD;
		}

		/* Les cas d'échec */
		if( $result['CPT'] == 0 )
			return self::ERR_ID_INVALIDE;
		else if( $result['CPT'] > 1 )
			return self::ERR_AMBIGUITE;

		/* Création de l'objet utilisateur */
		try {
			$this->creerObjetUtilisateur( $login );
		}
		catch( Exception $e ) {

			/* Mode debug, on retourne une erreur via l'exception plus intéressante ! */
			if( $this->debug == true ) {
				throw new Exception( $e->getMessage() . ' - ' . BD::getDerniereErreur() );
			}

			return self::ERR_BD;
		}

		$_SESSION[self::S_IS_USER_AUTH] = true;

		return self::ERR_OK;
	}

	/**
	* Création de l'objet utilisateur en session
	* $login : Le nom d'utilisateur en chaîne de caractère de l'utilisateur à créer
	* @throws Exception en cas d'erreur
	*/
	private function creerObjetUtilisateur( $login ) {

		@$_SESSION[self::S_USER_OBJ] = new Utilisateur( $login );
	}

	/**
	* Vérifie que l'utilisateur est bien authentifié (soit via le CAS, soit via l'auth classique)
	* @return True s'il l'est, false sinon
	*/
	public function isAuthentifie() {

		/* Actualisation du CAS pour voir si la session n'a pas espiré */
		phpCAS::handleLogoutRequests();

		/* Test de la variable de session dans un premier temps */
		if( @$_SESSION[self::S_IS_USER_AUTH] == true ) {
			return true;
		}

		/* Si le test échoue, on regarde auprès du CAS au cas où */
		if( @$_SESSION[self::S_AUTH_METHOD] == self::AUTH_CAS ) {
			if( phpCAS::isAuthenticated() == true ) {

				/* On fixe la variable de session pour éviter de redemander à CAS */
				$_SESSION[self::S_IS_USER_AUTH] = true;
				return true;
			}
		}

		return false;
	}


	/**
	* Force la déconnexion de l'utilisateur
	*/
	public function forcerDeconnexion() {

		/* On bascule le flag à faux */
		$_SESSION[self::S_IS_USER_AUTH] = false;
		$_SESSION[self::S_USER_OBJ]	= null;

		/* On demande à CAS de déconnecter l'utilisateur */
		if( @$_SESSION[self::S_AUTH_METHOD] == self::AUTH_CAS ) {
			$_SESSION[self::S_AUTH_METHOD]  = null;

            phpCAS::logout(array('url' => 'http://ifaedi.insa-lyon.fr'));
		}

		$_SESSION[self::S_AUTH_METHOD]  = null;
	}


	/**
	* Retourne l'objet utilisateur correspondant à l'utilisateur actuel ou null, s'il n'existe pas.
	*/
	public function getUtilisateur() {

		/* Check si l'utilisateur est bien authentifié */
		if( @$_SESSION[self::S_IS_USER_AUTH] == true )
		{
			/* Regarde si l'objet est bien setté */
			if( !( @isset( $_SESSION[self::S_USER_OBJ] ) ) ) {
				/* Si l'authentification est régulière, pas d'excuse, fuck */
				if( $_SESSION[self::S_AUTH_METHOD] == self::AUTH_NORMAL ) {
					return null;
				}

				else if( $_SESSION[self::S_AUTH_METHOD] == self::AUTH_CAS ) {
					/* Pour le CAS, on a pas pu créer l'utilisateur... Donc on le fait à la voler ici */
					$this->authentificationCAS();
					$login = phpCAS::getUser();
					try {
						$this->creerObjetUtilisateur( $login );
					}
					catch( Exception $e ) {

						/* Mode debug, on retourne une erreur via l'exception plus intéressante ! */
						if( $this->debug == true ) {
							throw new Exception( $e->getMessage() . ' - ' . BD::getDerniereErreur() );
						}
					}
				}
			}

			return $_SESSION[self::S_USER_OBJ];
		}

		return null;
	}

	/**
	* Retourne la méthode d'authentification
	*/
	public function getAuthentificationMethode() {

		return $_SESSION[self::S_AUTH_METHOD];
	}
};

?>
