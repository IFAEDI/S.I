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
       
        if(liste_etudiants.length == 0){
            select_etudiant += "<br>Votre recherche n'a donné aucun résultat";
        }
       
       
        for(i=0;i<liste_etudiants.length;i++){
            id_personne = liste_etudiants[i]['ID_PERSONNE'];
            nom_etudiant = liste_etudiants[i]['NOM'];
            prenom_etudiant = liste_etudiants[i]['PRENOM'];
            annee = liste_etudiants[i]['ANNEE'];
            titre_cv = liste_etudiants[i]['TITRE_CV'];
            
            if( nom_etudiant == null){
                nom_etudiant = '';
            }
            if( prenom_etudiant == null){
                prenom_etudiant = '';
            }
            if( id_personne != null){
                select_etudiant +="<div>";
                if(liste_etudiants[i]['etat'] == 0){
                    select_etudiant += "<img id='img_new"+id_personne+"' src='/cvtheque/img/new.png' style='margin-right : 5px;'>";
                }
                
                if(liste_etudiants[i]['etat'] == 1){
                    select_etudiant += "<a class='lien_cv' id='lien_cv"+id_personne+"' href='javascript:Afficher_CV("+id_personne+");' style='font-style:italic;font-weight:bold'>";
                    if(annee == 0){
                        select_etudiant += nom_etudiant+' '+prenom_etudiant; 
                    }else{
                        select_etudiant += nom_etudiant+' '+prenom_etudiant+" ("+annee+"IF)"; 
                    }
                    select_etudiant += "<span class='titre_cv' style='margin-left : 8px;' >"+titre_cv+"</span>";
                    select_etudiant +="</a>";
                }else{
                    select_etudiant += "<a class='lien_cv' id='lien_cv"+id_personne+"' href='javascript:Afficher_CV("+id_personne+");'>";
                    if(annee == 0){
                        select_etudiant += nom_etudiant+' '+prenom_etudiant; 
                    }else{
                        select_etudiant += nom_etudiant+' '+prenom_etudiant+" ("+annee+"IF)"; 
                    }
                    select_etudiant += "<span class='titre_cv' style='margin-left : 8px;' >"+titre_cv+"</span>";
                    select_etudiant +="</a>";
                }
                
                select_etudiant += "<a href='javascript:Favoris("+id_personne+");' style='position : relative; top : -2px; margin-left : 10px;'>";
                if(liste_etudiants[i]['favoris'] == 0){
                    select_etudiant += "<img id='img"+id_personne+"' src='/cvtheque/img/star_off.png' class='unstar' style='float: right'>"+"</a>";
                }else{
                    select_etudiant += "<img id='img"+id_personne+"' src='/cvtheque/img/star_on.png' class='star' style='float: right'>"+"</a>";
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

function Afficher_CV(id_personne){
    $('.titre_cv').hide();
    $('#div_liste_cv').css('width','210');
    $('#div_liste_cv').css('margin-right','10px');
    $('#img_new'+id_personne).remove();
    $('#lien_cv'+id_personne).css('font-weight', 'normal');
    $('#lien_cv'+id_personne).css('font-style', 'normal');
    $('#div_cv').load('/cvtheque/php/cv.php?inc=1&id_personne='+id_personne);
}

function Favoris(id_personne){
    if ($("#img"+id_personne).hasClass('unstar')){
        $.post("/cvtheque/ajax/cv.cible.php?action=star_cv", {
            id_personne : id_personne
        },function success(retour){
            retour = $.trim(retour);
            retour_decode = $.parseJSON(retour);
            if (retour_decode['code'] == 'ok'){
                $("#img"+id_personne).removeClass('unstar');
                $("#img"+id_personne).addClass('star');
                $("#img"+id_personne).attr('src', '/cvtheque/img/star_on.png');
            }
        });
    }else{
        $.post("/cvtheque/ajax/cv.cible.php?action=unstar_cv", {
            id_personne : id_personne
        },function success(retour){
            retour = $.trim(retour);
            retour_decode = $.parseJSON(retour);
            if (retour_decode['code'] == 'ok'){
                $("#img"+id_personne).removeClass('star');
                $("#img"+id_personne).addClass('unstar');
                $("#img"+id_personne).attr('src', '/cvtheque/img/star_off.png');
            }
        });
    }
}

