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

// Info sur l'utilisateur :
Annuaire.utilisateur = {};

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
		Annuaire.afficherErreur( "AJAX - Echec de la requête : " + textStatus );
	});

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
	
	if ($("#formUpdateEntreprise").validate()) {
	
		/* int */ var idEntrepriseActuelle = -1
		if ($('#formUpdateEntrepriseId').val() != "0") {
			idEntrepriseActuelle = Annuaire.infoEntrepriseCourante.description.id_entreprise;
		}
		
		// Envoi :
		var description = {id: parseInt($('#formUpdateEntrepriseId').val()), nom : encodeURIComponent(nomEntr), secteur: encodeURIComponent($('#formUpdateEntrepriseSecteur').val()), description: encodeURIComponent($('#formUpdateEntrepriseDescription').val())};
		var /* objet */ requete = $.ajax({
			url: "./annuaire/ajax/updateEntreprise.cible.php",
			type: "POST",
			data: description,
			dataType: "json"
		});
		
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
					Annuaire.infoEntrepriseCourante.description = description;
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
					// Si MAJ du nom, ca et à jour la liste ...
					Annuaire.retirerEntrepriseDeListe(description.id);
					Annuaire.insererEntrepriseDansListe({id_entreprise: description.id, nom: nomEntr});
					Annuaire.afficherListeEntreprises(); // Si MAJ du nom, ca et à jour la liste ...
				}
				else {
					Annuaire.afficherErreur('Entreprise - Une erreur est survenue (id = '+donnees.id+')' );
				}
			}
			else {
				Annuaire.afficherErreur('Entreprise - Une erreur est survenue ('+donnees.code+')' );
			}
		});
		requete.fail(function(jqXHR, textStatus) {
			Annuaire.afficherErreur('Entreprise - Une erreur est survenue ('+textStatus+')' );
		});
		
		
		
	}
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
	// Vérification du formulaire :
	if ($("#formUpdateContact").validate()) {
	
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
			id: parseInt($('#formUpdateContactId').val()),
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
				pays : encodeURIComponent($('#formUpdateContactVillePays').val()),
			},
			commentaire : encodeURIComponent($('#formUpdateContactCom').val()),
			priorite : parseInt($('#formUpdateContactPriorite').val()),
		};
		var /* objet */ requete = $.ajax({
			url: "./annuaire/ajax/updateContact.cible.php",
			type: "POST",
			data: nouveauContact,
			dataType: "json"
		});
		
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
							if (Annuaire.infoEntrepriseCourante.contacts[i].id_contact == nouveauContact.id) {
								Annuaire.infoEntrepriseCourante.contacts[i] = nouveauContact;
								break;
							}
						}
						
					}
					else {
						nouveauContact.id = donnees.id;
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
				Annuaire.afficherErreur('Contact - Une erreur est survenue ('+donnees.code+')' );
			}
		});
		requete.fail(function(jqXHR, textStatus) {
			Annuaire.afficherErreur('Contact - Une erreur est survenue ('+textStatus+')' );
		});
	}
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
	
	// Vérification du formulaire :
	if ($("#formAjoutCommentaire").validate()) {
	
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
						nouveauCommentaire.id = donnees.id;
						if (typeof Annuaire.infoEntrepriseCourante.commentaires === "undefined") { Annuaire.infoEntrepriseCourante.commentaires = []; }
						nouveauCommentaire.contenu = encodeURIComponent(nouveauCommentaire.contenu);
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
				Annuaire.afficherErreur('Commentaire : Une erreur est survenue ('+donnees.code+')' );
			}
		});
		requete.fail(function(jqXHR, textStatus) {
			Annuaire.afficherErreur('Commentaire : Une erreur est survenue ('+textStatus+')' );
		});
		
		
		
	}
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
			Annuaire.afficherErreur('Contact - Suppression : Une erreur est survenue ('+donnees.code+')' );
		}
	});
	requete.fail(function(jqXHR, textStatus) {
		Annuaire.afficherErreur('Contact - Suppression : Une erreur est survenue ('+textStatus+')' );
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
			Annuaire.afficherErreur('Commentaire - Suppression : Une erreur est survenue ('+donnees.code+')' );
		}
	});
	requete.fail(function(jqXHR, textStatus) {
		Annuaire.afficherErreur('Commentaire - Suppression : Une erreur est survenue ('+textStatus+')' );
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

// ------------------------ AFFICHAGE ------------------------ //

/** 
 * ---- afficherListeEntreprises
 * Affiche la liste des noms d'entreprise
 * Paramètres :
 *		- RIEN
 * Retour :
 *		- RIEN (Page maj)
 */
Annuaire.afficherListeEntreprises = function afficherListeEntreprises() {
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
	if (libelle == 'Bureau') { return '<span title="Bureau" class="label '+classesSup+'"><i class="icon-home"></i></span>' };
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
 *		- idEntreprise : INT - ID de l'entreprise voulue
 * Retour :
 *		- RIEN (Page directement modifiée)
 */
Annuaire.afficherInfoEntreprise = function afficherInfoEntreprise(/* objet */ donnees) {
	// Sorry pour les pavés de cette fonction, dur de faire un compromis entre clarté JS et clarté HTML ...
	// Si quelqu'un veut refaire ça plus proprement, ca devrait pas être trop difficile.

	if (typeof donnees.entreprise === "undefined") { return; }
	Annuaire.infoEntrepriseCourante = donnees.entreprise;
	donnees = donnees.entreprise;
	if (typeof donnees === "undefined") { return; }
	
	// Génération des blocs intermédiaires (nécessitant des boucles) :
	var /* string */ tableauContacts = '';
	if ((typeof donnees.contacts === "undefined") || (donnees.contacts.length == 0)) {
		tableauContacts = 'Aucun contact. '+(Annuaire.droitModification?'<a data-toggle="modal" href="#modalUpdateContact" title="Ajouter Contact" class="btn  btn-mini btn-ajoutContact"><i class="icon-plus"></i></a>':'');
	} else {
		tableauContacts = '<table class="table table-stripped tablesorter"> \n'+
'										<thead>\n'+
'											<tr> \n'+
'												<th>Nom</th>\n'+
'												<th>Prénom</th>\n'+
'												<th>Poste</th> \n'+
'												<th>Email</th> \n'+
'												<th>Tel</th>\n'+
'												<th class="first">Lieu</th>\n'+
'												<th>Priorité</th>\n'+
'												<th>'+(Annuaire.droitModification?'<a data-toggle="modal" href="#modalUpdateContact" title="Ajouter Contact" class="btn  btn-mini btn-ajoutContact"><i class="icon-plus"></i></a>':'')+'</th>\n'+
'										</thead> \n'+
'										<tbody>\n';
		for (var /* int */ i in donnees.contacts) {
			// Nom + Prenom + Fonction
			tableauContacts += '			<tr> \n'+
	'												<td><strong>'+donnees.contacts[i].personne.nom+'</strong></td>\n'+
	'												<td>'+donnees.contacts[i].personne.prenom+'</td>\n'+
	'												<td><em>'+donnees.contacts[i].fonction+'</em></td> \n'+
	'												<td><table>';
			// Mails
			for (var /* int */ j in donnees.contacts[i].personne.mails) {
				tableauContacts += '<tr><td><a href="mailto:'+donnees.contacts[i].personne.mails[j][1]+'">'+donnees.contacts[i].personne.mails[j][1]+'</a></td><td>'+Annuaire.afficherLibelle(donnees.contacts[i].personne.mails[j][0], '')+'</td></tr>';
			}
			
			tableauContacts += '</table></td><td><table>\n';
			
			// Tel
			for (var /* int */ j in donnees.contacts[i].personne.telephones) {
				tableauContacts += '<tr><td>'+donnees.contacts[i].personne.telephones[j][1]+'</td><td>'+Annuaire.afficherLibelle(donnees.contacts[i].personne.telephones[j][0], '')+'</td></tr>';
			}
			// Lieu
			tableauContacts += '</table></td><td class="first"><span style="display: none;">'+donnees.contacts[i].ville.code_postal+'</span><a target="_blank" title="'+donnees.contacts[i].ville.code_postal+' - '+donnees.contacts[i].ville.libelle+', '+donnees.contacts[i].ville.pays+'" href="http://maps.google.com/maps?q='+donnees.contacts[i].ville.code_postal+'+'+donnees.contacts[i].ville.libelle+',+'+donnees.contacts[i].ville.pays+'"><i class="icon-map-marker"></i></a></td> ';
			// Remarque + Priorité
			tableauContacts += '</td><td><span href="#" title="'+donnees.contacts[i].commentaire+'" class="label label-'+Annuaire.traduireCouleur(donnees.contacts[i].priorite)+'">'+Annuaire.traduirePrioriteContactTexte(donnees.contacts[i].priorite)+'</span></td> ';
			
			// Bouton modif
			if (Annuaire.droitModification) { // Ajout des boutons de modifications d'un contact :
				tableauContacts += '												<td><a title="Editer Contact" id-contact='+donnees.contacts[i].id_contact+' data-toggle="modal" href="#modalUpdateContact" class="btn  btn-mini btn-modifContact"><i class="icon-pencil"></i></a><a title="Supprimer Contact" id-contact='+donnees.contacts[i].id_contact+' class="btn btn-danger btn-mini btnSupprContact"><i class="icon-remove"></i></a></td>\n'
			}
			tableauContacts += '											</tr>';
		}
		
		tableauContacts += '</tbody></table> \n';
	}
	
	var /* string */ tableauRelations = '';
	
	if (typeof donnees.relations !== "undefined") {
		var /* string */ tableauParrainage = '';
		var /* string */ tableauRIF = '';
		var /* string */ tableauStages = '';
		var /* string */ tableauEntretiens = '';
		
		tableauRelations = '<table class="table table-stripped tablesorter"><tbody>';
		
		// Parrainage
		if ((typeof donnees.relations.parrainage !== "undefined") || (donnees.relations.parrainage.length == 0)) { // Aucun parrainage avec
			tableauParrainage = '<tr><th>Parrainage</th><td>/</td><td><span class="label label-default">Jamais</span></td></tr> ';
		} else {
			tableauParrainage = '<tr><th rowspan='+donnees.relations.parrainage.length+'>Parrainage</th><td>Promo '+donnees.relations.parrainage[0].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(donnees.relations.parrainage[0].couleur)+'">'+donnees.relations.parrainage[0].commentaire+'</span></td></tr>';
			for (var /* int */ i = 1; i < donnees.relations.parrainage.length; i++) {
				tableauParrainage += '<tr><td>Promo '+donnees.relations.parrainage[i].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(donnees.relations.parrainage[i].couleur)+'">'+donnees.relations.parrainage[i].commentaire+'</span></td></tr>';
			}
		}
		
		// RIF
		if ((typeof donnees.relations.rif === "undefined") || (donnees.relations.rif.length == 0)) {
			tableauRIF = '<tr><th>RIF</th><td>/</td><td><span class="label label-default">Jamais</span></td></tr> ';
		} else {
			tableauRIF = '<tr><th rowspan='+donnees.relations.rif.length+'>RIF</th><td>'+donnees.relations.rif[0].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(donnees.relations.rif[0].couleur)+'">'+donnees.relations.rif[0].commentaire+'</span></td></tr>';
			for (var /* int */ i = 1; i < donnees.relations.rif.length; i++) {
				tableauRIF += '<tr><td>'+donnees.relations.rif[i].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(donnees.relations.rif[i].couleur)+'">'+donnees.relations.rif[i].commentaire+'</span></td></tr>';
			}
		}	
		
		// Stages
		if ((typeof donnees.relations.stages === "undefined") || (donnees.relations.stages.length == 0)) {
			tableauStages = '<tr><th>Stages</th><td>/</td><td><span class="label label-default">Jamais</span></td></tr> ';
		} else {
			tableauStages = '<tr><th rowspan='+donnees.relations.stages.length+'>Stages</th><td>'+donnees.relations.stages[0].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(1)+'">'+donnees.relations.stages[0].nbSujets+' sujets</span></td></tr>';
			for (var /* int */ i = 1; i < donnees.relations.stages.length; i++) {
				tableauStages += '<tr><td>'+donnees.relations.stages[i].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(1)+'">'+donnees.relations.stages[i].nbSujets+' sujets</span></td></tr>';
			}
		}
		
		// Entretiens
		if ((typeof donnees.relations.entretiens === "undefined") || (donnees.relations.entretiens.length == 0)) {
			tableauEntretiens = '<tr><th>Entretien</th><td>/</td><td><span class="label label-default">Jamais</span></td></tr> ';
		} else {
			tableauEntretiens = '<tr><th rowspan='+donnees.relations.entretiens.length+'>Entretiens</th><td>'+donnees.relations.entretiens[0].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(1)+'">'+donnees.relations.entretiens[0].nbSessions+' sessions</span></td></tr>';
			for (var /* int */ i = 1; i < donnees.relations.entretiens.length; i++) {
				tableauEntretiens += '<tr><td>'+donnees.relations.entretiens[i].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(1)+'">'+donnees.relations.entretiens[i].nbSessions+' sessions</span></td></tr>';
			}
		}
		
		tableauRelations += tableauParrainage+'\n'+
'							'+tableauRIF+'\n'+
'							'+tableauStages+'\n'+
'							'+tableauEntretiens+'\n'+'</tbody></table>';
	} else {
		tableauRelations = 'Aucune relation.';
	}

	var /* string */ tableauCommentaires = '';
	if (typeof donnees.commentaires === "undefined") {
		tableauCommentaires = 'Aucun commentaire. <a data-toggle="modal" href="#modalAjoutCommentaire" title="Ajouter Commentaire" class="btn  btn-mini"><i class="icon-plus"></i></a>';
	} else {
		tableauCommentaires = '<table class="table table-stripped tablesorter">\n'+
'										<thead>\n'+
'											<tr> \n'+
'												<th class="first"></th>\n'+
'												<th>Auteur</th>\n'+
'												<th class="first">Poste</th>\n'+
'												<th>Date</th>\n'+
'												<th>Commentaires</th>\n'+
'												<th>'+(Annuaire.droitModification?'<a data-toggle="modal" href="#modalAjoutCommentaire" title="Ajouter Commentaire" class="btn  btn-mini btn-ajoutCommentaire"><i class="icon-plus"></i></a>':'')+'</th></thead> \n'+
'										<tbody>';

		for (var /* int */ i in donnees.commentaires) {
			tableauCommentaires += '<tr> \n'+
'												<td class="first"><span style="display: none;">'+donnees.commentaires[i].categorie+'</span>'+Annuaire.traduireCategorieCommentaire(donnees.commentaires[i].categorie)+'</td> \n'+
'												<td>'+donnees.commentaires[i].personne.prenom +' '+donnees.commentaires[i].personne.nom+'</td> \n'+
'												<td><small>'+Annuaire.traduireRole(donnees.commentaires[i].personne.role)+'</small></td> \n'+
'												<td>'+donnees.commentaires[i].timestamp +'</td>\n'+
'												<td>'+donnees.commentaires[i].contenu +'</td>';
			// Bouton modif
			if (Annuaire.droitModification) { // Ajout des boutons de supression d'un comm' :
				tableauCommentaires += '												<td><a title="Supprimer Commentaire" id-commentaire='+donnees.commentaires[i].id_commentaire+' class="btn btn-danger btn-mini btnSupprCommentaire"><i class="icon-remove"></i></a></td>\n';
			}
			tableauCommentaires += '</tr>';
		}

		tableauEntretiens = '</tbody></table>';
	}
	
	var btnModifEntreprise = '';
	if (Annuaire.droitModification) { // Ajout des boutons de modifications d'un contact :
		btnModifEntreprise += '<a title="Editer Entreprise" data-toggle="modal" href="#modalUpdateEntreprise" class="btn  btn-mini btn-modifEntreprise"><i class="icon-pencil" ></i></a>';
	}
	
	// Génération du bloc entier :
	var content = '<h1>'+donnees.description.nom+' <small>'+donnees.description.secteur+'&#09;'+btnModifEntreprise+'</small></h1>';
	if( donnees.description.description ) {
		content +=
'							<p>'+donnees.description.description+'</p> \n';
	}
	content +=
'							\n'+
'							<div class="accordion" id="accordion2">\n'+
'								<div class="accordion-group">\n'+
'								<div class="accordion-heading">\n'+
'								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#contacts"> \n'+
'								<h2>Contacts</h2> \n'+
'								</a>\n'+
'							</div>\n'+
'							<div id="contacts" class="accordion-body collapse in"> \n'+
'								<div class="accordion-inner">\n'+
'									 '+tableauContacts+'\n'+
'								</div>\n'+
'							</div>\n'+
'							</div>\n'+
'							<div class="accordion-group"> \n'+
'							<div class="accordion-heading">\n'+
'								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#relations">\n'+
'								<h2>Relations</h2>\n'+
'								</a>\n'+
'							</div>\n'+
'							<div id="relations" class="accordion-body collapse">\n'+
'								<div class="accordion-inner">\n'+
'									 '+tableauRelations+'\n'+
'								</div>\n'+
'							</div>\n'+
'							</div>\n'+
'							<div class="accordion-group"> \n'+
'							<div class="accordion-heading">\n'+
'								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#remarques">\n'+
'								<h2>Remarques</h2>\n'+
'								</a>\n'+
'							</div>\n'+
'							<div id="remarques" class="accordion-body collapse">\n'+
'								<div class="accordion-inner">\n'+
'									'+tableauCommentaires+' \n'+
'								</div>\n'+
'							</div>\n'+
'							</div>\n'+
'						</div> ';

	$(".module .hero-unit").html( content );

	// Possibilité de trier les tables :
	$("#contacts table").tablesorter({ 
        headers: { 
            
            7: { 
                // On désactive le tri sur la dernière colonne (celle des boutons) 
                sorter: false 
            } 
        } 
    }); 
	$("#remarques table").tablesorter({ 
        headers: { 
            
            5: { 
                // On désactive le tri sur la dernière colonne (celle des boutons) 
                sorter: false 
            } 
        }, 
		sortList: [[3,1]]
    });
	$("#relations table").tablesorter(); 
	$("#commentaires table").tablesorter(); 
	
	// Ajout de l'étape de confirmation à certaines actions :
	$('.btnSupprContact').click( function(event) {
		if (event.target.children.length == 0)
			{ event.target = event.target.parentNode; } // On a cliqué sur l'icone et non sur le bouton, du coup on remonte au bouton.
		var idContact = parseInt(event.target.getAttribute('id-contact'));
		Annuaire.confirmerAction('Êtes-vous sûr de vouloir supprimer ce contact ?', '', function(id) { Annuaire.supprimerContact(id); }, idContact);
	});

	$('.btnSupprCommentaire').click( function(event) {
		if (event.target.children.length == 0)
			{ event.target = event.target.parentNode; } // On a cliqué sur l'icone et non sur le bouton, du coup on remonte au bouton.
		var idCommentaire = parseInt(event.target.getAttribute('id-commentaire'));
		Annuaire.confirmerAction('Êtes-vous sûr de vouloir supprimer ce commentaire ?', '', function(id) { Annuaire.supprimerCommentaire(id); }, idCommentaire);
	});	
	// Préremplissage du formulaire de modification/ajout d'un contact :
	$('.btn-modifContact').click(function(event){Annuaire.preremplirFormulaireUpdateContact(event)});
	$('.btn-ajoutContact').click(Annuaire.preremplirFormulaireUpdateContactId);
	// Préremplissage du formulaire de modification de l'entreprise :
	$('.btn-modifEntreprise').click(function(event){Annuaire.preremplirFormulaireModifEntreprise(event)});
};
