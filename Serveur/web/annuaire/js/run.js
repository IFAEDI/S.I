/**
 * -----------------------------------------------------------
 * RUN - FONCTION JS
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Contact - benjamin.planche@aldream.net
 * ---------------------
 * Regroupe l'ensemble des instructions pour initier le moteur JS de la page Annuaire.
 */
 
$('document').ready(function() {
	
	// Mise en place d'une validation des formulaires :
	$("#formUpdateEntreprise").validate();
	$("#btnValiderUpdateEntreprise").click( function() {
		Annuaire.updaterEntreprise();
	});
	
	$("#formUpdateContact").validate();
	$("#btnValiderUpdateContact").click( function() {
		Annuaire.updaterContact();
	});
	
	// Mise en page - Ajout/Supression de champs pour des mails/telephones supplémentaires dans les formulaires :
	$('#formUpdateContactTel').focusout(function(event) { Annuaire.activerBoutonAjoutEntree(event, 'formUpdateContactTelAjout', 'Bureau', '');});
	$('#formUpdateContactTel').mouseout(function(event) { Annuaire.activerBoutonAjoutEntree(event, 'formUpdateContactTelAjout', 'Bureau', '');});
	$('#formUpdateContactEmail').focusout(function(event) { Annuaire.activerBoutonAjoutEntree(event, 'formUpdateContactEmailAjout', 'Pro', '');});
	$('#formUpdateContactEmail').mouseout(function(event) { Annuaire.activerBoutonAjoutEntree(event, 'formUpdateContactEmailAjout', 'Pro', '');});
	$('#formUpdateContact a[type="reset"]').click(Annuaire.resetFormContact);
	$('#modalUpdateContact a[data-dismiss="modal"]').click(Annuaire.resetFormContact);	
	
	// Mise en page - Dimensionnement de la liste selon la hauteur disponible sur la page.
	$('.liste_entreprises').css('height', (window.innerHeight - 180)+'px');
	
	// Initialisation de l'objet Annuaire :
	Annuaire.droitModification = ($("#inputModif").val()==1)?true:false;
	
});