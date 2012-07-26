<?php
/**
 * -----------------------------------------------------------
 * AJOUTCONTACT - CIBLE PHP
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Contact - benjamin.planche@aldream.net
 * ---------------------
 * Cible pour l'ajout d'un contact.
 * Le principe (repris de Bnj Bouv) est très simple :
 * 1) On récupère l'ensemble des variables qui ont été insérées.
 * 2) On appelle le contrôleur 
 * 3) On renvoit les résultats en JSON
 * Le résultat sera de la forme :
 		{
			code : "ok", // ou "error" - si error, les champs id n'est pas présent
			id : 1 		// ID du contact ajouté
			id_personne : 1 		// ID de la personne associée
		}
 */

require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('controleur', 'entreprise.class', 'php');
inclure_fichier('controleur', 'contact.class', 'php');
inclure_fichier('controleur', 'ville.class', 'php');
inclure_fichier('controleur', 'etudiant.class', 'php');
inclure_fichier('commun', 'personne.class', 'php');
		
/*
 * Récupérer et transformer le JSON
 */
/* int */ $id = 0;
/* int */ $id_entreprise = 0;
/* string */ $fonction = NULL;
/* objet */ $personne = NULL;
/* objet */ $ville = NULL;
/* string */ $commentaire = '';
/* int */ $priorite = 0;

if (verifierPresent('id_contact')) {
	$id = intval($_POST['id_contact']);
}
if (verifierPresent('id_entreprise')) {
	$id_entreprise = intval($_POST['id_entreprise']);
}
if (verifierPresent('fonction')) {
	$fonction = Protection_XSS(urldecode($_POST['fonction']));
}
if (verifierPresentObjet('personne')) {
	$personne = $_POST['personne'];
	$personne['nom'] = Protection_XSS(urldecode($personne['nom']));
	$personne['prenom'] = Protection_XSS(urldecode($personne['prenom']));
	$personne['id'] = intval($personne['id']);
	$compteur = 0;
	if (array_key_exists('telephones', $personne)) {
		$compteur = count($personne['telephones']);
	}
	for($i = 0; $i < $compteur; $i++) {
		$personne['telephones'][$i][0] = Protection_XSS(urldecode($personne['telephones'][$i][0]));
		$personne['telephones'][$i][1] = Protection_XSS(urldecode($personne['telephones'][$i][1]));
	}
	$compteur = 0;
	if (array_key_exists('mails', $personne)) {
		$compteur = count($personne['mails']);
	}
	for($i = 0; $i < $compteur; $i++) {
		$personne['mails'][$i][0] = Protection_XSS(urldecode($personne['mails'][$i][0]));
		$personne['mails'][$i][1] = Protection_XSS(urldecode($personne['mails'][$i][1]));
	}
}
if (verifierPresentObjet('ville')) {
	$ville = $_POST['ville'];
	$ville['code_postal'] = Protection_XSS(urldecode($ville['code_postal']));
	$ville['libelle'] = Protection_XSS(urldecode($ville['libelle']));
	$ville['pays'] = Protection_XSS(urldecode($ville['pays']));
}
if (verifierPresent('commentaire')) {
	$commentaire = Protection_XSS(urldecode($_POST['commentaire']));
}
if (verifierPresent('priorite')) {
	$priorite = intval($_POST['priorite']);
}

/*
 * Appeler la couche du dessous
 */
 /* obj Personne */ $personneObj;
if ($personne['id'] > 0) {
	$personneObj = Personne::getPersonneParID($personne['id']);
}
else {
	$personneObj = Personne::AjouterPersonne($personne['nom'], $personne['prenom'], Personne::ENTREPRISE);
}
	
	
  /* obj Ville */ $villeObj = new Ville(Etudiant::GetVilleOrAdd($ville['libelle'], $ville['code_postal'], $ville['pays']));
  /* obj Entreprise */ $entrepriseObj = Entreprise::GetEntrepriseByID($id_entreprise);
 if (($personneObj != null) && ($entrepriseObj != null) && ($villeObj != null) && ($fonction != null)) {
 
	// Ajout des tels & emails :
	if (array_key_exists('telephones', $personne)) {
		$personneObj->changeTelephones( $personne['telephones'] );
	}
	if (array_key_exists('mails', $personne)) {
		$personneObj->changeMails( $personne['mails'] );
	}
	$personneObj->changeInfo( $personne['nom'],  $personne['prenom']);
	
	 /* int */ $idContact = Contact::UpdateContact( $id, $personneObj, $entrepriseObj, $villeObj, $fonction, $commentaire, $priorite );
	 
	 /*
	 * Renvoyer le JSON
	 */
	if ($idContact === 0 || $idContact === Contact::getErreurChampInconnu()) {
		$json['code'] = 'errorChamp';
	}
	elseif ($idContact === Contact::getErreurExecRequete()) {
		$json['code'] = 'errorBDD';
	}
	else {
		$json['code'] = 'ok';
		$json['id'] = ($id != 0)?0:$idContact;
		$json['id_personne'] = $personneObj->getId();
	}

 }
 else {
	$json['code'] ='erreurChamp';
 }
echo json_encode($json);


?>
