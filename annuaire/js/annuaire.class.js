/**
 * -----------------------------------------------------------
 * ANNUAIRE - CLASSE JS
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 * 			Contact - benjamin.planche@aldream.net
 * ---------------------
 * Assure les principales fonctionnalités / le moteur de la page Annuaire des entreprises. Elle gère notamment :
 * - le chargement dynamique des informations sur une entreprise sélectionnée (AJAX)
 * - la mise en page et l'affichage de ces informations
 */

// Définition de l'objet - (Permet une encapsulation des fonctions ... Autant pourrir le moins possible l'espace de nom)
var Annuaire = {};

// ------------------------ ATTRIBUTS ------------------------ //

// Booléen définissant si l'utilisateur a le droit ou non d'apporter des modifications à l'annuaire (influence l'affichage -> ajout des boutons de modification ; n'empêche pas d'effectuer des contrôles côté serveur !)
Annuaire.droitModification = false;

// Objet contenant les données sur l'entreprise en cours de visualisation
Annuaire.infoEntrepriseCourante = {};

// Array contenant la liste des noms d'entreprise par ID
Annuaire.listeEntreprises = [];

// Array contenant la liste des secteurs déja entrés, servant ainsi pour l'autocomplétion.
Annuaire.listeSecteurs = [];

// Array contenant la liste des postes déja entrés, servant ainsi pour l'autocomplétion.
Annuaire.listePostes = [];

// Info sur l'utilisateur :
Annuaire.utilisateur = {};

// Templates HTML (Handlebars) :
Annuaire.templates = {};

// Fonction de tri des tables :
Annuaire.tableSorters = {};

// -------------------------- CONSTANTES -------------------------- //
Annuaire.Erreurs = {
	AJAX_INVALIDE : 'erreurRequete',
	SQL_INVALIDE : 'errorBDD',
	CHAMP_INVALIDE : 'erreurChamp'
};

Annuaire.Colors = {
	SEARCH_RESULTS_BACKGROUND : '#BED2FF'
};

// ------------------------ REQUETAGE AJAX ------------------------ //

/** 
 * ---- chercherInfoEntreprise
 * Interroge le serveur pour récupérer l'ensemble des informations sur une entreprise (description, contacts, relationss, remarques) - Requétage Ajax
 * Paramètres :
 *		- idEntreprise : INT - Identifiant de l'entreprise voulue
 * Retour :
 *		- OBJET - Informations sur l'entreprise ou message d'erreur
 *		Structure/Exemple de réponse :
			{
				description: {
					nom: "Atos",
					description: "Société française recrutant des tonnes de 4IF.",
					secteur: "SSII",
					commentaire: "",
				},
				contacts: [
					{id: 1, nom: "Chuck", prenom: "Noris", metier: "Dieu", email:"chuck@atos.com", tel:"06666666666", priorite:1, commentaire:""},
					{id: 2, nom: "Chucky", prenom: "Norissette", metier: "Déesse", email:"chuckky@atos.com", tel:"06666666667", priorite:0, commentaire:"A vérifier"}
				],
				relationss: {
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
 */
Annuaire.chercherInfoEntreprise = function chercherInfoEntreprise(/* int */ idEntreprise, /* void function(void) */ callback ) {
	// Requête Ajax :
	var /* objet */ requete = $.ajax({
		url: "./annuaire/ajax/infoEntreprise.cible.php",
		type: "POST",
		data: {id : idEntreprise},
		dataType: "json"
	});

	requete.done(function(donnees) {
		callback(donnees);
	});
	requete.fail(function(jqXHR, textStatus) {
		Annuaire.afficherErreur( "Une erreur est survenue lors de l'envoi de la requête au serveur : " + textStatus );
	});

};

/** 
 * ---- chercherContacts
 * Interroge le serveur pour récupérer l'ensemble des contacts correspondant au mots-clés donnés - Requétage Ajax
 * Paramètres :
 *		- RIEN
 * Retour :
 *		- OBJET - Informations sur les contacts organisés par entreprise
 *		Structure/Exemple de l'objet réponse :
			[
				{
					nom: "Atos",
					id : 1,
					contacts: [
						{id: 1, nom: "Chuck", prenom: "Noris", metier: "Dieu", email:"chuck@atos.com", tel:"06666666666", priorite:1, commentaire:""},
						{id: 2, nom: "Chucky", prenom: "Norissette", metier: "Déesse", email:"chuckky@atos.com", tel:"06666666667", priorite:0, commentaire:"A vérifier"}
				]},
				{
					nom: "Fiducial",
					id : 2,
					contacts: [
						{id: 1, nom: "Chuck", prenom: "Noris", metier: "Dieu", email:"chuck@atos.com", tel:"06666666666", priorite:1, commentaire:""},
						{id: 2, nom: "Chucky", prenom: "Norissette", metier: "Déesse", email:"chuckky@atos.com", tel:"06666666667", priorite:0, commentaire:"A vérifier"}
				]},
				
			]
 */
Annuaire.chercherContacts = function chercherContacts() {
	// Vérification du formulaire :
	var /* string */ unprocessedKeywords = $('#formSearchContactKeywords').val();
	if (unprocessedKeywords == null || unprocessedKeywords == '') { Annuaire.afficherEmail('Veuillez entrer des mots-clés pour votre recherche.'); return; }
	
	// Traitement des keywords :
	// -- Etape 1) Séparation des couples et/ou mots-clés seuls.	ex : "entr:Atos nom:Black, postier Lyon" , Paris" devient ["entr: Atos", "nom:Black", "postier", Lyon"]
	var splittedKeywords = unprocessedKeywords.split(/[\s,;]+/);
	
	// -- Etape 2) Isolation du champs et de la valeur dans les couples.	ex : ["entr:Atos", "nom:Black", "postier", Lyon"] devient [["entr", Atos"], ["nom", "Black"], [null, "postier"], [null, "Lyon"]]
	var processedKeywords = new Array();
	for (var k in splittedKeywords) {
		var splittedCouple = splittedKeywords[k].split(/:/);
		if ( splittedCouple.length == 2) {
			processedKeywords.push({'champ':splittedCouple[0], 'val':splittedCouple[1]});
		}
		else {
			processedKeywords.push({'champ':'*', 'val':splittedCouple[0]});
		}
	}
	
	// Requête Ajax :
	var /* objet */ requete = $.ajax({
		url: "./annuaire/ajax/searchContact.cible.php",
		type: "POST",
		data: {keywords : processedKeywords},
		dataType: "json"
	});

	requete.done(function(donnees) {
		Annuaire.afficherResultatRechercheContacts(donnees, processedKeywords);
	});
	requete.fail(function(jqXHR, textStatus) {
		Annuaire.afficherErreur( "Une erreur est survenue lors de l'envoi de la requête au serveur : " + textStatus );
	});

};

/**
 * Suppression d'une entreprise de la base de données.
 * id_entreprise : L'id de l'entreprise à supprimer
 */
