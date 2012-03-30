



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

function Afficher_erreur(erreur){
    div_erreur = $("#div_erreur");
    div_erreur.text(erreur);
    if (!div_erreur.is(':visible')) {
        div_erreur.show('blind');
    }
    return;
}