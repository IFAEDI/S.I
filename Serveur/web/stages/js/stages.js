/**
 * Met à jour les résultats en écrivant le code HTML à la main.
 * Pour chaque résultat présent, un item de liste est inséré avec
 * les informations concernant le stage.
 */
function afficherResultats(json) {
	$('#fenetre').show();
	$('#information').show();

	if (json.code === 'error') {
		$('#information').text('Impossible de récupérer le résultat ' +
				'suite à une erreur côté serveur. Merci de ' +
				'réessayer ou de contacter un administrateur.');
		$('#fenetre').hide();
		$('#resultats').html('');
		return;
	}

	var affichage = '';
	var resultats = json.msg;
	if(!resultats || resultats.length === 0) {
		$('#information').text('Aucun résultat n\'a été trouvé.');
		$('#fenetre').hide();
		$('#resultats').html('');
		return;
	}

	var pluriel = (resultats.length > 1) ? 's' : '';
	$('#information').text(resultats.length  + ' résultat' + pluriel + ' trouvé' + pluriel + '.');
	for (var i = 0; i < resultats.length; ++i) {
		var resultat = resultats[i];
		
		// Ajout des informations primordiales éventuellement manquantes
		if (!resultat.titre) {
			resultat.titre = "Offre de stage";
		}
		if (!resultat.description) {
			resultat.description = "Pas de description, voir fichier joint.";
		}

		// Affichage sous forme d'une liste de définitions
		affichage += '<li class="offre"><div class="info">' +
			'<details><summary>' + resultat.titre + '</summary>' +
			'<dl class="dl-horizontal">' +
			'<dt>Stage de</dt><dd>' + resultat.annee + 'ème année</dd>' +
			'<dt>Description</dt><dd>' + resultat.description + '</dd>';
		if (resultat.contact) {
			affichage += '<dt>Contact</dt><dd>' + resultat.contact + '</dd>'; 
		}
		affichage += '<dt>Lieu</dt><dd>' + resultat.lieu + '</dd> ' +
			'<dt>Entreprise</dt><dd>' + resultat.entreprise + '</dd>' +
			'<dt>Document</dt><dd><a href="' +
			'https://intranet-if.insa-lyon.fr/stages/descriptif/' +
		       	resultat.lien_fichier + '">cliquez ici</a></dd>' +
			'</dl></details></div></li>';	
	}
	$('#resultats').html(affichage);
}

$('document').ready(function() {
	$('#fenetre').hide();
	$('#information').hide();
	/**
	 * Préparation du comportement d'un clic sur le bouton
	 * rechercher :
	 * 1) Récupérer les valeurs des champs
	 * 2) Appeler le script ajax
	 */
	$('#form_stages').submit(function() {
		var obj = {
			mots_cles: $('#mots_cles').val(),
			lieu: $('#lieu').val(),
			entreprise: $('#entreprise').val(),
			annee: $('#annee').val()
		};

		$.post('stages/ajax/cible.php', obj, function(d,t,j) {
			afficherResultats(JSON.parse(d));	
		});

		return false; // évite que l'évènement soit propagé, ie
			      // que le formulaire essaie d'atteindre l'action.
	});

});
