<?php
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('controleur', 'entretien.class', 'php');
 
    if( empty($_POST) ) {
        die;
    }
	
	// On verifie chaque parametre
	if( verifierPresent($_POST['nom_entreprise']) ){
		$nom_entreprise = $_POST['nom_entreprise'];
	}
	if( verifierPresent($_POST['ville_entreprise']) ){
		$ville_entreprise = $_POST['ville_entreprise'];
	}
	if( verifierPresent($_POST['nom_contact']) ){
		$nom_contact = $_POST['nom_contact'];
	}
	if( verifierPresent($_POST['prenom_contact']) ){
		$prenom_contact = $_POST['prenom_contact'];
	}
	if( verifierPresent($_POST['mail_contact']) ){
		$mail_contact = $_POST['mail_contact'];
		//Verification validite adresse mail
		if( !verifierMail($mail_contact) ){
			return 'format adresse mail non valide';
		}
	}
	if( verifierPresent($_POST['tel_contact']) ){
		$tel_contact = $_POST['tel_contact'];
		//Verification validite telephone
		if( !verifierTelephone($tel_contact) ){
			return 'format numero telephone non valide';
		}
	}
	if( verifierPresent($_POST['date']) ){
		$date = $_POST['date'];
		//Verification format de la date
		if( !verifierDate($date) ){
			return 'format date non valide';
		}
	}
	if( verifierPresent($_POST['heureDebut']) ){
		$heureDeb = $_POST['heureDebut'];
		//Verification format horaire
		if( !verifierHoraire($heureDebut) ){
			return 'format horaire debut';
		}
	}
	if( verifierPresent($_POST['heureFin']) ){
		$heureFin = $_POST['heureFin'];
		//Verification format horaire
		if( !verifierHoraire($heureFin) ){
			return 'format horaire fin';
		}
	}
	//TODO : on enregistre le contact
	$contact = new Contact();
	$contact::getContactParNom();
	
	
	// On enregistre l'entretien
	$entretien = new Entretien();
	$_id = 3;
	$_id_contact = 1;
	$_date = $date;
	$_etat = 0; // Par defaut l'etat sera 0 donc a valider !!
	$entretien::UpdateEntretien($_id, $_id_contact, $_date, $_etat);

	//TODO: On créer les créneaux associés

	
?>
