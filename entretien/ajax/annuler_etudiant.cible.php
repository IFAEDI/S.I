<?php
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('controleur', 'creneau.class', 'php');

//TODO: decommenterSi l'utilisateur n'est pas un admin, on arrete tout
/*if (!Utilisateur_connecter('administrateur')) {
      die;
}
*/
	if( empty($_POST) ) {
        die;
    }
	
	// On verifie la presence de l'id du creneau choisis
	if( !empty($_POST['id_creneau']) ){
		$_id_creneau = $_POST['id_creneau'];
	}else{
		die;
	}
	
	// On met 0 afin de supprimer l'etudiant du creneau
	$retour = Creneau::ReserverCreneau($_id_creneau, 0);
	
	//Test du retour
	if( $retour != $_id_creneau ){
		echo 'erreur de traitement en base de donnee';
	}else{
		echo 'Ok';
	}
	
?>