Annuaire.supprimerEntreprise = function supprimerEntreprise(id_entreprise) {
	$.ajax( {
		url: "./annuaire/ajax/supprEntreprise.cible.php",
		type: "POST",
		async: false,
		data: { "id_entreprise" : id_entreprise },
		dataType: "json",
		success : function(resp) {
			if( resp.code == 'ok' ) {
				window.location.reload();
			}
			else {
				Annuaire.afficherErreur( resp.mesg );
			}
		},
		error :  function(jqXHR, textStatus) {
			Annuaire.afficherErreur( "Une erreur est survenue lors de l'envoi de la requête au serveur : " + textStatus );
		}
	} );
};

/** 
 * ---- updaterEntreprise
 * Valide le formulaire d'ajout/modification d'une entreprise & transmet les informations à la cible PHP.
 * Paramètres :
 *		- RIEN
 * Retour :
 *		- RIEN (Page directement modifiée)
 */
Annuaire.updaterEntreprise = function updaterEntreprise() {
	// Vérification du formulaire :
	var /* string */ nomEntr = $('#formUpdateEntrepriseNom').val();
		
	/* int */ var idEntrepriseActuelle = -1
	if ($('#formUpdateEntrepriseId').val() != "0") {
		idEntrepriseActuelle = Annuaire.infoEntrepriseCourante.description.id_entreprise;
	}
	
	// Envoi :
	var description = {id_entreprise: parseInt($('#formUpdateEntrepriseId').val()), nom : encodeURIComponent(nomEntr), secteur: encodeURIComponent($('#formUpdateEntrepriseSecteur').val()), description: encodeURIComponent($('#formUpdateEntrepriseDescription').val())};
	var /* objet */ requete = $.ajax({
		url: "./annuaire/ajax/updateEntreprise.cible.php",
		type: "POST",
		data: description,
		dataType: "json"
	});
	
	// Ajout du secteur à la liste si nouveau.
	if ($.inArray($('#formUpdateEntrepriseSecteur').val(), Annuaire.listeSecteurs) == -1) {
		Annuaire.listeSecteurs.push($('#formUpdateEntrepriseSecteur').val());
		$('#formUpdateEntrepriseSecteur').typeahead({
			source: Annuaire.listeSecteurs
		});
	}
	
	// RAZ du form :
	$('#modalUpdateEntreprise').modal('hide');
	resetForm($('#formUpdateEntreprise'));
	$('#formUpdateEntrepriseDescription').val('');
	$('#formUpdateEntrepriseId').val(0);

	requete.done(function(donnees) {
		if (donnees.code == "ok") {
			if (donnees.id > 0) { // Ajout d'une entreprise :
				Annuaire.insererEntrepriseDansListe({id_entreprise: donnees.id, nom: nomEntr});
				Annuaire.afficherListeEntreprises();
				description.nom = decodeURIComponent(description.nom );
				description.secteur = decodeURIComponent(description.secteur );
				description.description = decodeURIComponent(description.description );
				container = {'description' : description};
				Annuaire.infoEntrepriseCourante = container;
				var objSimulantReponseServeur = { entreprise : Annuaire.infoEntrepriseCourante};
				Annuaire.afficherInfoEntreprise(objSimulantReponseServeur);
				// On demande si l'utilisateur veut ajouter tout de suite des contacts :
				Annuaire.confirmerAction('Entreprise ajoutée !<br/> Voulez-vous ajouter des contacts tout de suite ?', 'alert-success', function(id) {
					$('#formUpdateContactEntrepriseId').val(id);
					$('#modalUpdateContact').modal('show');
				}, donnees.id);
			}
			else if (donnees.id == 0) { // Edition d'une entreprise :
				Annuaire.confirmerAction('Entreprise éditée !<br/> Voulez-vous également ajouter de nouveaux contacts ?', 'alert-success', function(id) {
					$('#formUpdateContactEntrepriseId').val(id);
					$('#modalUpdateContact').modal('show');
				}, Annuaire.infoEntrepriseCourante.description.id_entreprise);
				
				if (idEntrepriseActuelle == Annuaire.infoEntrepriseCourante.description.id_entreprise) {
					description.nom = decodeURIComponent(description.nom );
					description.secteur = decodeURIComponent(description.secteur );
					description.description = decodeURIComponent(description.description );
					Annuaire.infoEntrepriseCourante.description = description;
					var objSimulantReponseServeur = { entreprise : Annuaire.infoEntrepriseCourante};
					Annuaire.afficherInfoEntreprise(objSimulantReponseServeur);
				}
				// Si MAJ du nom, ca met à jour la liste ...
				Annuaire.retirerEntrepriseDeListe(description.id_entreprise);
				Annuaire.insererEntrepriseDansListe({id_entreprise: description.id_entreprise, nom: nomEntr});
				Annuaire.afficherListeEntreprises(); // Si MAJ du nom, ca met à jour la liste ...
			}
			else {
				Annuaire.afficherErreur('Une erreur est survenue (id = '+donnees.id+')' );
			}
		}
		else {
			Annuaire.afficherErreur( donnees.mesg );
		}
	});
	requete.fail(function(jqXHR, textStatus) {
		Annuaire.afficherErreur( "Une erreur est survenue lors de l'envoi de la requête au serveur : " + textStatus );
	});
};

/** 
 * ---- updaterContact
 * Valide le formulaire d'ajout/modification d'un contact & transmet les informations à la cible PHP.
 * Paramètres :
 *		- RIEN
 * Retour :
 *		- RIEN (Page directement modifiée)
 */
