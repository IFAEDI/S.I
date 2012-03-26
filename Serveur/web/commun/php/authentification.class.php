<?php
/***************************************
* Author : Sébastien Mériot	       *
* Date   : 27.03.2012                  *
* Description : Classe gestion l'auth- *
* entification des utilisateurs.       *
***************************************/

/* Considère que les sessions sont déjà initialisées */

inclure_fichier( 'commun', 'config.inc', 'php' );
inclure_fichier( 'commun', 'CAS',    'php' );
inclure_fichier( 'commun', 'bd.inc',     'php' );


class Authentification {

	/* Constantes partagées concernant le mode d'authentification */
	const AUTH_CAS 		= 1;	
	const AUTH_NORMAL	= 2;

	/* La même mais pour les indices de session */
	const S_AUTH_METHOD	= 'AUTH_METHOD';
	const S_IS_USER_AUTH	= 'IS_USER_AUTH';



	/* Constructeur */
	public function __construct() {
		global $CONFIG;

		/* On fixe les paramètre du serveur CAS à interroger (contenus dans config.inc.php) */
		phpCAS::client( CAS_VERSION_2_0, $CONFIG['sso']['server'], $CONFIG['sso']['port'], $CONFIG['sso']['root'] );
		phpCAS::setNoCasServerValidation();
	}



	/* Force l'authentification par le CAS */
	public function authentificationCAS() {

		$_SESSION[self::S_AUTH_METHOD] = self::AUTH_CAS;
		phpCAS::forceAuthentication();
	}

	/**
	* Force l'authentification normale avec le couple login/passwd
	* $utilisateur : chaîne de caractère contenant l'utilisateur
	* $mdp : mot de passe codé en SHA1
	* @return True si l'auth a réussi, false sinon
	*/
	public function authentificationNormale( $utilisateur, $mdp ) {

		/* Requête à la base de données pour essayer de trouver l'utilisateur */
		$_SESSION[self::$S_AUTH_METHOD] = self::AUTH_NORMAL;

		return false;
	}

	/**
	* Vérifie que l'utilisateur est bien authentifié (soit via le CAS, soit via l'auth classique)
	* @return True s'il l'est, false sinon
	*/
	public function isAuthentifie() {

		/* Test de la variable de session dans un premier temps */
		if( @$_SESSION[self::S_IS_USER_AUTH] == true ) {
			return true;
		}

		/* Si le test échoue, on regarde auprès du CAS au cas où */
		if( $_SESSION[self::S_AUTH_METHOD] == self::AUTH_CAS ) {
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

		/* On demande à CAS de déconnecter l'utilisateur */
		if( $_SESSION[self::S_AUTH_METHOD] == self::AUTH_CAS ) {
		        phpCAS::logoutWithUrl(  ); /* A améliorer */
		}
		else {
			/* On enlève le flag relatif au username */
		}
	}

	public function getUtilisateur() {
		/* Retourne le nom d'utilisateur */
		// $login = phpCAS::getUser();
	}

};

?>
