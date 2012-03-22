//****************  Variables Global  ******************//

/* (int) */var nb_langue = 0;
/* (int) */var nb_xp = 0;
/* (int) */var nb_formation = 0;
/* (int) */var nb_diplome = 0;




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
    langue += '<input type="text" id="score_nouvelle" class="span3" placeholder="Score" style="width : 80px;">';
    langue += '<a href="javascript:Ajouter_Langue(\'\',\'\',\'\',\'\')" class="icon-ok" style="margin-left : 20px;"></a>';
    langue += '</div>';
    langue += '</div><hr>';
    $('#div_nouvelle_langue').append(langue);

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
    diplome += '<a href="javascript:$(\'#diplome'+nb_diplome+'\').remove()" class="icon-remove" style="margin-left : 20px;"></a>';
    diplome += '</div>';
    diplome += '</div>';
    $('#div_ancien_Diplome').append(diplome);
    nb_diplome++;
}

//fonction permetant l'ajout d'une formation
function Ajouter_Formation(_debut,_fin,_institut,_ville,_annee){
    var debut = _debut.substring(0,2)+"/"+_debut.substring(2,4)+"/"+_debut.substring(4,8);
    var fin = _fin.substring(0,2)+"/"+_fin.substring(2,4)+"/"+_fin.substring(4,8);
    
    if (_debut == '' && _fin == '' && _institut=='' && _ville == '' && _annee == ''){
        debut = $("#debut_nouvelle_formation").val();
        fin = $("#fin_nouvelle_formation").val();
        _institut = $("#institut_nouvelle_formation").val();
        _ville = $("#ville_nouvelle_formation").val();
        _annee = $("#annee_nouvelle_formation").val();
    }

    /* (str) */var formation = "";
    formation += '<div class="control-group" id="formation'+nb_formation+'">';
    formation += '<label class="control-label">Formation</label>';
    formation += '<div class="controls">';
    formation += '<input type="text" id="debut_formation'+nb_formation+'" class="span3" placeholder="Début" value="'+debut+'" style="width : 80px;">';
    formation += '<input type="text" id="fin_formation'+nb_formation+'" class="span3" placeholder="Fin" value="'+fin+'" style="width : 80px;">';
    formation += '<input type="text" id="annee_formation'+nb_formation+'" class="span3" placeholder="Année" value="'+_annee+'" style="width : 80px;">';
    formation += '<input type="text" id="institut_formation'+nb_formation+'" class="span3" placeholder="Institut" value="'+_institut+'" style="width : 80px;">';
    formation += '<input type="text" id="ville_formation'+nb_formation+'" class="span3" placeholder="Ville" value="'+_ville+'" style="width : 150px;">';
    formation += '<a href="javascript:$(\'#formation'+nb_formation+'\').remove()" class="icon-remove" style="margin-left : 20px;"></a>';
    formation += '</div>';
    formation += '</div>';
    $('#div_ancienne_Formation').append(formation);
    
    $("#debut_formation"+nb_formation).datepicker();
    $("#fin_formation"+nb_formation).datepicker();
     
    nb_formation++;
}

//fonction permetant la suppression d'une langue
function Supprimer_Formation(_id_formation){
    $('#formation'+_id_formation).remove();
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
    langue += '<input type="text" id="score'+nb_langue+'" class="span3" placeholder="Score" value="'+_score_certif+'" style="width : 80px;">';
    langue += '<a href="javascript:$(\'#langue'+nb_langue+'\').remove()" class="icon-remove" style="margin-left : 20px;"></a>';
    langue += '</div>';
    langue += '</div>';
    $('#div_ancienne_langue').append(langue);
    nb_langue++;
}


