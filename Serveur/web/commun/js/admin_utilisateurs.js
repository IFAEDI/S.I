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

	$( "#admin_utilisateurs #filter_service" ).click( filtrer_service );
	$( "#admin_utilisateurs #no_filter_service" ).click( supprime_filtre_service );
	$( "#admin_utilisateurs #filter_type" ).click( filtrer_type );
	$( "#admin_utilisateurs #no_filter_type" ).click( supprime_filtre_type );

	$( "#admin_utilisateurs #raffraichir" ).click( recupererListeUtilisateurs );
	$( "#admin_utilisateurs #ajouter" ).click( ajouterUtilisateur );

	$( "#admin_user_dialog #enregistrer" ).click( enregistrerUtilisateur );
	$( "#admin_del_user_dialog #confirmer" ).click( confirmerSuppressionUtilisateur );
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
				/* Actualisation du combo dans la dialog d'ajout/edition */
				raffraichirListeRoles();
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
* Raffraichit la liste des rôles
*/
function raffraichirListeRoles() {
	
	var options = '';
	var i = 0;

	for( i = 0; i < liste_types.length; i++ ) {

		options += '<option value=' + i + '>';
		options += liste_types[i];
		options += '</option>';
	}

	$( '#admin_user_dialog #role' ).html( options );
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
	$( "a.edit" ).click( editerUtilisateur );
	$( "a.del"  ).click( supprimerUtilisateur );
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
* Ajout d'un utilisateur
*/
function ajouterUtilisateur() {

	/* On édite aucun utilisateur */
	action_sur = -1;

	/* On vide tous les champs */
	$( "#admin_user_dialog input" ).each( function() {
		$(this).val( '' );
	} );

	/* On balance la dialog */
	$( "#admin_user_dialog" ).modal( 'show' );

	/* Si jamais il y avait eu des erreurs précédemment, on enlève les class error */
	$( "#admin_user_dialog input" ).parents( ".control-group" ).removeClass( "error" );
	$( "#admin_user_dialog #erreur" ).hide();
}

/**
* Edition d'un utilisateur
*/
function editerUtilisateur() {

	/* On édite un utilisateur qui a l'id X */
	action_sur = $(this).attr( 'uid' );

	/* On vide tous les champs */
	$( "#admin_user_dialog input" ).each( function() {
		$(this).val( '' );
	} );

	/* On demande au serveur de nous fournir toutes les informations concernant le user */
	$.ajax( {
		async: false,
		type: "GET",
		dataType: "json",
		url: "commun/ajax/admin_utilisateurs.cible.php",
		data : {
			action: "get_user_info",
			id    : action_sur
		},
                success: function( msg ) {

                        if( msg.code == "ok" ) {

				$( "#admin_user_dialog #login" ) .val( msg.utilisateur.login );
				$( "#admin_user_dialog #nom" )   .val( msg.utilisateur.nom );
				$( "#admin_user_dialog #prenom" ).val( msg.utilisateur.prenom );
				$( "#admin_user_dialog #role"   ).val( msg.utilisateur.role );
				
				/* Mails */
				var i = 0;
				$( "#admin_user_dialog .libelle_mail" ).each( function() {

					if( i < msg.utilisateur.mails.length ) {
						$(this).val( msg.utilisateur.mails[i][0] );
						i++;
					}
				} );

				var i = 0;
				$( "#admin_user_dialog .mail" ).each( function() {

					if( i < msg.utilisateur.mails.length ) {
						$(this).val( msg.utilisateur.mails[i][1] );
						i++;
					}
				} );

				/* Téléphones */
                                var i = 0;
                                $( "#admin_user_dialog .libelle_telephone" ).each( function() {

                                        if( i < msg.utilisateur.telephones.length ) {
                                                $(this).val( msg.utilisateur.telephones[i][0] );
                                                i++;
                                        }
                                } );

                                var i = 0;
                                $( "#admin_user_dialog .telephone" ).each( function() {

                                        if( i < msg.utilisateur.telephones.length ) {
                                                $(this).val( msg.utilisateur.telephones[i][1] );
                                                i++;
                                        }
                                } );
	

				/* Si jamais il y avait eu des erreurs précédemment, on enlève les class error */
				$( "#admin_user_dialog #erreur" ).hide();
				$( "#admin_user_dialog input" ).parents( ".control-group" ).removeClass( "error" );
				/* On balance le dialog */
				$( "#admin_user_dialog" ).modal( 'show' );
                        }
                        else {
                                var err = 'Une erreur est survenue lors de la récupération des informations sur l\'utilisateur : ' + msg.code + '/' + msg.mesg;
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
* Enregistrement des modifications effectuées sur un utilisateur
*/
function enregistrerUtilisateur() {

	/* Si jamais il y avait eu des erreurs précédemment, on enlève les class error */
	$( "#admin_user_dialog input" ).parents( ".control-group" ).removeClass( "error" );

	/* Récupération des données */
	var login  = $( "#admin_user_dialog #login"  ).val();
	var passwd = $( "#admin_user_dialog #pwd"    ).val();
	var nom	   = $( "#admin_user_dialog #nom"    ).val();
	var prenom = $( "#admin_user_dialog #prenom" ).val();
	var role   = $( "#admin_user_dialog #role"   ).val();
	var erreur = false;

	/* On test que les données sont bien saisies */
	if( login.length == 0 ) {
		$( "#admin_user_dialog #login" ).parents( '.control-group' ).addClass( "error" );
		erreur = true;
	}
	/* On force la saisie du mot de passe qu'à la création */
	if( passwd.length == 0 && action_sur == -1 ) {
		$( "#admin_user_dialog #pwd" ).parents( '.control-group' ).addClass( "error" );
		erreur = true;
	}
	if( nom.length == 0 ) {
		$( "#admin_user_dialog #nom" ).parents( '.control-group' ).addClass( "error" );
		erreur = true;
	}

	/* Si on a eu une erreur, on ne va pas plus loin */
	if( erreur == true ) {
		var msg = "Veuillez saisir tous les champs indiqués en rouge avant de poursuivre.";
		$( "#admin_user_dialog #erreur" ).html( msg );
		$( "#admin_user_dialog #erreur" ).slideDown();
		return false;
	}

	/* Si on a un mdp, on le transmet crypté */
	if( passwd.length > 0 ) {
		passwd = hex_sha1( passwd );
	}

        /* On formatte la liste des mails et leurs libellés */
        var mails_array = [];
        var i = 0;
        $( "#admin_user_dialog .libelle_mail" ).each( function() {

                mails_array[i] = [$(this).val(), ''];
                i++;
        } );

        var i = 0;
        $( "#admin_user_dialog .mail" ).each( function() {

                mails_array[i][1] = $(this).val();
                i++;
        } );

        /* On formatte la liste des téléphones et leurs libellés */
        var telephones_array = [];
        var i = 0;
        $( "#admin_user_dialog .libelle_telephone" ).each( function() {

                telephones_array[i] = [$(this).val(), ''];
                i++;
        } );

        var i = 0;
        $( "#admin_user_dialog .telephone" ).each( function() {

                telephones_array[i][1] = $(this).val();
                i++;
        } );

	/* Envoie de la requête au serveur */
	$.ajax( {
		async: false,
		type: "GET",
		dataType: "json",
		url: "commun/ajax/admin_utilisateurs.cible.php",
		data: {
			action    : "edit_user",
			id        : action_sur,
			login     : login,
			pwd       : passwd,
			nom       : nom,
			prenom    : prenom,
			role      : role,
			mails     : mails_array,
			telephones: telephones_array
		},
                success: function( msg ) {

                        if( msg.code == "ok" ) {
				/* Pas très optimisé mais bon..... on refresh la table entière */
				recupererListeUtilisateurs();
				$( "#admin_user_dialog" ).modal( 'hide' );
                        }
                        else {
                                var err = 'Une erreur est survenue lors de la mise à jour : ' + msg.code + '/' + msg.mesg;
                                $( '#admin_user_dialog #erreur' ).html( err );
                                $( '#admin_user_dialog #erreur' ).slideDown();
                        }

                },
                error: function( obj, ex, msg ) {
                        alert( ex + ' - ' + msg + '\n' + obj.responseText );
                }
	} );	
}

/**
* Suppression d'un utilisateur
*/
function supprimerUtilisateur() {

	action_sur = $(this).attr( 'uid' );
	$( "#admin_del_user_dialog" ).modal( 'show' );
}

/**
* Confirme que l'utilisateur doit être supprimé
*/
function confirmerSuppressionUtilisateur() {

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
				/* Optimisation powa ! (ou pas) */
				recupererListeUtilisateurs();
				$( '#admin_utilisateurs' ).modal( 'hide' );
                        }
                        else {
                                var err = 'Une erreur est survenue lors de la suppression : ' + msg.code + '/' + msg.mesg;
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
