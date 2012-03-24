	$('document').ready(function() {
		$('.entreprise').click(function(){Annuaire.afficherInfoEntreprise(1)});
		$('.liste_entreprises').css('height', (window.innerHeight - 180)+'px');
	});