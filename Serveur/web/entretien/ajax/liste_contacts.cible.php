<?php
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('controleur', 'contact.class', 'php');

	if( empty($_POST) ) {
        die;
    }
	
	// On verifie la presence de l'id du creneau choisis
	if( !empty($_POST['nom_entreprise']) ){
		$_entreprise = $_POST['nom_entreprise'];
	}else{
		die;
	}
	
	$contact = new Contact();
	$retour = $contact::GetListeContactsParNomEntreprise($_entreprise);
	
	/*
	 * Renvoyer le JSON
	 */
	$json['code'] = ($retour != NULL) ? 'ok' : 'error';
	// FIXME comment distinguer s'il n'y a pas de résultats ou une erreur ?
	if ($retour != NULL) {
		$json['contact'] = $retour;
	}
	echo json_encode($json);
	
?>