<?php
/**
 * -----------------------------------------------------------
 * INFOENTREPRISE - CIBLE PHP
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Contact - benjamin.planche@aldream.net
 * ---------------------
 * Cible pour la r�cup�ration d'informations sur une entreprise.
 * Est donc appel�e par le moteur JS (Ajax) de la page Annuaire quand une entreprise est s�lectionn�e dans la liste des noms.
 * Le principe (repris de Bnj Bouv) est tr�s simple :
 * 1) On r�cup�re l'ensemble des variables qui ont �t� ins�r�es.
 * 2) On appelle le contr�leur 
 * 3) On renvoit les r�sultats en JSON
 * Le r�sultat sera de la forme :
 		{
			code : "ok", // ou "error" - si error, le champ entreprise n'est pas pr�sent
			entreprise : {
				description: {
					nom: "Atos",
					description: "Soci�t� fran�aise recrutant des tonnes de 4IF.",
					secteur: "SSII",
					commentaire: "",
				},
				contacts: [
					{nom: "Chuck", prenom: "Noris", metier: "Dieu", email:"chuck@atos.com", tel:"06666666666", priorite:1, commentaire:""},
					{nom: "Chucky", prenom: "Norissette", metier: "D�esse", email:"chuckky@atos.com", tel:"06666666667", priorite:0, commentaire:"A v�rifier"}
				],
				relation: {
					parrainage : [
						{annee: 2012, commentaire:"Ok", couleur:1},
						{annee: 2011, commentaire:"Bof", couleur:0}
					],
					rif : [
						{annee: 2012, commentaire:"Ok", couleur:1},
						{annee: 2011, commentaire:"Retard Paiement", couleur:0}
					],
					stages: [
						{annee: 2012, nbSujets:12},
						{annee: 2011, nbSujets:5}
					],
					entretiens: [
						{annee: 2012, nbSessions:3},
						{annee: 2011, nbSessions:1}
					]
				},
				commentaires: [
					{nom: "Le Roux", prenom: "Bill", poste: "SG", date:1332615354000 , categorie:0, commentaire:"A contacter pour un parteneriat"},
					{nom: "B", prenom: "Dan", poste: "Eq En", date:1332215354000, categorie:3, commentaire:"A contacter pour un calin"}
				]
			}
		}
 */
 
 
 // V�rification de l'authentification :
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('commun', 'authentification.class', 'php');
$authentification = new Authentification();
$utilisateur = null;
if ($authentification->isAuthentifie()) {

    /* On r�cup�re l'objet utilisateur associ� */
    $utilisateur = $authentification->getUtilisateur();
    if (($utilisateur == null) || (($utilisateur->getPersonne()->getRole() != Personne::AEDI) && ($utilisateur->getPersonne()->getRole() != Personne::ADMIN))) {
        $authentification->forcerDeconnexion();
		inclure_fichier('', '401', 'php');
		die;
    }
}

require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('controleur', 'entreprise.class', 'php');
inclure_fichier('controleur', 'contact.class', 'php');
inclure_fichier('controleur', 'commentaire_entreprise.class', 'php');

/*
 * R�cup�rer et transformer le JSON
 */
/* int */ $id_entreprise = NULL;

if (verifierPresent('id')) {
	$id_entreprise = intval($_POST['id']);
	
	/*
	 * Appeler la couche du dessous
	 */
	/* objet */ $entreprise = Entreprise::GetEntrepriseByID($id_entreprise);
	if (gettype($entreprise) == "int" && $entreprise == Entreprise::getErreurExecRequete()) {
		$json['code'] = 'errorBDD';
	}
	else {
		/* objet */ $contacts = NULL;
		/* objet */ $commentaires = NULL;
		if ($entreprise != NULL) {
			$contacts = Contact::GetListeContactsParEntreprise($id_entreprise);
			$commentaires = CommentaireEntreprise::GetListeCommentairesParEntreprise($id_entreprise);

			$json['entreprise']['description'] = $entreprise->toArrayObject();
			if (gettype($contacts) == 'array') {
				$json['entreprise']['contacts'] = Array();
				foreach( $contacts as $contact ) {
					array_push($json['entreprise']['contacts'], $contact->toArrayObject(false, true, true, true, false, false, false));
				}
			}
			if (gettype($commentaires) == 'array') {
				$json['entreprise']['commentaires'] = Array();
				foreach( $commentaires as $commentaire ) {
					array_push($json['entreprise']['commentaires'], $commentaire->toArrayObject(false, false, false, true, false, true));
				}
			}
			
			$json['code'] = 'ok';
		}
		else {
			$json['code'] = 'noEntr';
		}
	}
}
else {
	$json['code'] = 'errorChamp';
}



/*
 * Renvoyer le JSON
 */
echo json_encode($json);


?>
