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
	$('.entreprise').click(function(){Annuaire.afficherInfoEntreprise(1)});
	
	// Mise en page - Dimensionnement de la liste selon la hauteur disponible sur la page.
	$('.liste_entreprises').css('height', (window.innerHeight - 180)+'px');
});