/**
 * Met à jour les résultats en écrivant le code HTML à la main.
 * Pour chaque résultat présent, un item de liste est inséré avec
 * les informations concernant le stage.
 */
function afficherResultats(resultats) {
	var affichage = '';

	if (resultats !== null)
	{
		for (var i = 0; i < resultats.length; ++i) {
			var resultat = resultats[i];
			affichage += '<li><div class="info">' +
				'<details><summary>' + resultat.titre + '</summary>' +
				'<p>' + resultat.description + '</p></details></div></li>';	
		}
	}

	if(!resultats || resultats.length === 0) {
		affichage = '<li>Aucun résultat n\'a été trouvé.</li>';
	}

	$('#resultats').html(affichage);
}

$('document').ready(function() {
	/**
	 * Préparation du comportement d'un clic sur le bouton
	 * rechercher :
	 * 1) Récupérer les valeurs des champs
	 * 2) Appeler le script ajax
	 */
	$('#envoyer').click(function() {
		var obj = {
			mots_cles: $('#mots_cles').val(),
			duree: $('#duree').val(),
			lieu: $('#lieu').val(),
			entreprise: $('#entreprise').val(),
			annee: $('#annee').val()
		};

		$.post('/stages/ajax/cible.php', obj, function(d,t,j) {
			afficherResultats(JSON.parse(d));	
		});
	});

});
