//****************  Variables Global  ******************//

/* (int) */var nb_langue = 0;







//****************  Fonction executée directement après la fin de chargement de la page  ******************//
$(document).ready(function() {
    //Ajouter_Langue(1,1,1,3);
    //Ajouter_Langue(2,2,2,45);


    //Accordeon triable pour les differentes partie du CV
    $( "#accordion" )
    .accordion({
        header: "> div > h3"
    })
    .sortable({
        axis: "y",
        handle: "h3",
        stop: function( event, ui ) {
            // IE doesn't register the blur when sorting
            // so trigger focusout handlers to remove .ui-state-focus
            ui.item.children( "h3" ).triggerHandler( "focusout" );
        }
    });
});

//****************  Autre fonction  ******************//

//fonction permetant l'ajout d'une langue
function Ajouter_Langue(_id_langue_etudiant,_id_niveau,_id_certif,_score_certif){
    /* (str) */var langue = "";
    langue += '<div class="control-group" id="langue'+nb_langue+'">';
    langue += '<label class="control-label">Langue</label>';
    langue += '<div class="controls">';
    langue += Create_Select('sel_langue'+nb_langue,_id_langue_etudiant,liste_langue);
    langue += Create_Select('sel_niveau'+nb_langue,_id_niveau,liste_niveau);
    langue += Create_Select('sel_certif'+nb_langue,_id_certif,liste_certif);
    langue +='<input type="text" id="prenom_etudiant" class="span3" placeholder="Prenom" value="'+_score_certif+'" style="width : 80px;">';
    langue +='<a href="javascript:Supprimer_Langue('+nb_langue+')" class="icon-remove" style="margin-left : 20px;"></a>';
    langue +='</div>';
    langue +='</div>';
    $('#div_Langues').append(langue);
    nb_langue++;
}


function Supprimer_Langue(_id_langue){
    langue = $('#langue'+_id_langue);
    langue.remove();
}





