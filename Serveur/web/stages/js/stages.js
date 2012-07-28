/*
* Auteur : Sébastien Mériot (sebastien.meriot@gmail.com)
* Date : 2012
*/

/**
* Initialisation de l'espace de nom
*/
var Stages = {};

/**
 * Met à jour les résultats en écrivant le code HTML à la main.
 * Pour chaque résultat présent, un item de liste est inséré avec
 * les informations concernant le stage.
 */
Stages.afficherResultats = function afficherResultats(json) {
	$('#fenetre').show();
	$('#information').show();
	$('#description').slideUp();

	/* Test le code retour de la requête AJAX, si pas ok, affichage d'une erreur et <em>mesg</em> contient la raison. */
	if (json.code != 'ok') {
		$('#information').html('Impossible de récupérer le résultat.' +
				'Merci de réessayer ultérieurement ou de contacter un administrateur.<br />' +
				'Le serveur a renvoyé : <i>"' + json.mesg + '"</i>.' );
		$('#fenetre').hide();
		$('#resultats').html('');

		return;
	}

	/* Formattage des résutats (s'il y en a) */
	var affichage = '';
	var resultats = json.stages;
	if(!resultats || resultats.length === 0) {
		$('#information').text('Aucun résultat n\'a été trouvé.');
		$('#fenetre').hide();
		$('#resultats').html('');
		return;
	}

	/* Gestion des offres au pluriel */
	var pluriel = (resultats.length > 1) ? 's' : '';
	$('#information').text(resultats.length  + ' résultat' + pluriel + ' trouvé' + pluriel + '. Cliquez sur la ligne pour avoir plus d\'info.');

	for (var i = 0; i < resultats.length; ++i) {
		var resultat = resultats[i];
		
		// Ajout des informations primordiales éventuellement manquantes
		if (!resultat.titre) {
			resultat.titre = "Offre de stage";
		}
		if (!resultat.description) {
			resultat.description = "Pas de description, voir fichier joint.";
		}

		/* Affichage dans le tableau */
		affichage += '<tr>'
		affichage += '<td>' + (i+1) + '</td>';
		affichage += '<td>' + resultat.titre + '</td>';
		affichage += '<td>' + resultat.entreprise + '</td>';
		affichage += '<td>' + resultat.lieu + '</td>';
		affichage += '<td>' + resultat.annee + 'ème année</td>';
		affichage += '</tr>';

		affichage += '<tr class="hide">';
		affichage += '<td></td>';
		affichage += '<td colspan="4">';
		affichage +=  '<p>' + resultat.description + '</p>';
		if( resultat.contact ) {
			affichage += '<p><b>Contact</b> : ' + resultat.contact + '</p>';
		}
		affichage +=  '<a href="https://intranet-if.insa-lyon.fr/stages/descriptif/' + resultat.lien_fichier + '">Plus d\'info</a>';
		affichage += '</td>';
		affichage += '</tr>';
	}

	$('#resultats').html(affichage);

	/* Ajout de la gestion du clique permettant d'afficher plus d'infos */
	$('#resultats tr').click( function() {
		if( $(this).next().is( ':visible' ) ) {
			$(this).next().slideUp();
		}
		else {
			$(this).next().slideDown();
		}
	} );
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

		$.post('stages/ajax/stages.cible.php', obj, function(d,t,j) {
			Stages.afficherResultats(JSON.parse(d));	
		});

		return false; // évite que l'évènement soit propagé, ie
			      // que le formulaire essaie d'atteindre l'action.
	});
});

