<?php
/**
 * -----------------------------------------------------------
 * SUPPRCONTACT - CIBLE PHP
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Contact - benjamin.planche@aldream.net
 * ---------------------
 * Cible pour la suppression d'un contact.
 * Est donc appelée par le moteur JS (Ajax) de la page Annuaire quand un contact est sélectionné.
 * Le principe (repris de Bnj Bouv) est très simple :
 * 1) On récupère l'ensemble des variables qui ont été insérées.
 * 2) On appelle le contrôleur 
 * 3) On renvoit les résultats en JSON
 * Le résultat sera de la forme :
 		{
			code : "ok", // ou "error"
		}
 */

 // Vérification de l'authentification :
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';

inclure_fichier('modele', 'contact.class', 'php');

$logger = Logger::getLogger("Annuaire.supprContact");

$utilisateur = controlerAuthentificationJSON( $logger, array( Personne::ADMIN, Personne::AEDI ) );
$logger->debug( "\"".$utilisateur->getLogin()."\" a lancé une requête." );

/*
 * Récupérer et transformer le JSON
 */
/* int */ $id = 0;
if (verifierPresent('id')) {
	$id = (int) $_POST['id'];

	/* bool */ $codeRet = Contact::SupprimerContactByID($id);
	if ($codeRet === Contact::getErreurExecRequete()) {
		$logger->error( 'Une erreur est survenue.' );
		$json = genererReponseStdJSON( 'errorBDD', 'Une erreur est survenue lors de l\'enregistrement des données.' );
	}
	else {
		$json = genererReponseStdJSON( 'ok', 'Contact supprimé.' );
		$logger->info( '"'.$utilisateur->getLogin().'" a supprimé le contact #'.$id.'.' );
	}
}
else {
	$json = genererReponseStdJSON( 'erreurChamp', 'Veuillez vérifier que tous les champs sont renseignés.' );
}

echo json_encode(array_map('Protection_XSS', $json));

?>