//fonction permetant l'ajout d'une experience
function Ajouter_XP(_debut,_fin,_titre,_desc,_entreprise,_ville){ 
    var debut = _debut.substring(0,2)+"/"+_debut.substring(2,4)+"/"+_debut.substring(4,8);
    var fin = _fin.substring(0,2)+"/"+_fin.substring(2,4)+"/"+_fin.substring(4,8);
    
    if (_debut == '' && _fin == '' && _titre=='' && _desc == '' && _entreprise == '' && _ville == '' ){
        debut = $("#debut_nouvelle_xp").val();
        fin = $("#fin_nouvelle_xp").val();
        _titre = $("#titre_nouvelle_xp").val();
        _desc = $("#desc_nouvelle_xp").val();
        _entreprise = $("#entreprise_nouvelle_xp").val();
        _ville = $("#ville_nouvelle_xp").val();
    }

    /* (str) */var xp = "";
    xp += '<div id="xp'+nb_xp+'" class="control-group">';
    xp += '<table cellpadding="8" style="text-align : center;"><tr>';
    xp += '<td><input type="text" id="debut_xp'+nb_xp+'" class="span3" placeholder="Debut" value="'+debut+'" style="width : 80px;"></td>';
    xp += '<td><input type="text" id="fin_xp'+nb_xp+'" class="span3" placeholder="Fin" value="'+fin+'" style="width : 80px;>"</td>';
    xp += '<td><input type="text" id="titre_xp'+nb_xp+'" class="span3" placeholder="Titre" value="'+_titre+'" style="width : 300px;"></td>';
    xp += '<td><input type="text" id="entreprise_xp'+nb_xp+'" class="span3" placeholder="Entreprise" value="'+_entreprise+'" style="width : 150px;"></td>';
    xp += '<td><input type="text" id="ville_xp'+nb_xp+'" class="span3" placeholder="Ville" value="'+_ville+'" style="width : 150px;"></td>';
    xp += '<td><a href="javascript:$(\'#xp'+nb_xp+'\').remove()" class="icon-remove" style="margin-left : 20px;"></a></td>';
    xp += '<tr><td></td><td></td>';
    xp += '<td COLSPAN=3><textarea rows="4" id="desc_xp'+nb_xp+'" style="width : 660px;" placeholder="Descirption">'+_desc+'</textarea></td>';
    xp += '</tr></table></div>';
    $('#div_ancienne_XP').append(xp);
   
    $( "#debut_xp"+nb_xp ).datepicker();
    $( "#fin_xp"+nb_xp ).datepicker();
    
    nb_xp++;
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
    
    if (!VerifierChamp(telephone_etudiant,true,true,true)){
        Afficher_erreur("[Informations personnelles] Le téléphone est incorrect");
        return;
    }
    
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
    
    if (!VerifierChamp(nationalite_etudiant,false,false,false)){
        Afficher_erreur("[Informations personnelles] La nationnalité est incorrect");
        return;
    }
    
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
    var liste_experience = new Array;
    j = 0;
    for (i=0;i<nb_xp;i++){
        if ($('#xp'+i).length > 0){
            debut_xp = $('#debut_xp'+i);
            fin_xp = $('#fin_xp'+i);
            titre_xp = $('#titre_xp'+i);
            entreprise_xp = $('#entreprise_xp'+i);
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
            
            liste_experience[j] = new Array;
            liste_experience[j]['debut'] = debut_xp.val();
            liste_experience[j]['fin'] = fin_xp.val();
            liste_experience[j]['titre'] = titre_xp.val();
            liste_experience[j]['entreprise'] = entreprise_xp.val();
            liste_experience[j]['ville'] = ville_xp.val();
            j++;
        }
    }
    
    //Champs de la partie Diplome(s) (on en profite pour tout mettre dans un tableau)
    var liste_diplome = new Array;
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
          
            liste_diplome[j] = new Array;
            liste_diplome[j]['libelle'] = libelle_diplome.val();
            liste_diplome[j]['id_mention'] = id_mention_diplome.val();
            liste_diplome[j]['annee'] = annee_diplome.val();
            liste_diplome[j]['institut'] = institut_diplome.val();
            liste_diplome[j]['ville'] = ville_diplome.val();
            j++;
        }
    }
    
    //Champs de la partie Formation (on en profite pour tout mettre dans un tableau)
    var liste_formation = new Array;
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
          
            liste_formation[j] = new Array;
            liste_formation[j]['debut'] = debut_formation.val();
            liste_formation[j]['fin'] = fin_formation.val();
            liste_formation[j]['annee'] = annee_formation.val();
            liste_formation[j]['institut'] = institut_formation.val();
            liste_formation[j]['ville'] = ville_formation.val();
            j++;
        }
    }
    
    
    //Champs de la partie Langue (on en profite pour tout mettre dans un tableau)
    var liste_langue = new Array;
    j = 0;
    for (i=0;i<nb_langue;i++){
        if ($('#langue'+i).length > 0){   
            id_langue = $('#sel_langue'+i);
            id_niveau = $('#sel_niveau'+i);
            id_certif = $('#sel_certif'+i);
            score = $('#score'+i);

            if (!VerifierChamp(score,true,true,true)){
                Afficher_erreur("[Langue] Le score de la langue est incorrect");
                return;
            }  
          
            liste_langue[j] = new Array;
            liste_langue[j]['id_langue'] = id_langue.val();
            liste_langue[j]['id_niveau'] = id_niveau.val();
            liste_langue[j]['id_certif'] = id_certif.val();
            liste_langue[j]['score'] = score.val();
            j++;
        }
    }
    

    
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
    div_erreur = $("#div_erreur");
    div_erreur.text(erreur);
    
    if (!div_erreur.is(':visible')) {
        div_erreur.show('blind');
    }
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