Annuaire.updaterContact = function updaterContact() {

	var idEntrepriseActuelle = Annuaire.infoEntrepriseCourante.description.id_entreprise;
	
	// Récupération de données complexes :
	var /* array */ tels = [];
	$('#formUpdateContactTelGroup ul').children().each(function(){
		tels.push([$(this).find('.labelVal').attr('title'), $(this).find('.val').text()]);
	});
	if ($('#formUpdateContactTel').val() != '') { tels.push([encodeURIComponent($('#formUpdateContactTelLabel option:selected').val()), encodeURIComponent($('#formUpdateContactTel').val())]); }
	
	var /* array */ emails = [];
	$('#formUpdateContactEmailGroup ul').children().each(function(){
		emails.push([$(this).find('.labelVal').attr('title'), $(this).find('.val').text()]);
	});
	if ($('#formUpdateContactEmail').val() != '') { emails.push([encodeURIComponent($('#formUpdateContactEmailLabel option:selected').val()), encodeURIComponent($('#formUpdateContactEmail').val())]); }
	
	// Envoi :
	var /* objet */ nouveauContact = {
		id_contact: parseInt($('#formUpdateContactId').val()),
		id_entreprise: parseInt($('#formUpdateContactEntrepriseId').val()),
		fonction : encodeURIComponent($('#formUpdateContactPoste').val()),
		personne : {
			id : parseInt($('#formUpdateContactPersonneId').val()),
			nom : encodeURIComponent($('#formUpdateContactNom').val()),
			prenom : encodeURIComponent($('#formUpdateContactPrenom').val()),
			mails : emails,
			telephones : tels
		},
		ville : {
			code_postal : encodeURIComponent($('#formUpdateContactVilleCodePostal').val()),
			libelle : encodeURIComponent($('#formUpdateContactVilleLibelle').val()),
			pays : encodeURIComponent($('#formUpdateContactVillePays').val())
		},
		commentaire : encodeURIComponent($('#formUpdateContactCom').val()),
		priorite : parseInt($('#formUpdateContactPriorite').val())
	};
	var /* objet */ requete = $.ajax({
		url: "./annuaire/ajax/updateContact.cible.php",
		type: "POST",
		data: nouveauContact,
		dataType: "json"
	});
	
	// Ajout du poste à la liste si nouveau.
	if ($.inArray($('#formUpdateContactPoste').val(), Annuaire.listePostes) == -1) {
		Annuaire.listePostes.push($('#formUpdateContactPoste').val());
		$('#formUpdateContactPoste').typeahead({
			source: Annuaire.listePostes
		});
	}
	
	$('#modalUpdateContact').modal('hide');
	Annuaire.resetFormContact();

	requete.done(function(donnees) {
		if (donnees.code == "ok") {
			var idNouvContact = parseInt(donnees.id);
			if ((idNouvContact >= 0) && (idEntrepriseActuelle == Annuaire.infoEntrepriseCourante.description.id_entreprise)) { // Si l'utilisateur est toujours sur la même entreprise, on met à jour son affichage :
			
				nouveauContact.personne.id = donnees.id_personne;
				if (typeof Annuaire.infoEntrepriseCourante.contacts === "undefined") { Annuaire.infoEntrepriseCourante.contacts = []; }					
				nouveauContact.fonction = decodeURIComponent(nouveauContact.fonction);
				nouveauContact.personne.nom = decodeURIComponent(nouveauContact.personne.nom);
				nouveauContact.personne.prenom = decodeURIComponent(nouveauContact.personne.prenom);
				for (var i in nouveauContact.personne.mails) {
					nouveauContact.personne.mails[i][0] = decodeURIComponent(nouveauContact.personne.mails[i][0]);
					nouveauContact.personne.mails[i][1] = decodeURIComponent(nouveauContact.personne.mails[i][1]);
				}
				for (var i in nouveauContact.personne.telephones) {
					nouveauContact.personne.telephones[i][0] = decodeURIComponent(nouveauContact.personne.telephones[i][0]);
					nouveauContact.personne.telephones[i][1] = decodeURIComponent(nouveauContact.personne.telephones[i][1]);
				}
				nouveauContact.ville.code_postal = decodeURIComponent(nouveauContact.ville.code_postal);
				nouveauContact.ville.libelle = decodeURIComponent(nouveauContact.ville.libelle);
				nouveauContact.ville.pays = decodeURIComponent(nouveauContact.ville.pays);
				nouveauContact.commentaire = decodeURIComponent(nouveauContact.commentaire);

				// On met à jour l'ancien contact ou ajoute le nouveau :
				if (idNouvContact == 0) {
					for (var i in Annuaire.infoEntrepriseCourante.contacts) {
						if (Annuaire.infoEntrepriseCourante.contacts[i].id_contact == nouveauContact.id_contact) {
							Annuaire.infoEntrepriseCourante.contacts[i] = nouveauContact;
							break;
						}
					}
					
				}
				else {
					nouveauContact.id_contact = donnees.id;
					Annuaire.infoEntrepriseCourante.contacts.push(nouveauContact);
				}

				var objSimulantReponseServeur = { entreprise : Annuaire.infoEntrepriseCourante};
				Annuaire.afficherInfoEntreprise(objSimulantReponseServeur);
			}
			
			if (donnees.id > 0) { // Ajout d'un contact :
				// On demande si l'utilisateur veut en ajouter tout de suite d'autres :
				Annuaire.confirmerAction('Contact ajouté !<br/> Voulez-vous en ajouter d\'autres tout de suite ?', 'alert-success', function(id) {
					$('#formUpdateContactEntrepriseId').val(id);
					$('#modalUpdateContact').modal('show');
				}, donnees.id);
			}
			else if (donnees.id == 0) { // Edition d'un contact :
			
			}
			else {
				Annuaire.afficherErreur('Contact - Une erreur est survenue (id = '+donnees.id+')' );
			}
		}
		else {
			Annuaire.afficherErreur( donnees.mesg );
		}
	});
	requete.fail(function(jqXHR, textStatus) {
		Annuaire.afficherErreur( "Une erreur est survenue lors de l'envoi de la requête au serveur : " + textStatus );
	});
};

/** 
 * ---- ajouterCommentaire
 * Valide le formulaire d'ajout d'un commentaire & transmet les informations à la cible PHP.
 * Paramètres :
 *		- RIEN
 * Retour :
 *		- RIEN (Page directement modifiée)
 */
Annuaire.ajouterCommentaire = function ajouterCommentaire() {

	/* int */ var idEntrepriseActuelle = Annuaire.infoEntrepriseCourante.description.id_entreprise;
	
	// Envoi :
	var categorie = $('#formAjoutCommentaire .formAjoutCommentaireCateg:checked');
	var /* objet */ nouveauCommentaire = {
		'id_entreprise': idEntrepriseActuelle,
		'contenu' : encodeURIComponent($('#formAjoutCommentaireContenu').val()),
		'categorie' : parseInt(categorie.val())
	};
	var /* objet */ requete = $.ajax({
		url: "./annuaire/ajax/ajoutCommentaire.cible.php",
		type: "POST",
		data: nouveauCommentaire,
		dataType: "json"
	});
	
	// RAZ du form :
	$('#modalAjoutCommentaire').modal('hide');
	resetForm($('#formAjoutCommentaire'));
	$('#formAjoutCommentaireContenu').val('');
	$('#formAjoutCommentaireCategorie1').attr('checked', true);

	requete.done(function(donnees) {
		if (donnees.code == "ok") {
			if (donnees.id >= 0) {
				if (idEntrepriseActuelle == Annuaire.infoEntrepriseCourante.description.id_entreprise) { // Si l'utilisateur est toujours sur la même entreprise, on met à jour son affichage :
					nouveauCommentaire.id_commentaire = donnees.id;
					if (typeof Annuaire.infoEntrepriseCourante.commentaires === "undefined") { Annuaire.infoEntrepriseCourante.commentaires = []; }
					nouveauCommentaire.contenu = decodeURIComponent(nouveauCommentaire.contenu);
					nouveauCommentaire.personne = Annuaire.utilisateur.personne;
					nouveauCommentaire.timestamp = new Date();
					nouveauCommentaire.timestamp = nouveauCommentaire.timestamp.format('yyyy-mm-dd hh:mm:ss');
					Annuaire.infoEntrepriseCourante.commentaires.push(nouveauCommentaire);
					var objSimulantReponseServeur = { entreprise : Annuaire.infoEntrepriseCourante};
					Annuaire.afficherInfoEntreprise(objSimulantReponseServeur);
					$('#contacts').collapse('hide');
					$('#remarques').collapse('show');
				}
			}
			else {
				Annuaire.afficherErreur('Commentaire : Une erreur est survenue (id = '+donnees.id+')' );
			}
		}
		else {
			Annuaire.afficherErreur( donnees.mesg );
		}
	});
	requete.fail(function(jqXHR, textStatus) {
		Annuaire.afficherErreur( "Une erreur est survenue lors de l'envoi de la requête au serveur : " + textStatus );
	});

};

