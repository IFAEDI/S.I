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
		alert( "Request failed: " + textStatus );
	});
	
	return donnees_Atos;
};

/** 
 * ---- ajouterEntreprise
 * Valide le formulaire d'ajout d'une entreprise & transmet les informations à la cible PHP.
 * Paramètres :
 *		- RIEN
 * Retour :
 *		- RIEN (Page directement modifiée)
 */
Annuaire.ajouterEntreprise = function ajouterEntreprise() {
	// Vérification du formulaire :
	$("#formAjoutEntreprise").validate();
	if ($("#formAjoutEntreprise").validate()) {
		// Envoi :
		var /* objet */ requete = $.ajax({
			url: "./annuaire/ajax/ajoutEntreprise.cible.php",
			type: "POST",
			data: {nom : $('#formAjoutEntrepriseNom').val(), secteur: $('#formAjoutEntrepriseSecteur').val(), description: $('#formAjoutEntrepriseDescription').val()},
			dataType: "html"
		});

		requete.done(function(donnees) {
			alert("Ok !");
			return donnees;
		});
		requete.fail(function(jqXHR, textStatus) {
			alert( "Request failed: " + textStatus );
		});
	}
};

// ------------------------ COHESION DE LA PAGE ------------------------ //

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
Annuaire.activerBoutonAjoutEntree = function ajouterEntreeListe(event, idBouton, labelNeutre) {
	var idAleatoire = new Date().getTime();
	if (event.target.children.length == 0)
		{ event.target = event.target.parentNode; } // On a cliqué sur le "+" et non sur le bouton, du coup on remonte au bouton.
	var inputGroupe = $('#'+event.target.id).parent().parent();

	// Ajout de la ligne informative :
	var numTel = inputGroupe.find('input[type="text"]').val();
	var labelTel = inputGroupe.find('option:selected').val();
	inputGroupe.find('ul').append('<li><span class="val label label-info">'+numTel+'</span>&#09;<span class="labelVal label">'+labelTel+'</span>&#09;<a title="Supprimer" id="id'+idAleatoire+'" class="btn btn-danger btn-mini supprTel"><i class="icon-trash"></i></a></li>');
	inputGroupe.find('#id'+idAleatoire).click(function(event) {supprTel(event);});

	// RAZ des champs du tel :
	inputGroupe.find('input[type="text"]').val('');
	inputGroupe.find('option:selected').removeAttr("selected");
	inputGroupe.find('option[value="'+labelNeutre+'"]').attr('selected', 'selected');
	$('#'+idBouton).addClass('disabled');
	$('#'+idBouton).unbind('click');
	activerBoutonAjoutEntree.boutonActif[idBouton] = false;
	
	return false;
	
	function supprTel(event) {
		$('#'+event.target.id).parent().remove();
	}
};

/** 
 * ---- preremplirFormulaireModifContact
 * Préreplit le formulaire de modification d'un contact avec les infos déja acquises.
 * Paramètres :
 *		- event : OBJET EVENT - Evénement généré par la demande
 * Retour :
 *		- RIEN - Page directement changée
 */
Annuaire.preremplirFormulaireModifContact = function preremplirFormulaireModifContact(event) {
	/* int */ var idContact = parseInt(event.target.getAttribute('id-contact'));
	if (typeof Annuaire.infoEntrepriseCourante.contacts !== "undefined") {
		/* objet */ var contact;
		for (var i in Annuaire.infoEntrepriseCourante.contacts) {
			if (Annuaire.infoEntrepriseCourante.contacts[i].id == idContact) {
				contact = Annuaire.infoEntrepriseCourante.contacts[i];
				break;
			}
		}
	
		if (typeof contact !== "undefined") {
			$('#formModifContactNom').val(contact.nom);
			$('#formModifContactPrenom').val(contact.prenom);
			$('#formModifContactPoste').val(contact.metier);
			
			/* long */ var idAleatoire;
			for (/* int */ var i in Annuaire.infoEntrepriseCourante.contacts.telephones) {
				idAleatoire = new Date().getTime();
				$('#formModifContactTelGroup ul').append('<li><span class="val label label-info">'+contact.telephones[i].num+'</span>&#09;<span class="labelVal label">'+contact.telephones[i].label+'</span>&#09;<a title="Supprimer" id="id'+idAleatoire+'" class="btn btn-danger btn-mini supprTel"><i class="icon-trash"></i></a></li>');
			}
			
			for (/* int */ var i in Annuaire.infoEntrepriseCourante.contacts.emails) {
				idAleatoire = new Date().getTime();
				$('#formModifContactEmailGroup ul').append('<li><span class="val label label-info">'+contact.emails[i].num+'</span>&#09;<span class="labelVal label">'+contact.emails[i].label+'</span>&#09;<a title="Supprimer" id="id'+idAleatoire+'" class="btn btn-danger btn-mini supprTel"><i class="icon-trash"></i></a></li>');
			}
			
			$('#formModifContactPriorite').find('option[value='+contact.priorite+']').attr('selected', 'selected');
			$('#formModifContactCom').val(contact.commentaire);
			
			$('#formModifContactId').val(idContact);
		}
	}
};

