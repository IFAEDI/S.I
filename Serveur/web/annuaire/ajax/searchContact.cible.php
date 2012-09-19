<?php
/**
 * -----------------------------------------------------------
 * SEARCHCONTACT - CIBLE PHP
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Contact - benjamin.planche@aldream.net
 * ---------------------
 * Cible pour la recherche de contacts.
 * Le principe est le suivant :
 * 1) On récupère l'ensemble des mots-clés demandés et des champs associés, et on les sécurise.
 * 2) On appelle le contrôleur 
 * 3) On renvoit les résultats en JSON
 * Le résultat sera de la forme :
 		{
			code : "ok", // ou "errorBDD" ou "erreurChamp" ou "erreurRequete" - si erreur, les champs contact n'est pas présent
			entreprises : [{
				nom: "Atos",
				id : 1,
				contacts: [
					{nom: "Chuck", prenom: "Noris", metier: "Dieu", email:"chuck@atos.com", tel:"06666666666", priorite:1, commentaire:""},
					{nom: "Chucky", prenom: "Norissette", metier: "Déesse", email:"chuckky@atos.com", tel:"06666666667", priorite:0, commentaire:"A vérifier"}
				]},
				
				nom: "Fiducial",
				id : 2,
				contacts: [
					{nom: "Chuck", prenom: "Noris", metier: "Dieu", email:"chuck@atos.com", tel:"06666666666", priorite:1, commentaire:""},
					{nom: "Chucky", prenom: "Norissette", metier: "Déesse", email:"chuckky@atos.com", tel:"06666666667", priorite:0, commentaire:"A vérifier"}
				]},
				...],
			champ : "XXX" // présent seulement si code = "erreurChamp" - Nom du champ invalide
		}
 */

header( 'Content-Type: application/json' );

require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('modele', 'contact.class', 'php');

$logger = Logger::getLogger("Annuaire.searchContact");

$utilisateur = controlerAuthentificationJSON( $logger, array( Personne::ADMIN, Personne::AEDI ) );
$logger->debug( "\"".$utilisateur->getLogin()."\" a lancé une requête." );
	
/*
 * Récupérer et transformer le JSON
 */
/* Array */ $keywords = array();
/* Array */ $json = array();
if (verifierPresentObjet('keywords')) {
	$keywords = $_POST['keywords'];
	if (!is_array($keywords)) {
		$json['code'] = 'erreurRequete';
	}
	else {
		$nb = count($keywords);
		for($i = 0; $i < $nb; $i++) {
			$keywords[$i]['champ'] = Protection_XSS(urldecode($keywords[$i]['champ']));
			$keywords[$i]['val'] = Protection_XSS(urldecode($keywords[$i]['val']));
		}
		
		$contacts = Contact::Rechercher($keywords);
		if ($contacts == Contact::getErreurExecRequete()) {
			$json['code'] = 'errorBDD';
			$logger->error( 'Une erreur est survenue.' );
		}
		else if ($contacts == Contact::getErreurChampInconnu()) {
			$json['code'] = 'erreurChamp';
		}
		else {
			$json['code'] = 'ok';
			if (gettype($contacts) == 'array') {
				$listIdEntr = array();
				$json['entreprises'] = Array();
				foreach( $contacts as $contact ) {

					$idEntr = $contact->getEntreprise()->getId();
					if (!in_array($idEntr, $listIdEntr)) {
						array_push($listIdEntr, $idEntr);
						array_push($json['entreprises'], array('id'=>$idEntr, 'nom'=>Entreprise::GetEntrepriseByID($idEntr)->getNom(), 'contacts'=>array($contact->toArrayObject(false, true, true, true, false, false, false))));
					}
					else {
						$nbEntr = count($json['entreprises']);
						for($i = 0; $i < $nbEntr; $i++) {
							if ($json['entreprises'][$i]["id"] == $idEntr) {
								array_push($json['entreprises'][$i]["contacts"], $contact->toArrayObject(false, true, true, true, false, false, false));
								break;
							}
						}
					}
					
					
				}
			}
		}
	}
}

echo json_encode($json);

?>
