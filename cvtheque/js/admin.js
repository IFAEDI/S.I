var liste_utilisateurs = null;

$(document).ready(function() {
    recupererListeUtilisateurs();
});

function ArreterDiffusion(){
    $.post("/cvtheque/ajax/cv.cible.php?action=arreter_diffusion", {
        },function success(retour){
            retour = $.trim(retour)
            retour_decode = $.parseJSON(retour);
            if (retour_decode['code'] != 'ok'){
                Afficher_erreur(retour);
            }else{
                $('#mod_supression').modal('hide');
                location.reload(); 
            }
        });
}

function MettreNouveau(){
    $.post("/cvtheque/ajax/cv.cible.php?action=mettre_nouveau", {
        },function success(retour){
            retour = $.trim(retour)
            retour_decode = $.parseJSON(retour);
            if (retour_decode['code'] != 'ok'){
                Afficher_erreur(retour);
            }else{
                $('#mod_supression').modal('hide');
                location.reload(); 
            }
        });
}

function ViderFavorisCV(){
    $.post("/cvtheque/ajax/cv.cible.php?action=vider_favoris", {
        },function success(retour){
            retour = $.trim(retour)
            retour_decode = $.parseJSON(retour);
            if (retour_decode['code'] != 'ok'){
                Afficher_erreur(retour);
            }else{
                $('#mod_supression').modal('hide');
                location.reload(); 
            }
        });
}

function Afficher_erreur(erreur){
    div_erreur = $("#div_erreur");
    div_erreur.text(erreur);
    if (!div_erreur.is(':visible')) {
        div_erreur.show('blind');
    }
    return;
}



function recupererListeUtilisateurs() {
    /* Préparation des données à balancer */
    $.ajax( {
        type: "GET",
        dataType: "json",
        url: "/cvtheque/ajax/cv.cible.php",
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
                $( '#erreur' ).html( err );
                $( '#erreur' ).slideDown();
            }
        },
        error: function( obj, ex, msg ) {
            alert( ex + ' - ' + msg + '\n' + obj.responseText );
        }
        
    } );
}




/**
* Actualise le contenu de la table
* debut : L'index de l'utilisateur à partir duquel on commence à afficher
*/
function raffraichirTable( debut ) {
    var tbody = '';
    var i = 0;
    /* Parcourons les utilisateurs */
    while( i < liste_utilisateurs.length ) {
        tbody += '<tr>';
        tbody += '<td>' + (i+1) + '</td>';
        tbody += '<td>' + liste_utilisateurs[i].nom +' '+ liste_utilisateurs[i].prenom + '</td>';

        tbody += '<td style="text-align: center;">';
        if(liste_utilisateurs[i].acces_cvtheque == 0){
            tbody += '<a id="btn'+liste_utilisateurs[i].ID_PERSONNE+'" href="javascript:Changer_acces('+ liste_utilisateurs[i].ID_PERSONNE +')" class="btn btn-success" style="width : 100px; height : 20px;">Autoriser</a> ';
        }else{
            tbody += '<a id="btn'+liste_utilisateurs[i].ID_PERSONNE+'" href="javascript:Changer_acces('+ liste_utilisateurs[i].ID_PERSONNE +')"  class="btn btn-danger" style="width : 100px; height : 20px;">Interdire</a> ';
        }
        tbody += '</td>';
        tbody += '</tr>';

        i++;
    }

    $( '#liste_utilisateurs' ).html( tbody );
}


function Changer_acces(id_utilisteur){
    btn = $('#btn'+id_utilisteur);
    if(btn.hasClass('btn-success')){
        etat = 1;
    }else{
        etat = 0;
    }
    
    
    $.ajax( {
        type: "POST",
        dataType: "json",
        url: "/cvtheque/ajax/cv.cible.php?action=changer_acces",
        data: { 
            id_utilisateur  : id_utilisteur,
            etat  : etat
        },
        success: function( msg ) {
            if( msg.code != "ok" ) {
                var err = 'Une erreur est survenue lors de la récupération des utilisateurs : ' + msg.mesg; 
                $( '#div_erreur' ).html( err );
                $( '#div_erreur' ).slideDown();
            }else{
               
                if(etat == 1){
                    btn.text('Interdire');
                    btn.removeClass('btn-success')
                    btn.addClass('btn-danger')
                }else{
                    btn.text('Autoriser');
                    btn.removeClass('btn-danger')
                    btn.addClass('btn-success')
                }
            }
        },
        error: function( obj, ex, msg ) {
            alert( ex + ' - ' + msg + '\n' + obj.responseText );
        }
        
    } );
}