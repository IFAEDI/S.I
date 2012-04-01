/**************************************************
* Author : Sébastien Mériot			  *
* Date : 30.03.2012				  *
* Description : Gestion des utilisateurs en base  *
**************************************************/

var max_utilisateur_par_page = 15;

var liste_utilisateurs = null;
var liste_services     = null;
var liste_types	       = null;

var filtre_service     = '';
var filtre_type        = '';

/* Utilisateur sur lequel porte l'action courante */
var action_sur         = -1;

$(document).ready( function() {

	/* Récupération des informations */
	recupererLibelles();
	recupererListeUtilisateurs();

	$( "a#filter_service" ).click( filtrer_service );
	$( "a#no_filter_service" ).click( supprime_filtre_service );
	$( "a#filter_type" ).click( filtrer_type );
	$( "a#no_filter_type" ).click( supprime_filtre_type );

	$( "a#raffraichir" ).click( recupererListeUtilisateurs );

	$( "#admin_del_user_dialog #confirm" ).click( confirmer_suppression_utilisateur );
} );


/**
* Envoie une requête AJAX pour récupérer tous les utilisateurs enregistrés
*/
function recupererListeUtilisateurs() {

	/* Préparation des données à balancer */
	$.ajax( {
		type: "GET",
                dataType: "json",
                url: "commun/ajax/admin_utilisateurs.cible.php",
                data: { 
			action  : "get_user_list"
		},
                success: function( msg ) {

                        if( msg.code == "ok" ) {
				/* Conservation de la liste en mémoire et on actualise la table */
				liste_utilisateurs = clone(msg.utilisateurs);
				raffraichirTable( 0 );
                        }
                        else {
				var err = 'Une erreur est survenue lors de la récupération des utilisateurs : ' + msg.mesg; 
				$( '#admin_utilisateurs #erreur' ).html( err );
				$( '#admin_utilisateurs #erreur' ).slideDown();
                        }

                },
                error: function( obj, ex, msg ) {
                        alert( ex + ' - ' + msg + '\n' + obj.responseText );
                }
	} );
}

/**
* Envoie une requête AJAX pour récupérer les libellés des services et des types de compte
*/
function recupererLibelles() {

        /* Préparation des données à balancer */
        $.ajax( {
		async: false,
                type: "GET",
                dataType: "json",
                url: "commun/ajax/admin_utilisateurs.cible.php",
                data: {
                        action  : "get_labels"
                },
                success: function( msg ) {

                        if( msg.code == "ok" ) {
				/* Conservation des informations en mémoire */
                                liste_services = clone(msg.services);
                                liste_types    = clone(msg.types);
				/* Actualisation des filtres */
				raffraichirEnTete();
                        }
                        else {
                                var err = 'Une erreur est survenue lors de la récupération des libellés : ' + msg.mesg;  
                                $( '#admin_utilisateurs #erreur' ).html( err );
                                $( '#admin_utilisateurs #erreur' ).slideDown();
                        }

                },
                error: function( obj, ex, msg ) {
                        alert( ex + ' - ' + msg + '\n' + obj.responseText );
                }
        } );
}  

/**
* Raffraichit les en-têtes des colonnes pour afficher les filtres
*/
function raffraichirEnTete() {

	/* Filtre pour les services d'authentification */
	var services = '<li><a id="no_filter_service">Tous</a></li>';
	var i = 0;

	for( s in liste_services ) {

		services += '<li>';
		services += '<a id="filter_service">' + liste_services[s] + '</a>';
		services += '</li>';
	}

	$( '#admin_utilisateurs #service_hdr .dropdown-menu' ).html( services );

	/* Filtre pour les types d'utilisateurs */
	var types = '<li><a id="no_filter_type">Tous</a></li>';

        for( i = 0; i < liste_types.length; i++ ) {

                types += '<li>';
                types += '<a id="filter_type">' + liste_types[i] + '</a>';
                types += '</li>';
        }

        $( '#admin_utilisateurs #type_hdr .dropdown-menu' ).html( types );
}

