<?php
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('controleur', 'creneau.class', 'php');

	if( empty($_POST) ) {
        die;
    }
	
	// On verifie la presence de l'id du creneau choisis
	if( !empty($_POST['date']) ){
		$_date = $_POST['date'];
	}else{
		die;
	}
	
	$creneau = new Creneau();
	$retour = $creneau::GetListeCreneauxByDate($_date);
	
	//TODO: Construire l'objet à renvoyer
	/*
	 * Renvoyer le JSON
	 */
	$json['code'] = ($retour != NULL) ? 'ok' : 'error';
	// FIXME comment distinguer s'il n'y a pas de résultats ou une erreur ?
	if ($retour != NULL) {
		$json['creneau'] = $retour;
	}
	echo json_encode($json);
	
?>