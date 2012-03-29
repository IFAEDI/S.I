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
		$("#formAjoutEntreprise").validate();
		if ($("#formAjoutEntreprise").validate()) {
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
	});
	
	// Mise en page - Dimensionnement de la liste selon la hauteur disponible sur la page.
	$('.liste_entreprises').css('height', (window.innerHeight - 180)+'px');
	
});