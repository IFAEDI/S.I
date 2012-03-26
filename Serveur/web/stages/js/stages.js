/**
 * Met à jour les résultats en écrivant le code HTML à la main.
 * Pour chaque résultat présent, un item de liste est inséré avec
 * les informations concernant le stage.
 */
function afficherResultats(json) {

	if (json.code === 'error') {
		$('#information').text('Impossible de récupérer le résultat ' +
				'suite à une erreur côté serveur. Merci de ' +
				'réessayer ou de contacter un administrateur.');
		$('#resultats').html('');
		return;
	}

	var affichage = '';
	var resultats = json.msg;
	if(!resultats || resultats.length === 0) {
		$('#information').text('Aucun résultat n\'a été trouvé.');
		$('#resultats').html('');
		return;
	}

	$('#information').text('');
	for (var i = 0; i < resultats.length; ++i) {
		var resultat = resultats[i];
		affichage += '<li><div class="info">' +
			'<details><summary>' + resultat.titre + '</summary>' +
			'<ul><li>Stage de <strong>' + resultat.annee + 'ème année' +
			'</strong></li>' +
			'<li><strong>Description</strong> : ' + resultat.description
		       	+ '</li>' +
			'<li><strong>Durée</strong> : ' + resultat.duree +
		       	' mois.</li>' +
			'<li><strong>Lieu</strong> : ' + resultat.lieu + '</li> ' +
			'<li><strong>Entreprise</strong> : ' + resultat.entreprise
		       	+ '</li>' +
			'</ul></details></div></li>';	
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
	$('#form_stages').submit(function() {
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

		return false; // évite que l'évènement soit propagé, ie
			      // que le formulaire essaie d'atteindre l'action.
	});

});
