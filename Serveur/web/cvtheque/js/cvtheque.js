/*
 * @author Loïc Gevrey
 *
 *
 */

$(document).ready(function() {
    Rechercher();
});

function Rechercher(){
    annee=$('#annee_voulu');
    mots_clefs=$('#mot_clef_voulu');
    $('#div_cv').empty();
    $.post("/cvtheque/ajax/cv.cible.php?action=rechercher_cv", {
        annee : annee.val(),
        mots_clefs : mots_clefs.val() 
    },function success(retour){
        retour = $.trim(retour);
        liste_etudiants = $.parseJSON(retour);
        select_etudiant = "<center></center>";
        select_etudiant += "<input id='in_filtre' placeholder='Filtrer' style='width : 180px;'/>";
       
        for(i=0;i<liste_etudiants.length;i++){
            id_etudiant = liste_etudiants[i]['ID_ETUDIANT'];
            nom_etudiant = liste_etudiants[i]['NOM_ETUDIANT'];
            prenom_etudiant = liste_etudiants[i]['PRENOM_ETUDIANT'];
            annee = liste_etudiants[i]['ANNEE'];
            titre_cv = liste_etudiants[i]['TITRE_CV'];
            
            if( nom_etudiant == null){
                nom_etudiant = '';
            }
            if( prenom_etudiant == null){
                prenom_etudiant = '';
            }
            if( id_etudiant != null){
                select_etudiant +="<div>";
                if(liste_etudiants[i]['etat'] == 0){
                    select_etudiant += "<img id='img_new"+id_etudiant+"' src='/cvtheque/img/new.png' style='margin-right : 5px;'>";
                }
                
                if(liste_etudiants[i]['etat'] == 1){
                    select_etudiant += "<a class='lien_cv' id='lien_cv"+id_etudiant+"' href='javascript:Afficher_CV("+id_etudiant+");' style='font-style:italic;font-weight:bold'>";
                    select_etudiant += nom_etudiant+' '+prenom_etudiant+" ("+annee+"IF)"; 
                    select_etudiant += "<span class='titre_cv' style='margin-left : 8px;' >"+titre_cv+"</span>";
                    select_etudiant +="</a>";
                }else{
                    select_etudiant += "<a class='lien_cv' id='lien_cv"+id_etudiant+"' href='javascript:Afficher_CV("+id_etudiant+");'>";
                    select_etudiant += nom_etudiant+' '+prenom_etudiant+" ("+annee+"IF)";
                    select_etudiant += "<span class='titre_cv' style='margin-left : 8px;' >"+titre_cv+"</span>";
                    select_etudiant +="</a>";
                }
                
                select_etudiant += "<a href='javascript:Favoris("+id_etudiant+");' style='position : relative; top : -2px; margin-left : 10px;'>";
                if(liste_etudiants[i]['favoris'] == 0){
                    select_etudiant += "<img id='img"+id_etudiant+"' src='/cvtheque/img/star_off.png' class='unstar' style='float: right'>"+"</a>";
                }else{
                    select_etudiant += "<img id='img"+id_etudiant+"' src='/cvtheque/img/star_on.png' class='star' style='float: right'>"+"</a>";
                }
                select_etudiant += "</div>";
            }
        }
    
        $('#div_liste_cv').empty();
        $('#div_liste_cv').append(select_etudiant); 
        $('#div_liste_cv').css('width','auto');
        $('#div_liste_cv').css('margin-right','0');
        
        $('#in_filtre').keypress(function(){
            var filter = $(this).val();
            $('.lien_cv').each(function(){
                if ($(this).text().toLowerCase().indexOf(filter.toLowerCase())!=-1 || filter=='') {
                    $(this).parent().show();
                } else {
                    $(this).parent().hide();
                }
            })
        });
        
        $('#in_filtre').blur(function(){
            var filter = $(this).val();
            $('.lien_cv').each(function(){
                if ($(this).text().toLowerCase().indexOf(filter.toLowerCase())!=-1 || filter=='') {
                    $(this).parent().show();
                } else {
                    $(this).parent().hide();
                }
            })
        });
    });
}

function Afficher_CV(_id_etudiant){
    $('.titre_cv').hide();
    $('#div_liste_cv').css('width','210');
    $('#div_liste_cv').css('margin-right','10px');
    $('#img_new'+_id_etudiant).remove();
    $('#lien_cv'+_id_etudiant).css('font-weight', 'normal');
    $('#lien_cv'+_id_etudiant).css('font-style', 'normal');
    $('#div_cv').load('/cvtheque/php/cv.php?inc=1&id_etudiant='+_id_etudiant);
}

function Favoris(_id_etudiant){
    if ($("#img"+_id_etudiant).hasClass('unstar')){
        $.post("/cvtheque/ajax/cv.cible.php?action=star_cv", {
            id_etudiant : _id_etudiant
        },function success(retour){
            retour = $.trim(retour);
             retour_decode = $.parseJSON(retour);
        if (retour_decode['code'] == 'ok'){
                $("#img"+_id_etudiant).removeClass('unstar');
                $("#img"+_id_etudiant).addClass('star');
                $("#img"+_id_etudiant).attr('src', '/cvtheque/img/star_on.png');
            }
        });
    }else{
        $.post("/cvtheque/ajax/cv.cible.php?action=unstar_cv", {
            id_etudiant : _id_etudiant
        },function success(retour){
            retour = $.trim(retour);
             retour_decode = $.parseJSON(retour);
        if (retour_decode['code'] == 'ok'){
                $("#img"+_id_etudiant).removeClass('star');
                $("#img"+_id_etudiant).addClass('unstar');
                $("#img"+_id_etudiant).attr('src', '/cvtheque/img/star_off.png');
            }
        });
    }
}

