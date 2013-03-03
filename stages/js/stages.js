/*
* Auteur : Sébastien Mériot (sebastien.meriot@gmail.com)
* Date : 2012
*/

/**
* Initialisation de l'espace de nom
*/
var Stages = {};
Stages.templatesResultatsRecherche = {};
Stages.sorter ={};
/**
 * Met à jour les résultats en écrivant le code HTML à la main.
 * Pour chaque résultat présent, un item de liste est inséré avec
 * les informations concernant le stage.
 */
Stages.afficherResultats = function afficherResultats(json) {
	$('#fenetre').show();
	$('#information').show();
	$('#description').slideUp();

	$('#fenetre tbody').html('');
	/* Test le code retour de la requête AJAX, si pas ok, affichage d'une erreur et <em>mesg</em> contient la raison. */
	if (json.code != 'ok') {
		$('#information').html('Impossible de récupérer le résultat.' +
				'Merci de réessayer ultérieurement ou de contacter un administrateur.<br />' +
				'Le serveur a renvoyé : <i>"' + json.mesg + '"</i>.' );
		$('#fenetre').hide();
		$('#fenetre tbody').html('');

		return;
	}
	/* Formattage des résutats (s'il y en a) */
	var pluriel = (json.stages.length > 1) ? 's' : '';
	$('#information').text(json.stages.length  + ' résultat' + pluriel + ' trouvé' + pluriel + '. Cliquez sur le bouton à gauche pour avoir plus d\'info sur le stage.');

	$('#fenetre tbody').html(Stages.templatesResultatsRecherche(json));
	
	/* Ajout de la gestion du tri : */
	Stages.sorter = new TINY.table.sorter("Stages.sorter");
	Stages.sorter.head = "head";
	Stages.sorter.asc = "asc";
	Stages.sorter.desc = "desc";
	Stages.sorter.beforeSortCallback=function() {
		/* Fermeture des descriptions détaillées ouvertes:*/
		var ligne = $(".temp").prev();
		var bouton = ligne.find('.bouton button');
        $(".temp").remove();
		ligne.attr('deploye', 0);
		bouton.html('<i class="icon-chevron-down icon-white">');
		bouton.removeClass('btn-inverse');
		bouton.addClass('btn-info');
	};
	Stages.sorter.afterSortCallback=function() {
		/* Ajout de la gestion du clique permettant d'afficher plus d'infos */
		$('#fenetre .bouton button').click( function() {
			var ligneCiblee = $(this).closest('tr');
			if (ligneCiblee.attr('deploye') == 0) { // Non-déployé
				var tr = $('<tr class="temp"></tr>');
				var td = $('<td colspan=5"></td>');
				td.html(ligneCiblee.find('.desc-stage').html());
				tr.html(td);
				ligneCiblee.after(tr);
				ligneCiblee.attr('deploye', 1);
				$(this).html('<i class="icon-chevron-up icon-white">');
				$(this).removeClass('btn-info');
				$(this).addClass('btn-inverse');
			}
			else {
				ligneCiblee.attr('deploye', 0);


				$(this).html('<i class="icon-chevron-down icon-white">');
				$(this).removeClass('btn-inverse');
				$(this).addClass('btn-info');
				ligneCiblee.next().remove();
			}
		} );
	};
	Stages.sorter.init("fenetre",2);
}

/** 
 * ---- extraireContenuPourTriParAnnees
 * Retourne la donnée à utiliser pour TINY.table.sorter pour trier les stages selon l'année
 * Paramètres :
 *		- noeud : Elément DOM utilisé normalement par TINY.table.sorter
 * Retour :
 *		- donnée à utiliser pour le tri
 */
Stages.extraireContenuPourTriParAnnees = function extraireContenuPourTriParAnnees(noeud) {
	var anneeString = noeud.nodeValue;
	return (parseInt(anneeString.charAt(0))*10+anneeString.indexOf('4')+anneeString.indexOf('5'))+'';
}

$('document').ready(function() {

	$('#fenetre').hide();
	$('#information').hide();

	// Préparation du template pour les résultats :
	Stages.templatesResultatsRecherche = Handlebars.compile($("#templateSearchStages").html());
		
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

		$.ajax({
			url: "stages/ajax/searchStages.cible.php",
			type: "POST",
			data: obj,
			dataType: "json",
			success: function( data ) {
				if( data.code == 'ok' ) {
					Stages.afficherResultats( data );
				}
				else {
					alert( data.mesg );
				}
			},
			error: function() {
				alert( 'Une erreur est survenue lors de l\'envoi de la requête au serveur.' );
			}
		});


		return false; // évite que l'évènement soit propagé, ie
			      // que le formulaire essaie d'atteindre l'action.
	});
});