/** 
 * ---- supprimerContact
 * Supprime un contact
 * Paramètres :
 *		- id - INT : ID du contact à supprimer
 * Retour :
 *		- RIEN (Page directement modifiée)
 */
Annuaire.supprimerContact = function supprimerContact(id) {
	/* int */ var idEntrepriseActuelle = Annuaire.infoEntrepriseCourante.description.id_entreprise;
	
	// Envoi :
	var /* objet */ requete = $.ajax({
		url: "./annuaire/ajax/supprContact.cible.php",
		type: "POST",
		data: { id: parseInt(id) },
		dataType: "json"
	});

	requete.done(function(donnees) {
		if (donnees.code == "ok") {
			if (idEntrepriseActuelle == Annuaire.infoEntrepriseCourante.description.id_entreprise) { // Si l'utilisateur est toujours sur la même entreprise, on met à jour son affichage :
				for (var i in Annuaire.infoEntrepriseCourante.contacts) {
					if (Annuaire.infoEntrepriseCourante.contacts[i].id_contact == id) {
						Annuaire.infoEntrepriseCourante.contacts.splice(i,1);
						var objSimulantReponseServeur = { entreprise : Annuaire.infoEntrepriseCourante};
						Annuaire.afficherInfoEntreprise(objSimulantReponseServeur);
						break;
					}
				}
			}
		}
		else {
			Annuaire.afficherErreur( donnees.mesg );
		}
	});
	requete.fail(function(jqXHR, textStatus) {
		Annuaire.afficherErreur( "Une erreur est survenue lors de l'envoi de la requête au serveur : " + textStatus );
	});

};

/** 
 * ---- supprimerCommentaire
 * Supprime un comm'
 * Paramètres :
 *		- id - INT : ID du com' à supprimer
 * Retour :
 *		- RIEN (Page directement modifiée)
 */
Annuaire.supprimerCommentaire = function supprimerCommentaire(id) {
	/* int */ var idEntrepriseActuelle = Annuaire.infoEntrepriseCourante.description.id_entreprise;
	
	// Envoi :
	var /* objet */ requete = $.ajax({
		url: "./annuaire/ajax/supprCommentaire.cible.php",
		type: "POST",
		data: { id: parseInt(id) },
		dataType: "json"
	});

	requete.done(function(donnees) {
		if (donnees.code == "ok") {
			if (idEntrepriseActuelle == Annuaire.infoEntrepriseCourante.description.id_entreprise) { // Si l'utilisateur est toujours sur la même entreprise, on met à jour son affichage :
				for (var i in Annuaire.infoEntrepriseCourante.commentaires) {
					if (Annuaire.infoEntrepriseCourante.commentaires[i].id_commentaire == id) {
						Annuaire.infoEntrepriseCourante.commentaires.splice(i,1);
						var objSimulantReponseServeur = { entreprise : Annuaire.infoEntrepriseCourante};
						Annuaire.afficherInfoEntreprise(objSimulantReponseServeur);
						$('#contacts').collapse('hide');
						$('#remarques').collapse('show');
						break;
					}
				}
			}
		}
		else {
			Annuaire.afficherErreur( donnees.mesg );
		}
	});
	requete.fail(function(jqXHR, textStatus) {
		Annuaire.afficherErreur( "Une erreur est survenue lors de l'envoi de la requête au serveur : " + textStatus );
	});

};

// ------------------------ COHESION DE LA PAGE ------------------------ //

/** 
 * ---- insererEntrepriseDansListe
 * Ajoute & Affiche une entreprise dans la liste des noms.
 * Paramètres :
 *		- entreprise : OBJET- ID + nom de l'entreprise
 * Retour :
 *		- RIEN (Page directement modifiée)
 */
Annuaire.insererEntrepriseDansListe = function insererEntrepriseDansListe(/* objet JQUERY */ entreprise) {
	var insertOk = false;
	for (var /* int */ i = 1; i < Annuaire.listeEntreprises.length; i++) {
		if ((entreprise.nom >= Annuaire.listeEntreprises[i-1][1]) && (entreprise.nom < Annuaire.listeEntreprises[i][1])) {
			Annuaire.listeEntreprises.splice(i, 0, [parseInt(entreprise.id_entreprise), entreprise.nom]);
			insertOk = true;
			break;
		}
	}
	if (!insertOk) { Annuaire.listeEntreprises.push([entreprise.id_entreprise, entreprise.nom]); }
}

/** 
 * ---- retirerEntrepriseDeListe
 * Supprime une entreprise de la liste des noms.
 * Paramètres :
 *		- entreprise : INT - ID de l'entreprise
 * Retour :
 *		- RIEN (Page directement modifiée)
 */
Annuaire.retirerEntrepriseDeListe = function retirerEntrepriseDeListe(/* objet JQUERY */ entreprise) {
	for (var /* int */ i = 0; i < Annuaire.listeEntreprises.length; i++) {
		if (entreprise == Annuaire.listeEntreprises[i][0]) {
			Annuaire.listeEntreprises.splice(i, 1);
			break;
		}
	}
}

/** 
 * ---- activerBoutonSuppression
 * Paramètres :
 * Active le bouton de suppression si au moins une checkbox de suppression est cochée.
 *		- checkbox : JQUERY ELEMENT - checkbox cliquée
 * Retour :
 *		- RIEN (Page directement modifiée)
 */
Annuaire.nbChecboxCochees = 0;
Annuaire.activerBoutonSuppression = function activerBoutonSuppression(/* objet JQUERY */ checkbox) {
	if (checkbox.target.checked) {
		if (Annuaire.nbChecboxCochees==0) { // C'est la première cochée :
			$('#boutonSupprEntreprise').removeClass('disabled'); // On active le bouton
		}
		Annuaire.nbChecboxCochees++;
	} else {
		if (Annuaire.nbChecboxCochees==1) { // C'était la derniere cochée :
			$('#boutonSupprEntreprise').addClass('disabled'); // On désactive le bouton
		}
		Annuaire.nbChecboxCochees--;
	}
};

/** 
 * ---- resetFormContact
 * Paramètres :
 * Reset le formulaire d'ajout/modification de contact.
 *		- RIEN
 * Retour :
 *		- RIEN (Page directement modifiée)
 */
Annuaire.resetFormContact = function resetFormContact() {
	$('#formUpdateContactTelGroup ul').children().remove();
	$('#formUpdateContactEmailGroup ul').children().remove();
	resetForm($('#formUpdateContact'));
	$('#formUpdateContactPriorite').find('option[value="Normale"]').attr('selected', 'selected');
	$('#formUpdateContactId').val(0);
	$('#formUpdateContactEntrepriseId').val(0);
	$('#formUpdateContactPersonneId').val(0);
	$('#formUpdateContactPrioriteDefaut').attr('selected', true);
}

