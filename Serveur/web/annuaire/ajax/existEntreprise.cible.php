<?php
/**
 * -----------------------------------------------------------
 * EXISTENTREPRISE - CIBLE PHP
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Contact - benjamin.planche@aldream.net
 * ---------------------
 * Cible recevant en entrée un nom d'entreprise et renvoyant un booléen à true si cette entreprise existe en BDD et false sinon.
 */
 
header( 'Content-Type: application/json' );
 
 // Vérification de l'authentification :
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';

inclure_fichier('modele', 'entreprise.class', 'php');

$logger = Logger::getLogger("Annuaire.existEntreprise");

$utilisateur = controlerAuthentificationJSON( $logger, array( Personne::ADMIN, Personne::AEDI ) );
$logger->debug( "\"".$utilisateur->getLogin()."\" a lancé une requête." );

/*
 * Récupérer et transformer le JSON
 */
/* int */ $nom_entreprise = NULL;

if (verifierPresent('name')) {
	$nom_entreprise = Protection_XSS(urldecode($_POST['name']));

	/* booléen */ $existsName = Entreprise::ExistsName($nom_entreprise);
	$json['code'] = 'ok';
	$json['answer'] = $existsName;
}
else {
	$json['code'] = 'erreurChamp';
	$json['answer'] = false;
}


echo json_encode($json);

?>
