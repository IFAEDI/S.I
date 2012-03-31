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
	// Pour chaque entreprise de la liste, on permet d'afficher leur détail par simple clic :
	$('.entreprise').click(function(){Annuaire.chercherInfoEntreprise(1, Annuaire.afficherInfoEntreprise)});
	
	// Mise en place d'une validation des formulaires :
	$("#formAjoutEntreprise").validate();
	$("#btnValiderAjoutEntreprise").click( function() {
		Annuaire.ajouterEntreprise();
	});
	
	$("#formModifContact").validate();
	
	// Mise en page - Ajout/Supression de champs pour des mails/telephones supplémentaires dans les formulaires :
	$('#formModifContactTel').focusout(function(event) { Annuaire.activerBoutonAjoutEntree(event, 'formModifContactTelAjout', 'Bureau', '');});
	$('#formModifContactTel').mouseout(function(event) { Annuaire.activerBoutonAjoutEntree(event, 'formModifContactTelAjout', 'Bureau', '');});
	$('#formModifContactEmail').focusout(function(event) { Annuaire.activerBoutonAjoutEntree(event, 'formModifContactEmailAjout', 'Pro', '');});
	$('#formModifContactEmail').mouseout(function(event) { Annuaire.activerBoutonAjoutEntree(event, 'formModifContactEmailAjout', 'Pro', '');});
		
	
	// Mise en page - Dimensionnement de la liste selon la hauteur disponible sur la page.
	$('.liste_entreprises').css('height', (window.innerHeight - 180)+'px');
	
	// Initialisation de l'objet Annuaire :
	Annuaire.droitModification = ($("#inputModif").val()==1)?true:false;
	
});