/** 
 * ---- activerBoutonAjoutEntree
 * Utilisée pour le formulaire d'ajout ou d'édition de contact.
 * Active ou désactive le bouton d'ajout pour entrer une donnée supplémentaire (tel ou email)
 * Paramètres :
 *		- event : OBJET EVENT - Evénement généré par la modifiation des champs de saisie obligatoires liés à ce bouton
 *		- idBouton : STRING - ID du bouton d'ajout
 *		- labelNeutre : STRING ou INT - Valeur du label neutre pour l'élément option
 *		- validateur : TO DO - Regex ou autre pour vérifier la validité des données entrées.
 * Retour :
 *		- RIEN - Page directement changée
 */
Annuaire.activerBoutonAjoutEntree = function activerBoutonAjoutEntree(event, idBouton, labelNeutre, validateur) {
	// Si on a une entrée valide, on autorise l'ajout.
	// TO DO - METTRE EN PLACE UNE VRAIE REGLE DE CONTROLE
	var input = $('#'+event.target.id);
	if (input.val() != validateur) { // SI Validée (ok, if pas logique, mais TO DO)
		if (!activerBoutonAjoutEntree.boutonActif[idBouton]) {
			$('#'+idBouton).removeClass('disabled');
			$('#'+idBouton).click(function(event) {Annuaire.ajouterEntreeListe(event, idBouton, labelNeutre);} );
			activerBoutonAjoutEntree.boutonActif[idBouton] = true;
		}
	} else {
		if (activerBoutonAjoutEntree.boutonActif[idBouton]) {
			$('#'+idBouton).addClass('disabled');
			$('#'+idBouton).unbind('click');
			activerBoutonAjoutEntree.boutonActif[idBouton] = false;
		}
	}
}
Annuaire.activerBoutonAjoutEntree.boutonActif = [];
	
/** 
 * ---- ajouterEntreeListe
 * Utilisée pour le formulaire d'ajout ou d'édition de contact.
 * Ajoute, suite à une demande, les champs nécessaires pour entrer une donnée supplémentaire (tel ou email)
 * Paramètres :
 *		- event : OBJET EVENT - Evénement généré par la demande
 *		- idBouton : STRING - ID du bouton d'ajout
 *		- labelNeutre : STRING ou INT - Valeur du label neutre pour l'élément option
 * Retour :
 *		- RIEN - Page directement changée
 */
Annuaire.ajouterEntreeListe = function ajouterEntreeListe(event, idBouton, labelNeutre) {
	var idAleatoire = new Date().getTime();
	if (event.target.children.length == 0)
		{ event.target = event.target.parentNode; } // On a cliqué sur le "+" et non sur le bouton, du coup on remonte au bouton.
	var inputGroupe = $('#'+event.target.id).parent().parent();

	// Ajout de la ligne informative :
	var num = inputGroupe.find('input[type="text"]').val();
	var label = inputGroupe.find('option:selected').val();
	inputGroupe.find('ul').append('<li><span class="val label label-info">'+num+'</span>&#09;&#09;'+Annuaire.afficherLibelle(label, 'labelVal')+'&#09;<a title="Supprimer" id="id'+idAleatoire+'" class="btn btn-danger btn-mini supprTel"><i class="icon-trash"></i></a></li>');
	inputGroupe.find('#id'+idAleatoire).click(function(event) {Annuaire.suppressionEntreeListe(event);});

	// RAZ des champs du tel :
	inputGroupe.find('input[type="text"]').val('');
	inputGroupe.find('option:selected').removeAttr("selected");
	inputGroupe.find('option[value="'+labelNeutre+'"]').attr('selected', 'selected');
	$('#'+idBouton).addClass('disabled');
	$('#'+idBouton).unbind('click');
	Annuaire.activerBoutonAjoutEntree.boutonActif[idBouton] = false;
	
	return false;
};


/** 
 * ---- suppressionEntreeListe
 * Utilisée pour le formulaire d'ajout ou d'édition de contact.
 * Supprime, suite à une demande, une des données supplémentaires (tel ou email)
 * Paramètres :
 *		- event : OBJET EVENT - Evénement généré par la demande
 * Retour :
 *		- RIEN - Page directement changée
 */
Annuaire.suppressionEntreeListe = function suppressionEntreeListe(event) {
	if (event.target.children.length == 0) { event.target = event.target.parentNode; } // On a cliqué sur l'icone et non sur le bouton, du coup on remonte au bouton.
	$('#'+event.target.id).parent().remove();
}

/** 
 * ---- confirmerAction
 * Demande à l'utilisateur de confirmer une action avant de l'effectuer
 * Paramètres :
 *		- enonceAction : STRING - Enoncé de l'action (ex: "Voulez-vous vraiment supprimer ce contact ?")
  *		- typeMessage : STRING - Bootstrap class définissant le type de message (alert, info, ...)
 *		- fonctionAction : void FUNCTION (NAWAK) - fonction à lancer si confirmation
 *		- paramFonction : NAWAK - Paramètre de la fonction
 * Retour :
 *		- RIEN
 */
Annuaire.confirmerAction = function confirmerAction(enonceAction, typeMessage, fonctionAction, paramFonction) {
	// RAZ de la popup :
	$('#btnModalConfirmer').unbind('click');
	$('#modalConfirmation .modal-body p').removeClass();
	// Création :
	$('#modalConfirmation .modal-body p').html(enonceAction);
	$('#modalConfirmation .modal-body p').addClass('alert');
	$('#modalConfirmation .modal-body p').addClass(typeMessage);
	$('#btnModalConfirmer').click( function() { fonctionAction(paramFonction); });
	$('#modalConfirmation').modal('show');
};

/** 
 * ---- afficherErreur
 * Affiche une erreur en fenetre modale
 * Paramètres :
 *		- erreur : STRING - Enoncé de l'erreur
 * Retour :
 *		- RIEN
 */
Annuaire.afficherErreur = function afficherErreur(erreur) {
	// Création :
	$('#modalErreur .modal-body p').html(erreur);
	$('#modalErreur').modal('show');
};

/** 
 * ---- preremplirFormulaireModifEntreprise
 * Préreplit le formulaire de modification d'une entreprise avec les infos déja acquises.
 * Paramètres :
 *		- event : OBJET EVENT - Evénement généré par la demande
 * Retour :
 *		- RIEN - Page directement changée
 */
Annuaire.preremplirFormulaireModifEntreprise = function preremplirFormulaireModifEntreprise(event) {
	if (typeof Annuaire.infoEntrepriseCourante.description !== "undefined") {
		$('#formUpdateEntrepriseId').val(Annuaire.infoEntrepriseCourante.description.id_entreprise);
		$('#formUpdateEntrepriseNom').val(Annuaire.infoEntrepriseCourante.description.nom);
		$('#formUpdateEntrepriseSecteur').val(Annuaire.infoEntrepriseCourante.description.secteur);
		$('#formUpdateEntrepriseDescription').val(Annuaire.infoEntrepriseCourante.description.description);
	}
	$('.type-action').text("Edition d'une entreprise");
};

/** 
 * ---- preremplirFormulaireUpdateContact
 * Préreplit le formulaire de modification d'un contact avec les infos déja acquises.
 * Paramètres :
 *		- event : OBJET EVENT - Evénement généré par la demande
 * Retour :
 *		- RIEN - Page directement changée
 */
