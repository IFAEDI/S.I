//****************  Variables Global  ******************//

/* (int) */var nb_langue = 0;
/* (int) */var nb_xp = 0;
/* (int) */var nb_formation = 0;
/* (int) */var nb_diplome = 0;
var annuler_diplome = new Array();
var annuler_formation = new Array();
var annuler_xp = new Array();
var annuler_langue = new Array();

//****************  Fonction executée directement après la fin de chargement de la page  ******************//
$(document).ready(function() {
    //Ajout des champs pour ajouter une nouvelle langue
    /* (str) */var langue = "";
    langue += '<div class="control-group" id="nouvelle_langue">';
    langue += '<label class="control-label">Nouvelle langue</label>';
    langue += '<div class="controls">';
    langue += Creer_Select('sel_nouvelle_langue',-1,liste_langue);
    langue += Creer_Select('sel_nouvelle_niveau',-1,liste_niveau);
    langue += Creer_Select('sel_nouvelle_certif',-1,liste_certif);
    langue += '<input type="text" id="score_nouvelle" class="span3" placeholder="Score" style="width : 80px;" disabled>';
    langue += '<a href="javascript:Ajouter_Langue(\'\',\'\',\'\',\'\')" class="icon-ok" style="margin-left : 20px;"></a>';
    langue += '</div>';
    langue += '</div><hr>';
    $('#div_nouvelle_langue').append(langue);
    
    $('#sel_nouvelle_certif').change(function() {
        if (this.options[this.selectedIndex].value == 1){
            $('#score_nouvelle').attr('disabled', true);
        }else{
            $('#score_nouvelle').prop('disabled', false);
        }
    });

    //Ajout des champs pour ajouter une nouvelle expérience
    /* (str) */var xp = "";
    xp += '<div id="nouvelle_xp">';
    xp += '<table cellpadding="8" style="text-align : center;"><tr>';
    xp += '<td><input type="text" id="debut_nouvelle_xp" class="span3" placeholder="Debut" style="width : 80px;"></td>';
    xp += '<td><input type="text" id="fin_nouvelle_xp" class="span3" placeholder="Fin" style="width : 80px;>"</td>';
    xp += '<td><input type="text" id="titre_nouvelle_xp" class="span3" placeholder="Titre" style="width : 300px;"></td>';
    xp += '<td><input type="text" id="entreprise_nouvelle_xp" class="span3" placeholder="Entreprise" style="width : 150px;"></td>';
    xp += '<td><input type="text" id="ville_nouvelle_xp" class="span3" placeholder="Ville" style="width : 150px;"></td>';
    xp += '<td><a href="javascript:Ajouter_XP(\'\',\'\',\'\',\'\',\'\',\'\')" class="icon-ok" style="margin-left : 20px;"></a></td>';
    xp += '<tr><td></td><td></td>';
    xp += '<td COLSPAN=3><textarea rows="4" id="desc_nouvelle_xp" style="width : 660px;" placeholder="Descirption"></textarea></td>';  
    xp += '</tr></table></div><hr>';
    $('#div_nouvelle_XP').append(xp);
    $( "#debut_nouvelle_xp").datepicker();
    $( "#fin_nouvelle_xp").datepicker();

    //Ajout des champs pour ajouter une nouvelle formation
    /* (str) */var formation = "";
    formation += '<div class="control-group" id="nouvelle_formation'+nb_formation+'">';
    formation += '<label class="control-label">Formation</label>';
    formation += '<div class="controls">';
    formation += '<input type="text" id="debut_nouvelle_formation" class="span3" placeholder="Début"  style="width : 80px;">';
    formation += '<input type="text" id="fin_nouvelle_formation" class="span3" placeholder="Fin"  style="width : 80px;">';
    formation += '<input type="text" id="annee_nouvelle_formation" class="span3" placeholder="Année"  style="width : 80px;">';
    formation += '<input type="text" id="institut_nouvelle_formation" class="span3" placeholder="Institut"  style="width : 80px;">';
    formation += '<input type="text" id="ville_nouvelle_formation" class="span3" placeholder="Ville" style="width : 150px;">';
    formation += '<a href="javascript:Ajouter_Formation(\'\',\'\',\'\',\'\',\'\')" class="icon-ok" style="margin-left : 20px;"></a>';
    formation += '</div>';
    formation += '</div><hr>';
    $('#div_nouvelle_Formation').append(formation);
    $("#debut_nouvelle_formation").datepicker();
    $("#fin_nouvelle_formation").datepicker();



    //Ajout des champs pour ajouter un nouveau diplome
    /* (str) */var diplome = "";
    diplome += '<div class="control-group" id="nouveau_diplome">';
    diplome += '<label class="control-label">Formation</label>';
    diplome += '<div class="controls">';
    diplome += '<input type="text" id="libelle_diplome" class="span3" placeholder="Nom du diplome"  style="width : 120px;">';
    diplome += Creer_Select('sel_mention_nouveau_diplome',-1,liste_mention);
    diplome += '<input type="text" id="annee_nouveau_diplome" class="span3" placeholder="Année"  style="width : 80px;">';
    diplome += '<input type="text" id="institut_nouveau_diplome" class="span3" placeholder="Institut"  style="width : 80px;">';
    diplome += '<input type="text" id="ville_nouveau_diplome" class="span3" placeholder="Ville"  style="width : 150px;">';
    diplome += '<a href="javascript:Ajouter_Diplome(\'\',\'\',\'\',\'\',\'\')" class="icon-ok" style="margin-left : 20px;"></a>';
    diplome += '</div>';
    diplome += '</div><hr>';
    $('#div_nouveau_Diplome').append(diplome);

    //Ajout du date picker pour la date d'anniversaire
    $("#anniv_etudiant").datepicker();


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
//fonction permetant l'ajout d'un diplome
function Ajouter_Diplome(_annee,_id_mention,_libelle,_institut,_ville){
    if (_annee == '' && _id_mention == '' && _libelle=='' && _institut == '' && _ville == ''){
        _annee = $("#annee_nouveau_diplome").val();
        _id_mention = $("#sel_mention_nouveau_diplome").val();
        _libelle = $("#libelle_diplome").val();
        _institut = $("#institut_nouveau_diplome").val();
        _ville = $("#ville_nouveau_diplome").val();
    }

    /* (str) */var diplome = "";
    diplome += '<div class="control-group" id="diplome'+nb_diplome+'">';
    diplome += '<label class="control-label">Formation</label>';
    diplome += '<div class="controls">';
    diplome += '<input type="text" id="libelle_diplome'+nb_diplome+'" class="span3" placeholder="Nom du diplome" value="'+_libelle+'" style="width : 120px;">';
    diplome += Creer_Select('sel_mention_diplome'+nb_diplome,_id_mention,liste_mention);
    diplome += '<input type="text" id="annee_diplome'+nb_diplome+'" class="span3" placeholder="Année" value="'+_annee+'" style="width : 80px;">';
    diplome += '<input type="text" id="institut_diplome'+nb_diplome+'" class="span3" placeholder="Institut" value="'+_institut+'" style="width : 80px;">';
    diplome += '<input type="text" id="ville_diplome'+nb_diplome+'" class="span3" placeholder="Ville" value="'+_ville+'" style="width : 150px;">';
    diplome += '<a href="javascript:Supprimer_Diplome('+nb_diplome+');" class="icon-remove" style="margin-left : 20px;"></a>';
    diplome += '</div>';
    diplome += '</div>';
    $('#div_ancien_Diplome').append(diplome);
    nb_diplome++;
}

//fonction permetant la suppression d'un diplome
function Supprimer_Diplome(_id_diplome){
    annuler_diplome['libelle'] = $('#libelle_diplome'+_id_diplome).val();
    annuler_diplome['id_mention'] = $('#sel_mention_diplome'+_id_diplome).val();
    annuler_diplome['annee'] = $('#annee_diplome'+_id_diplome).val();
    annuler_diplome['institut'] = $('#institut_diplome'+_id_diplome).val();
    annuler_diplome['ville'] = $('#ville_diplome'+_id_diplome).val();
    $('#btn_annuler_diplome').show();
    $('#diplome'+_id_diplome).remove();
}

//Fonction permetant un retour en arriere en cas d'erreur
function Annuler_diplome(){
    Ajouter_Diplome(annuler_diplome['annee'],annuler_diplome['id_mention'],annuler_diplome['libelle'],annuler_diplome['institut'],annuler_diplome['ville']);
    $('#btn_annuler_diplome').hide();
}

//fonction permetant l'ajout d'une formation
function Ajouter_Formation(_debut,_fin,_institut,_ville,_annee){
    if (_debut == '' && _fin == '' && _institut=='' && _ville == '' && _annee == ''){
        _debut = $("#debut_nouvelle_formation").val();
        _fin = $("#fin_nouvelle_formation").val();
        _institut = $("#institut_nouvelle_formation").val();
        _ville = $("#ville_nouvelle_formation").val();
        _annee = $("#annee_nouvelle_formation").val();
    }

    /* (str) */var formation = "";
    formation += '<div class="control-group" id="formation'+nb_formation+'">';
    formation += '<label class="control-label">Formation</label>';
    formation += '<div class="controls">';
    formation += '<input type="text" id="debut_formation'+nb_formation+'" class="span3" placeholder="Début" value="'+_debut+'" style="width : 80px;">';
    formation += '<input type="text" id="fin_formation'+nb_formation+'" class="span3" placeholder="Fin" value="'+_fin+'" style="width : 80px;">';
    formation += '<input type="text" id="annee_formation'+nb_formation+'" class="span3" placeholder="Année" value="'+_annee+'" style="width : 80px;">';
    formation += '<input type="text" id="institut_formation'+nb_formation+'" class="span3" placeholder="Institut" value="'+_institut+'" style="width : 80px;">';
    formation += '<input type="text" id="ville_formation'+nb_formation+'" class="span3" placeholder="Ville" value="'+_ville+'" style="width : 150px;">';
    formation += '<a href="javascript:Supprimer_formation('+nb_formation+');" class="icon-remove" style="margin-left : 20px;"></a>';
    formation += '</div>';
    formation += '</div>';
    $('#div_ancienne_Formation').append(formation);
    
    $("#debut_formation"+nb_formation).datepicker();
    $("#fin_formation"+nb_formation).datepicker();
     
    nb_formation++;
}

//fonction permetant la suppression d'une formation
function Supprimer_formation(_id_formation){
    annuler_formation['debut'] = $('#debut_formation'+_id_formation).val();
    annuler_formation['fin'] = $('#fin_formation'+_id_formation).val();
    annuler_formation['institut'] = $('#institut_formation'+_id_formation).val();
    annuler_formation['ville'] = $('#ville_formation'+_id_formation).val();
    annuler_formation['annee'] = $('#annee_formation'+_id_formation).val();
    $('#btn_annuler_formation').show();
    $('#formation'+_id_formation).remove();
}


//Fonction permetant un retour en arriere en cas d'erreur
function Annuler_formation(){
    Ajouter_Formation(annuler_formation['debut'],annuler_formation['fin'],annuler_formation['institut'],annuler_formation['ville'],annuler_formation['ville']);
    $('#btn_annuler_formation').hide();
}


//fonction permetant l'ajout d'une langue
function Ajouter_Langue(_id_langue_etudiant,_id_niveau,_id_certif,_score_certif){
    if (_id_langue_etudiant == '' && _id_niveau == '' && _id_certif=='' && _score_certif == ''){
        _id_langue_etudiant = $("#sel_nouvelle_langue").val();
        _id_niveau = $("#sel_nouvelle_niveau").val();
        _id_certif = $("#sel_nouvelle_certif").val();
        _score_certif = $("#score_nouvelle").val();
    }

    /* (str) */var langue = "";
    langue += '<div class="control-group" id="langue'+nb_langue+'">';
    langue += '<label class="control-label">Langue</label>';
    langue += '<div class="controls">';
    langue += Creer_Select('sel_langue'+nb_langue,_id_langue_etudiant,liste_langue);
    langue += Creer_Select('sel_niveau'+nb_langue,_id_niveau,liste_niveau);
    langue += Creer_Select('sel_certif'+nb_langue,_id_certif,liste_certif);
    if (_id_certif == '1'){
        langue += '<input type="text" id="score'+nb_langue+'" class="span3" placeholder="Score" value="'+_score_certif+'" style="width : 80px;" disabled>';
    }else{
        langue += '<input type="text" id="score'+nb_langue+'" class="span3" placeholder="Score" value="'+_score_certif+'" style="width : 80px;">';
    }
   
    langue += '<a href="javascript:Supprimer_langue('+nb_langue+');" class="icon-remove" style="margin-left : 20px;"></a>';
    langue += '</div>';
    langue += '</div>';
    $('#div_ancienne_langue').append(langue);
    
    $('#sel_certif'+nb_langue).change(function(j) {
        return function() {
            if (this.options[this.selectedIndex].value == 1){
                $('#score'+j).attr('disabled', true);
            }else{
                $('#score'+j).prop('disabled', false);
            }
        };
    }(nb_langue));
    
    nb_langue++;
}

//fonction permetant la suppression d'une langue
function Supprimer_langue(_id_langue){
    annuler_langue['id_langue'] = $('#sel_langue'+_id_langue).val();
    annuler_langue['id_niveau'] = $('#sel_niveau'+_id_langue).val();
    annuler_langue['id_certif'] = $('#sel_certif'+_id_langue).val();
    annuler_langue['score_certif'] = $('#score'+_id_langue).val();
    $('#btn_annuler_langue').show();
    $('#langue'+_id_langue).remove();
}


//Fonction permetant un retour en arriere en cas d'erreur
function Annuler_langue(){
    Ajouter_Langue(annuler_langue['id_langue'],annuler_langue['id_niveau'],annuler_langue['id_certif'],annuler_langue['score_certif']);
    $('#btn_annuler_langue').hide();
}


//fonction permetant l'ajout d'une experience
function Ajouter_XP(_debut,_fin,_titre,_desc,_entreprise,_ville){ 
    if (_debut == '' && _fin == '' && _titre=='' && _desc == '' && _entreprise == '' && _ville == '' ){
        _debut = $("#debut_nouvelle_xp").val();
        _fin = $("#fin_nouvelle_xp").val();
        _titre = $("#titre_nouvelle_xp").val();
        _desc = $("#desc_nouvelle_xp").val();
        _entreprise = $("#entreprise_nouvelle_xp").val();
        _ville = $("#ville_nouvelle_xp").val();
    }

    /* (str) */var xp = "";
    xp += '<div id="xp'+nb_xp+'" class="control-group">';
    xp += '<table cellpadding="8" style="text-align : center;"><tr>';
    xp += '<td><input type="text" id="debut_xp'+nb_xp+'" class="span3" placeholder="Debut" value="'+_debut+'" style="width : 80px;"></td>';
    xp += '<td><input type="text" id="fin_xp'+nb_xp+'" class="span3" placeholder="Fin" value="'+_fin+'" style="width : 80px;>"</td>';
    xp += '<td><input type="text" id="titre_xp'+nb_xp+'" class="span3" placeholder="Titre" value="'+_titre+'" style="width : 300px;"></td>';
    xp += '<td><input type="text" id="entreprise_xp'+nb_xp+'" class="span3" placeholder="Entreprise" value="'+_entreprise+'" style="width : 150px;"></td>';
    xp += '<td><input type="text" id="ville_xp'+nb_xp+'" class="span3" placeholder="Ville" value="'+_ville+'" style="width : 150px;"></td>';
    xp += '<td><a href="javascript:Supprimer_XP('+nb_xp+');" class="icon-remove" style="margin-left : 20px;"></a></td>';
    xp += '<tr><td></td><td></td>';
    xp += '<td COLSPAN=3><textarea rows="4" id="desc_xp'+nb_xp+'" style="width : 660px;" placeholder="Descirption">'+_desc+'</textarea></td>';
    xp += '</tr></table></div>';
    $('#div_ancienne_XP').append(xp);
   
    $( "#debut_xp"+nb_xp ).datepicker();
    $( "#fin_xp"+nb_xp ).datepicker();
    
    nb_xp++;
}

//fonction permetant la suppression d'une langue
function Supprimer_XP(_id_xp){
    annuler_xp['debut'] = $('#debut_xp'+_id_xp).val();
    annuler_xp['fin'] = $('#fin_xp'+_id_xp).val();
    annuler_xp['titre'] = $('#titre_xp'+_id_xp).val();
    annuler_xp['desc'] = $('#titre_xp'+_id_xp).val();
    annuler_xp['entreprise'] = $('#entreprise_xp'+_id_xp).val();
    annuler_xp['ville'] = $('#ville_xp'+_id_xp).val();
    $('#btn_annuler_xp').show();
    $('#xp'+_id_xp).remove();
}


//Fonction permetant un retour en arriere en cas d'erreur
function Annuler_XP(){
    Ajouter_XP(annuler_xp['debut'],annuler_xp['fin'],annuler_xp['titre'],annuler_xp['desc'],annuler_xp['entreprise'],annuler_xp['ville']);
    $('#btn_annuler_xp').hide();
}



//Sauvegarde du CV
function Sauvegarder(){    
    //On verifie d'abord tout les champs

    //Champs de la partie Informations personnelles
    nom_etudiant = $("#nom_etudiant");
    prenom_etudiant = $("#prenom_etudiant");
    telephone_etudiant = $("#telephone_etudiant");
    adresse1_etudiant = $("#adresse1_etudiant");
    ville_etudiant = $("#ville_etudiant");
    cp_etudiant = $("#cp_etudiant");
    pays_etudiant = $("#pays_etudiant");
    nationalite_etudiant = $("#nationalite_etudiant");
    ville_naissance_etudiant = $("#ville_naissance_etudiant");
    cp_naissance_etudiant = $("#cp_naissance_etudiant");
    pays_naissance_etudiant = $("#pays_naissance_etudiant");
    mail_etudiant = $("#mail_etudiant");

    if (!VerifierChamp(nom_etudiant,false,false,false)){
        Afficher_erreur("[Informations personnelles] Le nom étudiant est incorrect");
        return;
    }
    
    if (!VerifierChamp(prenom_etudiant,false,false,false)){
        Afficher_erreur("[Informations personnelles] Le prenom étudiant est incorrect");
        return;
    }
    
    /*if (!VerifierChamp(telephone_etudiant,true,true,true)){
        Afficher_erreur("[Informations personnelles] Le téléphone est incorrect");
        return;
    }*/
    
    if (!VerifierChamp(adresse1_etudiant,false,false,false)){
        Afficher_erreur("[Informations personnelles] L'adresse 1 est incorrect");
        return;
    }
    
    if (!VerifierChamp(ville_etudiant,false,false,false)){
        Afficher_erreur("[Informations personnelles] La ville est incorrect");
        return;
    }
    
    if (!VerifierChamp(cp_etudiant,true,true,true)){
        Afficher_erreur("[Informations personnelles] Le code postale est incorrect");
        return;
    }
    
    if (!VerifierChamp(pays_etudiant,false,false,false)){
        Afficher_erreur("[Informations personnelles] Le pays est incorrect");
        return;
    }
    
    /*if (!VerifierChamp(nationalite_etudiant,false,false,false)){
        Afficher_erreur("[Informations personnelles] La nationnalité est incorrect");
        return;
    }*/
    
    if (!VerifierChamp(ville_naissance_etudiant,false,false,false)){
        Afficher_erreur("[Informations personnelles] La ville de naissance est incorrect");
        return;
    }
    
    if (!VerifierChamp(cp_naissance_etudiant,true,true,true)){
        Afficher_erreur("[Informations personnelles] Le code postale de naissance est incorrect");
        return;
    }
    
    if (!VerifierChamp(pays_naissance_etudiant,false,false,false)){
        Afficher_erreur("[Informations personnelles] Le pays de naissance est incorrect");
        return;
    }
    
    if(verifMail(mail_etudiant)){
        mail_etudiant.parent().parent().parent().removeClass('error');
        mail_etudiant.parent().parent().parent().addClass('success');
    }else{
        mail_etudiant.parent().parent().parent().removeClass('success');
        mail_etudiant.parent().parent().parent().addClass('error');
        Afficher_erreur("[Informations personnelles] L'adresse mail est incorrect");
        return;
    }
 
    //Champs de la partie Expériences professionnelles (on en profite pour tout mettre dans un tableau)
    var liste_experience_etudiant = new Array();
    j = 0;
    for (i=0;i<nb_xp;i++){
        if ($('#xp'+i).length > 0){
            debut_xp = $('#debut_xp'+i);
            fin_xp = $('#fin_xp'+i);
            titre_xp = $('#titre_xp'+i);
            entreprise_xp = $('#entreprise_xp'+i);
            desc_xp = $('#desc_xp'+i);
            ville_xp = $('#ville_xp'+i);

            if (titre_xp.val() != ''){
                titre_xp.parent().parent().parent().parent().parent().removeClass('error');
                titre_xp.parent().parent().parent().parent().parent().addClass('success');
               
            }else{
                titre_xp.parent().parent().parent().parent().parent().removeClass('success');
                titre_xp.parent().parent().parent().parent().parent().addClass('error');
                Afficher_erreur("[Expériences professionnelles] La titre de l'expérience est incorrect");
                return;
            }
            
            if (entreprise_xp.val() != ''){
                entreprise_xp.parent().parent().parent().parent().parent().removeClass('error');
                entreprise_xp.parent().parent().parent().parent().parent().addClass('success');
               
            }else{
                entreprise_xp.parent().parent().parent().parent().parent().removeClass('success');
                entreprise_xp.parent().parent().parent().parent().parent().addClass('error');
                Afficher_erreur("[Expériences professionnelles] L'entreprise de l'expérience est incorrect");
                return;
            }
            
            if (ville_xp.val() != ''){
                ville_xp.parent().parent().parent().parent().parent().removeClass('error');
                ville_xp.parent().parent().parent().parent().parent().addClass('success');
               
            }else{
                ville_xp.parent().parent().parent().parent().parent().removeClass('success');
                ville_xp.parent().parent().parent().parent().parent().addClass('error');
                Afficher_erreur("[Expériences professionnelles] La ville de l'expérience est incorrect");
                return;
            }
            
            liste_experience_etudiant[j] = new Array(debut_xp.val(),fin_xp.val(),titre_xp.val(),desc_xp.val(),entreprise_xp.val(),ville_xp.val());
            j++;
        }
    }
    
    //Champs de la partie Diplome(s) (on en profite pour tout mettre dans un tableau)
    var liste_diplome_etudiant = new Array();
    j = 0;
    for (i=0;i<nb_diplome;i++){
        if ($('#diplome'+i).length > 0){
            libelle_diplome = $('#libelle_diplome'+i);
            id_mention_diplome = $('#sel_mention_diplome'+i);
            annee_diplome = $('#annee_diplome'+i);
            institut_diplome = $('#institut_diplome'+i);
            ville_diplome = $('#ville_diplome'+i);
            
            if (!VerifierChamp(libelle_diplome,false,false,false)){
                Afficher_erreur("[Diplome(s)] La nom du diplome est incorrect");
                return;
            }
            
            if (!VerifierChamp(annee_diplome,true,true,true)){
                Afficher_erreur("[Diplome(s)] L'année du diplome est incorrect");
                return;
            }
            
            if (!VerifierChamp(institut_diplome,false,false,false)){
                Afficher_erreur("[Diplome(s)] L'institut du diplome est incorrect");
                return;
            }
            
            if (!VerifierChamp(ville_diplome,false,false,false)){
                Afficher_erreur("[Diplome(s)] La ville du diplome est incorrect");
                return;
            }
          
            liste_diplome_etudiant[j] = new Array(annee_diplome.val(),id_mention_diplome.val(),libelle_diplome.val(),institut_diplome.val(),ville_diplome.val());
            j++;
        }
    }
    
    //Champs de la partie Formation (on en profite pour tout mettre dans un tableau)
    var liste_formation_etudiant = new Array();
    j = 0;
    for (i=0;i<nb_formation;i++){
        if ($('#formation'+i).length > 0){   
            debut_formation = $('#debut_formation'+i);
            fin_formation = $('#fin_formation'+i);
            annee_formation = $('#annee_formation'+i);
            institut_formation = $('#institut_formation'+i);
            ville_formation = $('#ville_formation'+i);
            
            if (!VerifierChamp(institut_formation,false,false,false)){
                Afficher_erreur("[Formation] L'institut de formation est incorrect");
                return;
            }

            if (!VerifierChamp(ville_formation,false,false,false)){
                Afficher_erreur("[Formation] La de formation est incorrect");
                return;
            }
            
            if (!VerifierChamp(annee_formation,false,false,false)){
                Afficher_erreur("[Formation] L'année de formation est incorrect");
                return;
            }  
          
            liste_formation_etudiant[j] = new Array(debut_formation.val(),fin_formation.val(),institut_formation.val(),ville_formation.val(),annee_formation.val());
            j++;
        }
    }
    
    
    //Champs de la partie Langue (on en profite pour tout mettre dans un tableau)
    var liste_langue_etudiant = new Array();
    j = 0;
    for (i=0;i<nb_langue;i++){
        if ($('#langue'+i).length > 0){   
            id_langue = $('#sel_langue'+i);
            id_niveau = $('#sel_niveau'+i);
            id_certif = $('#sel_certif'+i);
            score = $('#score'+i);

            if (id_certif.val()!=1 && !VerifierChamp(score,true,true,true)){
                Afficher_erreur("[Langue] Le score de la langue est incorrect");
                return;
            }  
            
            for(k=0;k<liste_certif.length;k++){
                
                if (liste_certif[k]['id'] == id_certif.val()){
                    if (parseInt(score.val())>parseInt(liste_certif[k]['score_max'])){
                        score.parent().parent().removeClass('success');
                        score.parent().parent().addClass('error');
                        Afficher_erreur("[Langue] Le score de la langue est incorrect <= "+liste_certif[k]['score_max']);
                        return;
                    }
                }
            }

          
            liste_langue_etudiant[j] = new Array(id_langue.val(),id_niveau.val(),id_certif.val(),score.val());
            j++;
        }
    }


    //On transforme les array en json pour les passer au php
    liste_experience_json = JSON.stringify(liste_experience_etudiant);
    liste_diplome_json = JSON.stringify(liste_diplome_etudiant);
    liste_formation_json = JSON.stringify(liste_formation_etudiant);
    liste_langue_json = JSON.stringify(liste_langue_etudiant);
    
    
    //On récupere les champs qui reste et qui n'ont pas à etre verifiés
    adresse2_etudiant = $("#adresse2_etudiant");
    statut_marital_etudiant = $("#sel_statut_marital");
    permis_etudiant = $("#sel_permis");
    sexe_etudiant = $("#sel_sexe");
    loisir_etudiant = $("#loisir_etudiant");
    mobilite_etudiant = $("#sel_mobilite");
    anniv_etudiant = $("#anniv_etudiant");
    titre_cv = $("#titre_cv");
    
    
    
    $.post("/cvtheque/ajax/cv.cible.php?action=edit_cv", {
        id_etudiant : id_etudiant,
        nom_etudiant : nom_etudiant.val(),
        prenom_etudiant : prenom_etudiant.val(),
        telephone_etudiant : telephone_etudiant.val(),
        adresse1_etudiant : adresse1_etudiant.val(),
        ville_etudiant : ville_etudiant.val(),
        cp_etudiant : cp_etudiant.val(),
        pays_etudiant : pays_etudiant.val(),
        anniv_etudiant : anniv_etudiant.val(),
        nationalite_etudiant : nationalite_etudiant.val(),
        ville_naissance_etudiant : ville_naissance_etudiant.val(),
        cp_naissance_etudiant : cp_naissance_etudiant.val(),
        pays_naissance_etudiant : pays_naissance_etudiant.val(),
        mail_etudiant : mail_etudiant.val(),
        adresse2_etudiant : adresse2_etudiant.val(),
        statut_marital_etudiant : statut_marital_etudiant.val(),
        permis_etudiant : permis_etudiant.val(),
        sexe_etudiant : sexe_etudiant.val(),
        loisir_etudiant : loisir_etudiant.val(),
        mobilite_etudiant : mobilite_etudiant.val(),
        titre_cv : titre_cv.val(),
        liste_experience : liste_experience_json,
        liste_diplome : liste_diplome_json,
        liste_formation : liste_formation_json,
        liste_langue : liste_langue_json
        
    },function success(retour){
        retour = $.trim(retour)
        if (retour == "1"){
            div_info = $("#div_info");
            $("#text_info").text("La sauvegarde c'est bien passé");
            div_info.addClass("alert-success");
            div_info.removeClass("alert-error");            
        }else{
            Afficher_erreur(retour);
        }
    });
}

//Fonction qui verifie un champs et le met en erreur si il le faut
function VerifierChamp(_element,_est_un_nombre,_positif,_positif_strict){
    valide= true;
    if (_est_un_nombre && _positif && _positif_strict){
        if (_element.val() == '' || isNaN(_element.val()) || _element.val()<=0){
            valide= false;
        }
    }else if (_est_un_nombre && _positif){
        if (_element.val() == '' || isNaN(_element.val()) || _element.val()<0){
            valide= false;
        }
    }else if (_est_un_nombre){
        if (_element.val() == '' || isNaN(_element.val())){
            valide= false;
        }
    }else{
        if (_element.val() == ''){
            valide= false;
        }
    }
  
    if(valide){
        _element.parent().parent().removeClass('error');
        _element.parent().parent().addClass('success');
    }else{
        _element.parent().parent().removeClass('success');
        _element.parent().parent().addClass('error');
    }
    return valide;
}

//Fonction permettant d'afficher des details sur l'erreur
function Afficher_erreur(erreur){
    div_info = $("#div_info");
    $("#text_info").text(erreur);
    div_info.removeClass("alert-success");
    div_info.addClass("alert-error");
    return;
}

//Fonction qui verifie que l'adresse mail est bien formatée
function verifMail(champ){
    var regex = /^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
    if(!regex.test(champ.val()))
    {
        return false;
    }
    return true;
}