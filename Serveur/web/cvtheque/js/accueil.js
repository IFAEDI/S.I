/*
 * @author Loïc Gevrey
 *
 *
 */


//Fonction qui change l'état de diffusion du CV
function Diffusion(_etat){
    $.post("/cvtheque/ajax/cv.cible.php?action=diffusion_cv", {
        etat : _etat
    },function success(retour){
        retour = $.trim(retour)
        retour_decode = $.parseJSON(retour);
        if (retour_decode['code'] != 'ok'){
            Afficher_erreur(retour);
        }else{
            location.reload(); 
        }
    });
}


function Supprimer_CV(){
    $.post("/cvtheque/ajax/cv.cible.php?action=supprimer_cv", {

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









//Fonction permettant d'afficher les erreurs
function Afficher_erreur(erreur){
    div_erreur = $("#div_erreur");
    div_erreur.text(erreur);
    if (!div_erreur.is(':visible')) {
        div_erreur.show('blind');
    }
    return;
}