Annuaire.preremplirFormulaireUpdateContactId = function preremplirFormulaireUpdateContactId() {
	$('#formUpdateContactEntrepriseId').val(Annuaire.infoEntrepriseCourante.description.id_entreprise);
}
Annuaire.preremplirFormulaireUpdateContact = function preremplirFormulaireUpdateContact(event) {
	if (event.target.children.length == 0)
		{ event.target = event.target.parentNode; } // On a cliqué sur l'icone et non sur le bouton, du coup on remonte au bouton.
	/* int */ var idContact = parseInt(event.target.getAttribute('id-contact'));
	if (typeof Annuaire.infoEntrepriseCourante.contacts !== "undefined") {
		/* objet */ var contact;
		for (var i in Annuaire.infoEntrepriseCourante.contacts) {
			if (Annuaire.infoEntrepriseCourante.contacts[i].id_contact == idContact) {
				contact = Annuaire.infoEntrepriseCourante.contacts[i];
				break;
			}
		}
	
		if (typeof contact !== "undefined") {
			$('#formUpdateContactNom').val(contact.personne.nom);
			$('#formUpdateContactPrenom').val(contact.personne.prenom);
			$('#formUpdateContactPoste').val(contact.fonction);
			
			if (typeof contact.ville !== "undefined") {
				$('#formUpdateContactVilleCodePostal').val(contact.ville.code_postal);
				$('#formUpdateContactVilleLibelle').val(contact.ville.libelle);
				$('#formUpdateContactVillePays').val(contact.ville.pays);
			}
			
			/* long */ var idAleatoire;
			for (/* int */ var i in contact.personne.telephones) {
				idAleatoire = new Date().getTime();
				$('#formUpdateContactTelGroup ul').append('<li><span class="val label label-info">'+contact.personne.telephones[i][1]+'</span>&#09;'+Annuaire.afficherLibelle(contact.personne.telephones[i][0], 'labelVal')+'&#09;<a title="Supprimer" id="id'+idAleatoire+'" class="btn btn-danger btn-mini supprTel"><i class="icon-trash"></i></a></li>');
				$('#formUpdateContactTelGroup ul').find('#id'+idAleatoire).click(function(event) {Annuaire.suppressionEntreeListe(event);});
			}
			
			for (/* int */ var i in contact.personne.mails) {
				idAleatoire = new Date().getTime();
				$('#formUpdateContactEmailGroup ul').append('<li><span class="val label label-info">'+contact.personne.mails[i][1]+'</span>&#09;'+Annuaire.afficherLibelle(contact.personne.mails[i][0], 'labelVal')+'&#09;<a title="Supprimer" id="id'+idAleatoire+'" class="btn btn-danger btn-mini supprTel"><i class="icon-trash"></i></a></li>');
				$('#formUpdateContactEmailGroup ul').find('#id'+idAleatoire).click(function(event) {Annuaire.suppressionEntreeListe(event);});
			}
			
			$('#formUpdateContactPriorite').find('option[value='+contact.priorite+']').attr('selected', 'selected');
			$('#formUpdateContactCom').val(contact.commentaire);
			
			$('#formUpdateContactId').val(idContact);
			$('#formUpdateContactPersonneId').val(contact.personne.id);
			Annuaire.preremplirFormulaireUpdateContactId();
		}
	}
};

/** 
 * ---- extraireContenuPourTriParRemarques
 * Retourne la donnée à utiliser pour TINY.table.sorter pour trier les contacts selon leur priorité et remarque
 * Paramètres :
 *		- noeud : Elément DOM utilisé normalement par TINY.table.sorter
 * Retour :
 *		- donnée à utiliser pour le tri
 */
Annuaire.extraireContenuPourTriParRemarques = function extraireContenuPourTriParRemarques(noeud) {
	return noeud.nextSibling.innerHTML + noeud.nextSibling.getAttribute('data-content');
}

/** 
 * ---- extraireContenuPourTriParBadge
 * Retourne la donnée à utiliser pour TINY.table.sorter pour trier les commentaires selon leur badge
 * Paramètres :
 *		- noeud : Elément DOM utilisé normalement par TINY.table.sorter
 * Retour :
 *		- donnée à utiliser pour le tri
 */
Annuaire.extraireContenuPourTriParBadge = function extraireContenuPourTriParBadge(noeud) {
	return noeud.nextSibling.innerHTML;
}

// ------------------------ AFFICHAGE ------------------------ //

/** 
 * ---- initialiserTemplates
 * Intialise le template qui servira à l'affichage des données Info Entreprise
 * Paramètres :
 *		- RIEN
 * Retour :
 *		- RIEN (Page maj)
 */
Annuaire.initialiserTemplates = function initialiserTemplates() {

	Handlebars.registerHelper('traduirePrioriteContactTexte', function (value)
		{ return new Handlebars.SafeString(Annuaire.traduirePrioriteContactTexte(value)); }
		);
	Handlebars.registerHelper('afficherLibelle', function (value) 
		{ return new Handlebars.SafeString(Annuaire.afficherLibelle(value, '')); }
		);
	Handlebars.registerHelper('traduireCategorieCommentaire', function (value) 
		{ 
			return new Handlebars.SafeString(Annuaire.traduireCategorieCommentaire(value));
		});
	Handlebars.registerHelper('traduirePrioriteContactTexte', function (value) 
		{ 
			return new Handlebars.SafeString(Annuaire.traduirePrioriteContactTexte(value));
		});
	Handlebars.registerHelper('traduireRole', function (value) 
		{ 
			return new Handlebars.SafeString(Annuaire.traduireRole(value));
		});
	Handlebars.registerHelper('traduireCouleur', function (value) 
		{ 
			return new Handlebars.SafeString(Annuaire.traduireCouleur(value));
		});
	Handlebars.registerHelper('afficherEmail', function (mail) 
		{ 
			return new Handlebars.SafeString('<table><tr><td><a href="mailto:'+mail[1]+'">'+mail[1]+'</a></td><td>'+Annuaire.afficherLibelle(mail[0], '')+'</td></tr></table>');
		});
	Handlebars.registerHelper('afficherTel', function (tel) 
		{ 
			return new Handlebars.SafeString('<table><tr><td>'+tel[1]+'</td><td>'+Annuaire.afficherLibelle(tel[0], '')+'</td></tr></table>');
		});

	Annuaire.templates['InfoEntreprise'] = Handlebars.compile($("#templateInfoEntreprise").html());
	Annuaire.templates['SearchContact'] = Handlebars.compile($("#templateSearchContact").html());
}


/** 
 * ---- afficherListeEntreprises
 * Affiche la liste des noms d'entreprise
 * Paramètres :
 *		- RIEN
 * Retour :
 *		- RIEN (Page maj)
 */
