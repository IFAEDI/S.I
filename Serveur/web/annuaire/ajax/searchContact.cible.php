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
 * 1) On r�cup�re l'ensemble des mots-cl�s demand�s et des champs associ�s, et on les s�curise.
 * 2) On appelle le contr�leur 
 * 3) On renvoit les r�sultats en JSON
 * Le r�sultat sera de la forme :
 		{
			code : "ok", // ou "errorBDD" ou "erreurChamp" ou "erreurRequete" - si erreur, les champs contact n'est pas pr�sent
			entreprises : [{
				nom: "Atos",
				id : 1,
				contacts: [
					{nom: "Chuck", prenom: "Noris", metier: "Dieu", email:"chuck@atos.com", tel:"06666666666", priorite:1, commentaire:""},
					{nom: "Chucky", prenom: "Norissette", metier: "D�esse", email:"chuckky@atos.com", tel:"06666666667", priorite:0, commentaire:"A v�rifier"}
				]},
				
				nom: "Fiducial",
				id : 2,
				contacts: [
					{nom: "Chuck", prenom: "Noris", metier: "Dieu", email:"chuck@atos.com", tel:"06666666666", priorite:1, commentaire:""},
					{nom: "Chucky", prenom: "Norissette", metier: "D�esse", email:"chuckky@atos.com", tel:"06666666667", priorite:0, commentaire:"A v�rifier"}
				]},
				...],
			champ : "XXX" // pr�sent seulement si code = "erreurChamp" - Nom du champ invalide
		}
 */

require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('controleur', 'contact.class', 'php');

		
/*
 * R�cup�rer et transformer le JSON
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
