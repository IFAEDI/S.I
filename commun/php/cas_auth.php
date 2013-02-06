<?php
/*
 * @author Sébastien Mériot
 *
 * Utilisation de phpCAS afin d'authentifier l'utilisateur sur le service
 */

require_once( dirname( __FILE__ ) . '/config.inc.php' );
require_once( dirname( __FILE__ ) . '/base.inc.php' );
inclure_fichier('commun', 'CAS.php', 'php');


/* On fixe les paramètre du serveur à interroger (contenus dans config.inc.php) */
phpCAS::client( CAS_VERSION_2_0, $CONFIG['sso']['server'], $CONFIG['sso']['port'], $CONFIG['sso']['root'] );
phpCAS::setNoCasServerValidation();

/* Gère la demande de déconnexion */
if( @isset( $_REQUEST['logout'] ) ) {
	phpCAS::logout(); /* A améliorer */
}

/* Check si l'utilisateur est authentifié, si ce n'est pas le cas, on lui demande poliement */
if( phpCAS::isAuthenticated() == false ) {
	phpCAS::forceAuthentication();
}

/* Retourne le nom d'utilisateur */
// $login = phpCAS::getUser();

?>