Annuaire.afficherListeEntreprises = function afficherListeEntreprises() {
	
	if( Annuaire.listeEntreprises.length > 0 ) {
		var /* char */ premiere_lettrePrec;
		for (var /* int */ i in Annuaire.listeEntreprises) {
			premiere_lettrePrec = Annuaire.listeEntreprises[i][1].charAt(0);
			break;
		}
		var /* char */ premiere_lettreSuiv = premiere_lettrePrec;
		var /* int */ compteur = 0;
		var /* string */ lignes = '';
		var /* string */ listeFinale = '';
		
		for (var /* int */ i in Annuaire.listeEntreprises) {
			premiere_lettreSuiv = Annuaire.listeEntreprises[i][1].charAt(0);
			if (premiere_lettrePrec != premiere_lettreSuiv) { // On passe à la lettre suivante dans l'alphabet :
				// On ajoute la colonne affichant la lettre, et on affiche le tout :
				lignes = '<tr><td  class="first" rowspan="'+compteur+'">'+premiere_lettrePrec+'</td>'+lignes;
				listeFinale += lignes;
				lignes = '';
				compteur = 0;
				premiere_lettrePrec = premiere_lettreSuiv;
			}
		
			// On génère les lignes :
			compteur++;
			if (lignes != '') {
				lignes += '<tr>';
			}
			lignes += '<td class="entreprise" id-entreprise='+Annuaire.listeEntreprises[i][0]+' ><a id-entreprise='+Annuaire.listeEntreprises[i][0]+' href="#'+Annuaire.listeEntreprises[i][1]+'">'+Annuaire.listeEntreprises[i][1]+'</a></td></tr>';
		}
		
		// On affiche le dernier contenu générer :
		listeFinale += '<tr><td  class="first" rowspan="'+compteur+'">'+premiere_lettrePrec+'</td>'+lignes;
		
		$('#listeEntreprises tbody').html(listeFinale);
	}
	
	// Pour chaque entreprise de la liste, on permet d'afficher leur détail par simple clic :
	$('.entreprise').click(function(event){Annuaire.chercherInfoEntreprise(parseInt(event.target.getAttribute('id-entreprise')), Annuaire.afficherInfoEntreprise)});
};
 
/** 
 * ---- traduirePrioriteContactTexte
 * Traduit textuellement une priorité numérique, selon la convention définie (voir code directement - explicite).
 * Paramètres :
 *		- priorite : INT - Valeur de la priorité à traduire
 * Retour :
 *		- STRING - Texte décrivant la priorité
 */
Annuaire.traduirePrioriteContactTexte = function traduirePrioriteContactTexte(/* int */ priorite) {
	if (priorite > 2) { return "Prioritaire" };
	if (priorite == 2) { return "Normale" };
	if (priorite == 1) { return "Faible" };
	if (priorite == 0) { return "Incertain" };
	if (priorite < 0) { return "A éviter" };
	return "?";
};

/** 
 * ---- traduireRole
 * Traduit textuellement un role numérique, selon la convention définie (voir code directement - explicite).
 * Paramètres :
 *		- role : INT - Valeur du role à traduire
 * Retour :
 *		- STRING - Texte décrivant la rôle
 */
Annuaire.traduireRole = function traduireRole(/* int */ role) {
	if (role == 0) { return 'Etudiant' };
	if (role == 1) { return "Enseignant" };
	if (role == 2) { return "Contact" };
	if (role == 3) { return "Admin" };
	if (role == 4) { return "AEDI" };
	return "?";
};

/** 
 * ---- traduireCouleur
 * Retourne, pour une valeur donnée, l'attribut bootstrap de coloration correspondant selon la convention définie (voir code directement - explicite).
 * Paramètres :
 *		- num : INT - Valeur à traduire
 * Retour :
 *		- STRING - Attribut bootstrap de coloration
 */
Annuaire.traduireCouleur = function traduireCouleur(/* int */ num) {
	if (num > 2) { return "success" };
	if (num == 2) { return "info" };
	if (num == 1) { return "warning" };
	if (num < 0) { return "alert" };
	return "";
};

/** 
 * ---- afficherLibelle
 * Met en page le libellé d'un mail ou tel selon la convention ci-dessous
 * Paramètres :
 *		- libelle : STRING - Libellé du mail
 *		- classesSup : STRING - classes supplémentaires à ajouter au span créé
 * Retour :
 *		- STRING - Libellé mis en page à l'aide de Bootstrap
 */
Annuaire.afficherLibelle = function afficherLibelle(/* string */ libelle, classesSup) {
	if (libelle == 'Pro') { return '<span title="Pro" class="label '+classesSup+'"><i class="icon-book"></i></span>' };
	if (libelle == 'Perso') { return '<span title="Perso" class="label '+classesSup+'"><i class="icon-home"></i></span>' };
	if (libelle == 'Bureau') { return '<span title="Bureau" class="label '+classesSup+'"><i class="icon-book"></i></span>' };
	if (libelle == 'Fixe') { return '<span title="Fixe" class="label '+classesSup+'"><i class="icon-home"></i></span>' };
	if (libelle == 'Mobile') { return '<span title="Mobile" class="label '+classesSup+'"><i class="icon-road"></i></span>' };
	return '<span title="'+libelle+'" class="label '+classesSup+'"><i class="icon-question-sign"></i></span>';
};

/** 
 * ---- traduireCategorieCommentaire
 * Retourne, pour une valeur donnée à un commentaire, l'icone correspondant selon la convention définie (voir code directement - explicite).
 * Paramètres :
 *		- num : INT - Valeur du commentaire
 * Retour :
 *		- STRING HTML - Icone (Badge Bootstrap + Icone JQ)
 */
Annuaire.traduireCategorieCommentaire = function traduireCategorieCommentaire(/* int */ num) {
	if (num == -1) { return '<span class="badge badge-error"><i class="icon-warning-sign icon-white"></i></span>' }; 	// Alerte
	if (num == 3) { return '<span class="badge badge-success"><i class="icon-heart icon-white"></i></span>' };			// Bonne nouvelle
	return '<span class="badge"><i class="icon-asterisk icon-white"></i></span>'; 										// Défaut
};

/** 
 * ---- afficherInfoEntreprise
 * Affiche dans l'hero-unit les informations de l'entreprise demandée 
 * Paramètres :
 *		- donnees : Objet - Données retournées par le serveur (voir fonction de requétage)
 * Retour :
 *		- RIEN (Page directement modifiée)
 */
