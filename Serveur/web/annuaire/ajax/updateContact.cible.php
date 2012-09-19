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

header( 'Content-Type: application/json' );

require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('modele', 'entreprise.class', 'php');
inclure_fichier('modele', 'contact.class', 'php');
inclure_fichier('modele', 'ville.class', 'php');

$logger = Logger::getLogger("Annuaire.updateContact");

$utilisateur = controlerAuthentificationJSON( $logger, array( Personne::ADMIN, Personne::AEDI ) );
$logger->debug( "\"".$utilisateur->getLogin()."\" a lancé une requête." );
		
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

/* Vérification de la présence des champs requis */
if (verifierPresent('id_entreprise') && verifierPresent( 'fonction' ) && verifierPresentObjet( 'personne' ) && verifierPresentObjet( 'ville' )) {
	/* Entreprise */
	$id_entreprise = intval($_POST['id_entreprise']);
	/* Fonction */
	$fonction = Protection_XSS(urldecode($_POST['fonction']));

	/* Personne */
	$personne = $_POST['personne'];
	$personne['nom'] = Protection_XSS(urldecode($personne['nom']));
	$personne['prenom'] = Protection_XSS(urldecode($personne['prenom']));
	$personne['id'] = intval($personne['id']);
	$compteur = 0;
	if (array_key_exists('telephones', $personne)) {
		$compteur = count($personne['telephones']);

		for($i = 0; $i < $compteur; $i++) {
			$personne['telephones'][$i][0] = Protection_XSS(urldecode($personne['telephones'][$i][0]));
			$personne['telephones'][$i][1] = Protection_XSS(urldecode($personne['telephones'][$i][1]));
		}
	}

	$compteur = 0;
	if (array_key_exists('mails', $personne)) {
		$compteur = count($personne['mails']);

		for($i = 0; $i < $compteur; $i++) {
			$personne['mails'][$i][0] = Protection_XSS(urldecode($personne['mails'][$i][0]));
			$personne['mails'][$i][1] = Protection_XSS(urldecode($personne['mails'][$i][1]));
		}
	}

	/* Ville */
	$ville = $_POST['ville'];
	$ville['code_postal'] = Protection_XSS(urldecode($ville['code_postal']));
	$ville['libelle'] = Protection_XSS(urldecode($ville['libelle']));
	$ville['pays'] = Protection_XSS(urldecode($ville['pays']));

	/* Vérification des champs facultatifs et récupération de leurs valeurs */
	if (verifierPresent('id_contact')) {
		$id = intval($_POST['id_contact']);
	}
	if (verifierPresent('commentaire')) {
		$commentaire = Protection_XSS(urldecode($_POST['commentaire']));
	}
	if (verifierPresent('priorite')) {
		$priorite = intval($_POST['priorite']);
	}

	/*
	 * Si l'ID de la personne est précisé, on récupère l'objet associé pour le mettre à jour, sinon, on ajoute la personne.
	 */

	/* obj Personne */ $personneObj;
	if ($personne['id'] > 0) {
		$personneObj = Personne::getPersonneParID($personne['id']);
	}
	else {
		$personneObj = Personne::AjouterPersonne($personne['nom'], $personne['prenom'], Personne::ENTREPRISE);
	}
	
	/* 
	 * A partir des	informations fournies, on récupère la ville et l'entreprise attachées au contact
	 */
	$id_ville = Ville::VilleExiste( $ville['code_postal'], $ville['libelle'], $ville['pays'] );
	if( $id_ville == false ) {
		$id_ville = Ville::AjouterVille( $ville['code_postal'], $ville['libelle'], $ville['pays'] );
	}

	/* obj Ville */ $villeObj = new Ville($id_ville);
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
		 * Préparation du JSON
		 */
		if ($idContact === 0 || $idContact === Contact::getErreurChampInconnu()) {
			$json['code'] = 'erreurChamp';
		}
		elseif ($idContact === Contact::getErreurExecRequete()) {
			$json['code'] = 'errorBDD';

			$logger->error( 'Une erreur est survenue' );
		}
		else {
			$json['code'] = 'ok';
			$json['id'] = ($id != 0) ? 0 : $idContact;
			$json['id_personne'] = $personneObj->getId();

			$logger->info( '"'.$utilisateur->getLogin().'" a modifié le contact "'.$personne['prenom'].' '.$personne['nom'].'".' );
		}
	}
	else {
		$json['code'] ='erreurChamp';
	}
}
else {
	$json['code'] = "erreurChamp";
}


echo json_encode($json);

?>
