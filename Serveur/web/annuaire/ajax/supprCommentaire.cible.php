<?php
/**
 * -----------------------------------------------------------
 * SUPPRCOMMENTAIRE - CIBLE PHP
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Contact - benjamin.planche@aldream.net
 * ---------------------
 * Cible pour la suppression d'un comm'.
 * Est donc appelée par le moteur JS (Ajax) de la page Annuaire quand un comm' est sélectionné.
 * Le principe (repris de Bnj Bouv) est très simple :
 * 1) On récupère l'ensemble des variables qui ont été insérées.
 * 2) On appelle le contrôleur 
 * 3) On renvoit les résultats en JSON
 * Le résultat sera de la forme :
 		{
			code : "ok", // ou "error"
		}
 */

header( 'Content-Type: application/json' );

 // Vérification de l'authentification :
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('modele', 'commentaire_entreprise.class', 'php');

$logger = Logger::getLogger("Annuaire.supprCommentaire");

$utilisateur = controlerAuthentificationJSON( $logger, array( Personne::ADMIN, Personne::AEDI ) );
$logger->debug( "\"".$utilisateur->getLogin()."\" a lancé une requête." );

/*
 * Récupérer et transformer le JSON
 */
/* int */ $id = 0;
if (verifierPresent('id')) {
	$id = intval($_POST['id']);

	/* bool */ $codeRet = CommentaireEntreprise::SupprimerCommentaireByID($id);

	/*
	 * Renvoyer le JSON
	 */
	 if ($codeRet === 0) {
		$json['code'] = 'erreurChamp';
		$json['mesg'] = 'Veuillez vérifier les champs renseignés.';
	}
	elseif ($codeRet === CommentaireEntreprise::getErreurExecRequete()) {
		$json['code'] = 'errorBDD';
		$json['mesg'] = 'Une erreur est survenue.';

		$logger->error( 'Une erreur est survenue.' );
	}
	else {
		$json['code'] = 'ok';
		$json['mesg'] = 'Commentaire supprimé.';

		$logger->info( '"'.$utilisateur->getLogin().'" a supprimé le commentaire #'.$id.'.' );
	}
}
else {
	$json['code'] = 'erreurChamp';
	$json['mesg'] = 'Veuillez vérifier les champs renseignés.';
}

echo json_encode($json);

?>