Annuaire.afficherInfoEntreprise = function afficherInfoEntreprise(/* objet */ donnees) {
	Annuaire.afficherListeEntreprises();

	if (typeof donnees.entreprise === "undefined") { Annuaire.afficherErreur( "Désolé, cette entreprise n'est pas en BDD." ); return; }
	Annuaire.infoEntrepriseCourante = donnees.entreprise;
	donnees = donnees.entreprise;
	if (typeof donnees === "undefined") { Annuaire.afficherErreur( "Désolé, cette entreprise n'est pas en BDD." ); return; }
	
	// Génération du html par templating :
	donnees.droitModification = Annuaire.droitModification;
	$(".module .hero-unit").html( Annuaire.templates['InfoEntreprise'](donnees) );

	// Possibilité de trier les tables :
	Annuaire.tableSorters =	[];
	
	if (donnees.contacts && donnees.contacts.length > 0) {
		Annuaire.tableSorters[0] = new TINY.table.sorter("Annuaire.tableSorters[0]");
		Annuaire.tableSorters[0].head = "head";
		Annuaire.tableSorters[0].asc = "asc";
		Annuaire.tableSorters[0].desc = "desc";
		Annuaire.tableSorters[0].afterSortCallback=function() { // Lors du tri, lescallbacks mis sur les clicks des boutons sont perdus, donc on les réinitialise :
			$('.btnSupprContact').click( function(event) { // On récupère l'ID du contact à supprimer :
				if (event.target.children.length == 0)
					{ event.target = event.target.parentNode; } // On a cliqué sur l'icone et non sur le bouton, du coup on remonte au bouton.
				var idContact = parseInt(event.target.getAttribute('id-contact'));
				Annuaire.confirmerAction('Êtes-vous sûr de vouloir supprimer ce contact ?', '', function(id) { Annuaire.supprimerContact(id); }, idContact);
			});
			// Préremplissage du formulaire de modification/ajout d'un contact :
			$('.btn-modifContact').click(function(event){Annuaire.preremplirFormulaireUpdateContact(event)});
			$('.btn-ajoutContact').click(Annuaire.preremplirFormulaireUpdateContactId);
			// Popover pour afficher le comm' sur le contact :
			$("a[rel=popover], span[rel=popover]").popover();
		};
		Annuaire.tableSorters[0].init("contacts-table",0);
	}
	if (donnees.relations && donnees.relations.length > 0) {
		Annuaire.tableSorters[1] = new TINY.table.sorter("Annuaire.tableSorters[1]");
		/*
		Annuaire.tableSorters[1].head = "head";
		Annuaire.tableSorters[1].asc = "asc";
		Annuaire.tableSorters[1].desc = "desc";
		Annuaire.tableSorters[1].init("relations-table",2);
		*/
	}
	
	if (donnees.commentaires && donnees.commentaires.length > 0) {
		Annuaire.tableSorters[2] = new TINY.table.sorter("Annuaire.tableSorters[2]");
		Annuaire.tableSorters[2].head = "head";
		Annuaire.tableSorters[2].asc = "asc";
		Annuaire.tableSorters[2].desc = "desc";
		Annuaire.tableSorters[2].init("remarques-table",3);
		Annuaire.tableSorters[2].afterSortCallback=function() { // Lors du tri, lescallbacks mis sur les clicks des boutons sont perdus, donc on les réinitialise :
			$('.btnSupprCommentaire').click( function(event) { // On récupère l'ID du comm' à supprimer :
				if (event.target.children.length == 0)
					{ event.target = event.target.parentNode; } // On a cliqué sur l'icone et non sur le bouton, du coup on remonte au bouton.
				var idCommentaire = parseInt(event.target.getAttribute('id-commentaire'));
				Annuaire.confirmerAction('Êtes-vous sûr de vouloir supprimer ce commentaire ?', '', function(id) { Annuaire.supprimerCommentaire(id); }, idCommentaire);
			});	
		};
	}

	// Préremplissage du formulaire de modification de l'entreprise :
	$('.btn-modifEntreprise').click(function(event){Annuaire.preremplirFormulaireModifEntreprise(event)});
	
	// Popover :
	$("a[rel=popover], span[rel=popover]").popover();
	$("span[rel=popover]").popover();
};

/** 
 * ---- afficherResultatRechercheContacts
 * Paramètres :
 *		- donnees : Objet - Données retournées par le serveur (voir fonction de requétage)
		- processedKeywords : Array - Liste des mots-clés + champs en entrée
 * Retour :
 *		- RIEN (Page directement modifiée)
 */
Annuaire.afficherResultatRechercheContacts = function afficherResultatRechercheContacts(/* objet */ donnees, /* array */ processedKeywords) {

	// Gestion des erreurs :
	if (donnees.code != 'ok' ) {
		Annuaire.afficherErreur( donnees.mesg );
		return;
	}
	
	// Génération du html par templating :
	donnees.droitModification = Annuaire.droitModification;
	$(".module .hero-unit").html( Annuaire.templates['SearchContact'](donnees) );

	// Possibilité de trier les tables :
	if (donnees.entreprises && donnees.entreprises.length > 0) {
		Annuaire.tableSorters = new TINY.table.sorter("Annuaire.tableSorters");
		Annuaire.tableSorters.head = "head";
		Annuaire.tableSorters.asc = "asc";
		Annuaire.tableSorters.desc = "desc";
		Annuaire.tableSorters.afterSortCallback = function() {
			/* TO DO - La suppression & modification de contacts sur la page de recherche a été désactivée car elle nécessitait au funal un traitement particulier.
			// Ajout de l'étape de confirmation à certaines actions :
			$('.btnSupprContact').click( function(event) {
				if (event.target.children.length == 0)
					{ event.target = event.target.parentNode; } // On a cliqué sur l'icone et non sur le bouton, du coup on remonte au bouton.
				var idContact = parseInt(event.target.getAttribute('id-contact'));
				Annuaire.confirmerAction('Êtes-vous sûr de vouloir supprimer ce contact ?', '', function(id) { Annuaire.supprimerContact(id); }, idContact);
			});

			// Préremplissage du formulaire de modification/ajout d'un contact :
			$('.btn-modifContact').click(function(event){Annuaire.preremplirFormulaireUpdateContact(event)});
			$('.btn-ajoutContact').click(Annuaire.preremplirFormulaireUpdateContactId);
			*/
			// Pour chaque entreprise de la liste, on permet d'afficher leur détail par simple clic :
			$('.entreprise a').click(function(event){
				if (event.target.children.length == 0) { event.target = event.target.parentNode; } // On veut les données du lien, pas du noeud <strong>.
				Annuaire.chercherInfoEntreprise(parseInt(event.target.getAttribute('id-entreprise')), Annuaire.afficherInfoEntreprise)
			});

			// Popover :
			$("a[rel=popover], span[rel=popover]").popover();
		}
		Annuaire.tableSorters.init("search-contacts-table",0);
	}


	
	// Surlignage des occurences des mots-clés :
	for (var i = 0; i < processedKeywords.length; i++) {
		var regexKeyword = new RegExp(processedKeywords[i]['val'], "i");
		if (processedKeywords[i]['champ'] != '*') {
			$('#search-contacts tbody .'+processedKeywords[i]['champ']).highlight(processedKeywords[i]['val']);
			$('#search-contacts tbody .'+processedKeywords[i]['champ']+' a[rel=popover], #search-contacts tbody .'+processedKeywords[i]['champ']+' span[rel=popover]').each(function(index) {
				var contentPopover = $(this).attr('data-content');
				contentPopover.replace(regexKeyword, highlightContentPopover);
				$(this).attr('data-content', contentPopover);
			});
		}
		else {
			$('#search-contacts tbody').highlight(processedKeywords[i]['val']);
			$("a[rel=popover], span[rel=popover]").each(function(index) {
				var contentPopover = $(this).attr('data-content');
				contentPopover = contentPopover.replace(regexKeyword, highlightContentPopover);
				$(this).attr('data-content', contentPopover);
			});
		}
	}
	// Définition de la couleur de surlignage pour les résultats de la recherche :
	$(".highlight").css({ backgroundColor: "#BED2FF" });

	function highlightContentPopover(match, offset, string) {
		return '<span class="highlight" style="background-color: '+Annuaire.Colors['SEARCH_RESULTS_BACKGROUND']+';">'+match+'</span>';
	}
};
