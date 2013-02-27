/*
* Auteur : Sébastien Mériot (sebastien.meriot@gmail.com)
* Date : 2012
*/

/**
* Initialisation de l'espace de nom
*/
var Stages = {};
Stages.templatesResultatsRecherche = {}
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
	var pluriel = (json.stages.length > 1) ? 's' : '';
	$('#information').text(json.stages.length  + ' résultat' + pluriel + ' trouvé' + pluriel + '. Cliquez sur le bouton à gauche pour avoir plus d\'info sur le stage.');

	json.stages.sort(function(s1,s2){ return s1.entreprise.localeCompare(s2.entreprise); }); // Tri des résultats avant affichage, par nom d'entreprise croissante.
	$('#resultats').html(Stages.templatesResultatsRecherche(json));

	/* Ajout de la gestion du clique permettant d'afficher plus d'infos */
	$('#resultats .bouton button').click( function() {
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
			ligneCiblee.next().remove();
			ligneCiblee.attr('deploye', 0);
			$(this).html('<i class="icon-chevron-down icon-white">');
			$(this).removeClass('btn-inverse');
			$(this).addClass('btn-info');
		}
	} );
	
	/* Ajout de la gestion du tri : */
	$("#fenetre").tablesorter({
		headers: {         
            0: { 
                // On désactive le tri sur la 1ere colonne (celle des boutons) 
                sorter: false 
            } 
        }
	}); 
	$("#fenetre").bind("sortStart",function() { 
		$(".temp").prev().attr('deploye', 0);
        $(".temp").remove(); 
	});
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