/**
* Actualise le contenu de la table
* debut : L'index de l'utilisateur à partir duquel on commence à afficher
*/
function raffraichirTable( debut ) {

	var tbody = '';
	var i = 0;
	var index = 1;

	/* Parcourons les utilisateurs */
	while( i < liste_utilisateurs.length ) {

		/* Application des filtres s'ils sont présents */
		if( filtre_service.length > 0 ) {
			if( liste_services[liste_utilisateurs[i].service] != filtre_service ) {
				i++;
				continue;
			}
		}
		if( filtre_type.length > 0 ) {
			if( liste_types[liste_utilisateurs[i].type] != filtre_type ) {
				i++;
				continue;
			}
		}

		/* On applique les bornes */
		if( i < debut ) continue;
		if( i == debut + max_utilisateur_par_page ) break; 

		tbody += '<tr>';
		tbody += '<td>' + (index++) + '</td>';
		tbody += '<td>' + liste_utilisateurs[i].login + '</td>';
		tbody += '<td>' + liste_services[liste_utilisateurs[i].service] + '</td>';
		tbody += '<td>' + liste_types[liste_utilisateurs[i].type] + '</td>';
		tbody += '<td>';
		if( liste_utilisateurs[i].nom != null ) {
			tbody += liste_utilisateurs[i].nom;
		}
		tbody += '</td>';
		tbody += '<td>';
		if( liste_utilisateurs[i].prenom != null ) {
			tbody += liste_utilisateurs[i].prenom;
		}
		tbody += '</td>';

		/* Les actions, à savoir Voir, Editer, Bannir */
		var id = liste_utilisateurs[i].id;
		tbody += '<td style="text-align: center;">';
		tbody += '<a href="#" class="edit" uid="' + id + '"><i class="icon-pencil"></i></a> ';
		tbody += '<a href="#" class="del"  uid="' + id + '"><i class="icon-remove"></i></a>';
		tbody += '</td>';
		tbody += '</tr>';

		i++;
	}

	$( '#admin_utilisateurs #liste_utilisateurs' ).html( tbody );

	/* Ajout des triggers */
	$( "a.edit" ).click( editer_utilisateur );
	$( "a.del"  ).click( supprimer_utilisateur );
}

/**
* Applique un filtre sur les services d'authentification
*/
function filtrer_service() {

	filtre_service = $(this).html();
	raffraichirTable( 0 );
}

function supprime_filtre_service() {

	filtre_service = '';
	raffraichirTable( 0 );
}

/**
* Applique un filtre sur les types de compte
*/
function filtrer_type() {

	filtre_type = $(this).html();
	raffraichirTable( 0 );
}

function supprime_filtre_type() {

	filtre_type = '';
	raffraichirTable( 0 );
}

/**
* Edition d'un utilisateur
*/
function editer_utilisateur() {
	action_sur = $(this).attr( 'uid' );
	$( "#admin_user_dialog" ).modal( 'show' );
}

/**
* Suppression d'un utilisateur
*/
function supprimer_utilisateur() {

	action_sur = $(this).attr( 'uid' );
	$( "#admin_del_user_dialog" ).modal( 'show' );
}

/**
* Confirme que l'utilisateur doit être supprimé
*/
function confirmer_suppression_utilisateur() {

	$.ajax( {
                async: false,
                type: "GET",
                dataType: "json",
                url: "commun/ajax/admin_utilisateurs.cible.php",
                data: {
                        action  : "del_user",
			id	: action_sur
                },
                success: function( msg ) {

                        if( msg.code == "ok" ) {
                                /* Conservation des informations en mémoire */
                                liste_services = clone(msg.services);
                                liste_types    = clone(msg.types);
                                /* Actualisation des filtres */
                                raffraichirEnTete();
                        }
                        else {
                                var err = 'Une erreur est survenue lors de la récupération des libellés : ' + msg.code + '/' + msg.mesg;
                                $( '#admin_utilisateurs #erreur' ).html( err );
                                $( '#admin_utilisateurs #erreur' ).slideDown();
                        }

                },
                error: function( obj, ex, msg ) {
                        alert( ex + ' - ' + msg + '\n' + obj.responseText );
                }
	} );

	$( "#admin_del_user_dialog" ).modal( "hide" );
}
