<?php
/**
 * -----------------------------------------------------------
 * supprEntreprise - CIBLE PHP
 * -----------------------------------------------------------
 * Auteur : Sébastien Mériot (4IF 2011/12)
 *          Contact - sebastien.meriot@gmail.com
 * ---------------------
 * Contrôleur permettant de supprimer une entreprise via son ID
 */
 
header( 'Content-Type: application/json' );
 
 // Vérification de l'authentification :
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('modele', 'entreprise.class', 'php');

$logger = Logger::getLogger("Annuaire.supprEntreprise");

$utilisateur = controlerAuthentificationJSON( $logger, array( Personne::ADMIN, Personne::AEDI ) );
$logger->debug( "\"".$utilisateur->getLogin()."\" a lancé une requête." );


/*
 * Récupérer et transformer le JSON
 */
/* int */ $id_entreprise = NULL;

if (verifierPresent('id_entreprise')) {
	$id_entreprise = intval($_POST['id_entreprise']);


	try {
		Entreprise::SupprimerEntrepriseByID($id_entreprise);
	
		$logger->info( '"'.$utilisateur->getLogin().'" a supprimé l\'entreprise #'.$id_entreprise.'.' );
		$json = genererReponseStdJSON( 'ok', 'Entreprise supprimée.' );
	}
	catch( Exception $e ) {
		$logger->error( "Une erreur est survenue. [".$e->getMessage()."]" );
		$json = genererReponseStdJSON( 'error', 'Une erreur interne est survenue lors de la suppression.' );
	}
}
else {
	$json = genererReponseStdJSON( 'erreurChamp', 'Le champ "id_entreprise" n\'est pas renseigné.' );

}


echo json_encode($json);

?>
