<?php
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('controleur', 'entretien.class', 'php');

//TODO: decommenterSi l'utilisateur n'est pas un admin, on arrete tout
/*if (!Utilisateur_connecter('administrateur')) {
      die;
}
*/
	/*if( empty($_POST) ) {
        die;
    }*/
	
	// On verifie la presence de l'id du creneau choisis
	/*if( !empty($_POST['id_entretien']) ){
		$_id_entretien = $_POST['id_entretien'];
	}else{
		die;
	}*/
	$_id_entretien = 3;
	
	$retour = Entretien::ValiderEntretien($_id_entretien);
	
	//Test du retour
	if( $retour != $_id_entretien ){
		echo 'erreur de traitement en base de donnee';
	}else{
		echo 'Ok';
	}
	
?>