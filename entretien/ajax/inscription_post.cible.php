<?php
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('controleur', 'entretien.class', 'php');
inclure_fichier('modele', 'contact.class', 'php');
inclure_fichier('controleur', 'creneau.class', 'php');
 
	if( empty($_POST) ) {
        die;
    }
	
	// On verifie chaque parametre
	if( !empty($_POST['nom_entreprise']) ){
		$nom_entreprise = $_POST['nom_entreprise'];
	}
	if( !empty($_POST['nom_contact']) ){
		$nom_contact = $_POST['nom_contact'];
	}
	if( !empty($_POST['date']) ){
		$date = $_POST['date'];
		//Verification format de la date
		/*if( !verifierDate($date) ){
			return 'format date non valide';
		}*/
	}
	if( !empty($_POST['heureDebut']) ){
		$heureDeb = $_POST['heureDebut'];
		//Verification format horaire
		/*if( !verifierHoraire($heureDebut) ){
			return 'format horaire debut';
		}*/
	}
	if( !empty($_POST['heureFin']) ){
		$heureFin = $_POST['heureFin'];
		//Verification format horaire
		/*if( !verifierHoraire($heureFin) ){
			return 'format horaire fin';
		}*/
	}
	
	//TODO: attention s'il y a plusieurs espaces !!!
	//On regarde si le contact existe deja
	$temp = explode(" ", $nom_contact);
	$nom = $temp[1];
	$prenom = $temp[0];
	echo $prenom;
	echo $nom;
	$retour = Contact::GetContactParNom($nom,$prenom);
	if( $retour != null){
		// On recupere l'id correspondant
		$_id_contact = $retour->getId();
	}else{
		die;
	}
	
	$_date = $date;
	$_etat = 0; // Par defaut l'etat sera 0 donc a valider !!
	// On enregistre l'entretien
	$id_entretien = Entretien::UpdateEntretien(0, $_id_contact, $_date, $_etat);
	
	//On cr?er les cr?neaux associ?s
	if( $id_entretien > 0 ){
		// Analyser les heures debut et fin
		$temp = explode("h",$heureDeb);
		$heureDebut = (int) $temp[0];
		$minuteDebut = (int) $temp[1];
		
		$temp = explode("h",$heureFin);
		$heureFin = (int) $temp[0];
		$minuteFin = (int) $temp[1];

		while( true ){
			
			$_debut = $heureDebut."h".$minuteDebut;
			
			$heureDebut += floor(($minuteDebut + 45) / 60);
			$minuteDebut = ($minuteDebut + 45) % 60;
			$minuteTemp = ($minuteDebut > 9)? $minuteDebut : $minuteDebut."0";
			$_fin = $heureDebut."h".$minuteTemp;
			
			if( $heureDebut > $heureFin ){
				break;
			}else if( $heureDebut==$heureFin && $minuteDebut >= $minuteFin){
				break;
			}else if( $heureDebut==$heureFin && $minuteDebut >= $minuteFin + 45){
				break;
			}else{
				// Les valeurs sont bonnes on creer un nouveau creneau
				Creneau::UpdateCreneau(0, $id_entretien, $_debut, $_fin, 0);
			}
		}
	}else{
		echo 'erreur d\'insertion en base de donnees';
	}
	
	
?>