// ------------------------ AFFICHAGE ------------------------ //


/** 
 * ---- traduirePrioriteContactTexte
 * Traduit textuellement une priorité numérique, selon la convention définie (voir code directement - explicite).
 * Paramètres :
 *		- priorite : INT - Valeur de la priorité à traduire
 * Retour :
 *		- STRING - Texte décrivant la priorité
 */
Annuaire.traduirePrioriteContactTexte = function traduirePrioriteContactTexte(/* int */ priorite) {
	if (priorite > 1) { return "Prioritaire" };
	if (priorite == 1) { return "Normal" };
	if (priorite == 0) { return "Incertain" };
	if (priorite < 0) { return "A éviter" };
	return "Défaut";
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
	if (num == 1) { return "success" };
	if (num == 0) { return "warning" };
	if (num < 0) { return "alert" };
	return "";
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
	if (num == 0) { return '<span class="badge badge-error"><i class="icon-warning-sign icon-white"></i></span>' }; 	// Alerte
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

	Annuaire.infoEntrepriseCourante = donnees.entreprise;
	donnees = donnees.entreprise;
	
	// Génération des blocs intermédiaires (nécessitant des boucles) :
	var /* string */ tableauContacts = '';
	if (donnees.contacts.length == 0) {
		tableauContacts = "Aucun contact.";
	} else {
		for (var /* int */ i in donnees.contacts) {
			tableauContacts += '			<tr> \n'+
	'												<td>'+donnees.contacts[i].nom+'</td>\n'+
	'												<td>'+donnees.contacts[i].prenom+'</td>\n'+
	'												<td>'+donnees.contacts[i].metier+'</td> \n'+
	'												<td><a href="mailto:'+donnees.contacts[i].email+'">'+donnees.contacts[i].email+'</a></td>\n'+
	'												<td>'+donnees.contacts[i].tel+'</td> \n'+
	'												<td><span class="label label-'+Annuaire.traduireCouleur(donnees.contacts[i].priorite)+'">'+Annuaire.traduirePrioriteContactTexte(donnees.contacts[i].priorite)+'</span></td> ';
			if (Annuaire.droitModification) { // Ajout des boutons de modifications d'un contact :
				tableauContacts += '												<td><a id-contact='+donnees.contacts[i].id+' data-toggle="modal" href="#modalModifContact" class="btn  btn-mini btn-modifContact"><i class="icon-pencil"></i></a><a id-contact='+donnees.contacts[i].id+' data-toggle="modal" href="#modalEditEntreprise" class="btn btn-danger  btn-mini"><i class="icon-remove"></i></a></td>\n'
			}
			tableauContacts += '											</tr>';
		}
	}
	
	var /* string */ tableauParrainage = '';
	var /* string */ tableauRIF = '';
	var /* string */ tableauStages = '';
	var /* string */ tableauEntretiens = '';
	
	if (typeof donnees.relations !== "undefined") {
		if ((typeof donnees.relations.parrainage !== "undefined") || (donnees.relations.parrainage.length == 0)) { // Aucun parrainage avec
			tableauParrainage = '<tr><th>Parrainage</th><td>/</td><td><span class="label label-default">Jamais</span></td></tr> ';
		} else {
			tableauParrainage = '<tr><th rowspan='+donnees.relations.parrainage.length+'>Parrainage</th><td>Promo '+donnees.relations.parrainage[0].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(donnees.relations.parrainage[0].couleur)+'">'+donnees.relations.parrainage[0].commentaire+'</span></td></tr>';
			for (var /* int */ i = 1; i < donnees.relations.parrainage.length; i++) {
				tableauParrainage += '<tr><td>Promo '+donnees.relations.parrainage[i].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(donnees.relations.parrainage[i].couleur)+'">'+donnees.relations.parrainage[i].commentaire+'</span></td></tr>';
			}
		}
		
		if ((typeof donnees.relations.rif === "undefined") || (donnees.relations.rif.length == 0)) {
			tableauRIF = '<tr><th>RIF</th><td>/</td><td><span class="label label-default">Jamais</span></td></tr> ';
		} else {
			tableauRIF = '<tr><th rowspan='+donnees.relations.rif.length+'>RIF</th><td>'+donnees.relations.rif[0].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(donnees.relations.rif[0].couleur)+'">'+donnees.relations.rif[0].commentaire+'</span></td></tr>';
			for (var /* int */ i = 1; i < donnees.relations.rif.length; i++) {
				tableauRIF += '<tr><td>'+donnees.relations.rif[i].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(donnees.relations.rif[i].couleur)+'">'+donnees.relations.rif[i].commentaire+'</span></td></tr>';
			}
		}	
		
		if ((typeof donnees.relations.stages === "undefined") || (donnees.relations.stages.length == 0)) {
			tableauStages = '<tr><th>Stages</th><td>/</td><td><span class="label label-default">Jamais</span></td></tr> ';
		} else {
			tableauStages = '<tr><th rowspan='+donnees.relations.stages.length+'>Stages</th><td>'+donnees.relations.stages[0].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(1)+'">'+donnees.relations.stages[0].nbSujets+' sujets</span></td></tr>';
			for (var /* int */ i = 1; i < donnees.relations.stages.length; i++) {
				tableauStages += '<tr><td>'+donnees.relations.stages[i].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(1)+'">'+donnees.relations.stages[i].nbSujets+' sujets</span></td></tr>';
			}
		}
		
		if ((typeof donnees.relations.entretiens === "undefined") || (donnees.relations.entretiens.length == 0)) {
			tableauEntretiens = '<tr><th>Entretien</th><td>/</td><td><span class="label label-default">Jamais</span></td></tr> ';
		} else {
			tableauEntretiens = '<tr><th rowspan='+donnees.relations.entretiens.length+'>Entretiens</th><td>'+donnees.relations.entretiens[0].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(1)+'">'+donnees.relations.entretiens[0].nbSessions+' sessions</span></td></tr>';
			for (var /* int */ i = 1; i < donnees.relations.entretiens.length; i++) {
				tableauEntretiens += '<tr><td>'+donnees.relations.entretiens[i].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(1)+'">'+donnees.relations.entretiens[i].nbSessions+' sessions</span></td></tr>';
			}
		}
	} else {
		tableauEntretiens = 'Aucune relation.';
	}

	var /* string */ tableauCommentaires = '';
	if (typeof donnees.commentaires === "undefined") {
		tableauCommentaires = 'Aucun commentaire.';
	} else {
		tableauCommentaires = '<table class="table table-stripped">\n'+
'										<thead>\n'+
'											<tr> \n'+
'												<th class="first"></th>\n'+
'												<th>Auteur</th>\n'+
'												<th class="first">Poste</th>\n'+
'												<th>Date</th>\n'+
'												<th>Commentaires</th>\n'+
'										</thead> \n'+
'										<tbody>';

		for (var /* int */ i in donnees.commentaires) {
			tableauCommentaires += '<tr> \n'+
'												<td>'+Annuaire.traduireCategorieCommentaire(donnees.commentaires[i].categorie)+'</td> \n'+
'												<td>'+donnees.commentaires[i].prenom +' '+donnees.commentaires[i].nom+'</td> \n'+
'												<td><small>'+donnees.commentaires[i].poste +'</small></td> \n'+
'												<td>'+(new Date(donnees.commentaires[i].date)).toDateString() +'</td>\n'+
'												<td>'+donnees.commentaires[i].commentaire +'</td>'+
'											</tr>';
		}

		tableauEntretiens = '</tbody></table>';
	}
	
	// Génération du bloc entier :
	$(".module .hero-unit").html('<h1>'+donnees.description.nom+' <small>'+donnees.description.secteur+'</small></h1>'+
'							<p>'+donnees.description.description+'</p> \n'+
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
'									 <table class="table table-stripped"> \n'+
'										<thead>\n'+
'											<tr> \n'+
'												<th>Nom</th>\n'+
'												<th>Prénom</th>\n'+
'												<th>Poste</th> \n'+
'												<th>Email</th> \n'+
'												<th>Tel</th>\n'+
'												<th>Rem.</th>\n'+
'										</thead> \n'+
'										<tbody>\n'+
'											'+tableauContacts+'\n'+
'										</tbody> \n'+
'								</table> \n'+
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
'									 <table class="table table-stripped"> \n'+
'										<tbody>\n'+
'											'+tableauParrainage+'\n'+
'											'+tableauRIF+'\n'+
'											'+tableauStages+'\n'+
'											'+tableauEntretiens+'\n'+
'										</tbody> \n'+
'								</table> \n'+
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
'						</div> ');

	// Préremplissage du formulaire de modification d'un contact :
	$('.btn-modifContact').click(function(event){Annuaire.preremplirFormulaireModifContact(event)});
};