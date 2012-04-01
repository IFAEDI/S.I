<?php
/*
 * @author Loïc Gevrey
 *
 *
 */

global $authentification;
global $utilisateur;

if ($authentification->isAuthentifie() == false || (
        $utilisateur->getPersonne()->getRole() != Personne::ADMIN &&
        $utilisateur->getPersonne()->getRole() != Personne::AEDI &&
        $utilisateur->getPersonne()->getRole() != Personne::ETUDIANT)) {
    inclure_fichier('', '401', 'php');
    die;
}



inclure_fichier('controleur', 'etudiant.class', 'php');
inclure_fichier('cvtheque', 'accueil', 'js');



$id_personne = $utilisateur->getPersonne()->getId();

$etudiant = new Etudiant();
$etudiant = Etudiant::GetEtudiantByID($id_personne);
?>
<div class="alert alert-error" id="div_erreur" style="display: none;"></div>



<?php if ($etudiant == null) { ?>
    <div class='alert alert-error'>Tu n'as pas encore généré ton CV -  
        <a class="btn btn-success" href="index.php?page=edit_cv">Créer mon CV</a></div>
    <?php
} else {
    $cv = $etudiant->getCV();
    if ($cv->getAgreement() == 1) {
        ?>
        <div class="alert alert-success" style="padding: 20px;">
            <table style="width : 100%;">
                <tr>
                    <td>
                        <span>Ton CV est actuellement diffusé : <a href="javascript:Diffusion(0);">Arrêter sa diffusion</a></span><br/>
                        <?php
                        $nb_entreprise_suivi = Etudiant::GetNbSuivi($id_personne);
                        if ($nb_entreprise_suivi > 0) {
                            ?> 
                            <span>Ton CV est actuellement suivi par <?php echo $nb_entreprise_suivi ?> entreprise(s)</span>
                        <?php } ?>
                    </td>
                    <td style="text-align : right;">
                        <a id='imprimer' style='margin-right : 10px;' class='btn' onClick="window.open('/cvtheque/php/cv.php?inc=1','CV','toolbar=no,status=no,scrollbars=yes,location=no,resize=no,menubar=no')"><span class='ui-icon ui-icon-print' style='display: inline-block;height: 13px; margin-right: 5px;'></span>Imprimer</a>
                        <a class="btn btn-danger" data-toggle="modal" href="#mod_supression" style="margin-right : 10px;">Supprimer mon CV</a>
                        <a class="btn btn-primary" href="index.php?page=edit_cv">Editer mon CV</a>
                    </td>
                </tr>
            </table>
        </div>
        <?php
    } else {
        ?>
        <div class="alert alert-error" style="padding: 20px;">
            <table style="width : 100%;">
                <tr>
                    <td>
                        <span>Ton CV n'est toujours pas diffusé : <a href='javascript:Diffusion(1);'>Autoriser sa diffusion</a></span>
                    </td>
                    <td style='text-align : right;'>
                        <a id='imprimer' style='margin-right : 10px;' class='btn' onClick="window.open('/cvtheque/php/cv.php?inc=1','CV','toolbar=no,status=no,scrollbars=yes,location=no,resize=no,menubar=no')"><span class='ui-icon ui-icon-print' style='display: inline-block;height: 13px; margin-right: 5px;'></span>Imprimer</a>
                        <a class='btn btn-danger' data-toggle='modal' href='#mod_supression' style='margin-right : 10px;'>Supprimer mon CV</a>
                        <a class='btn btn-primary' href="index.php?page=edit_cv">Editer mon CV</a>
                    </td>
                </tr>   
            </table>
        </div>
        <?php
    }
}
?>
<div >
    <?php
    if ($etudiant != null) {
        inclure_fichier("cvtheque", "cv", "php");
    }
    ?>

</div>

<div id="mod_supression" class="modal hide fade">
    <div class="modal-header">
        <a class="close" data-dismiss="modal" >&times;</a>
        <h3>Confirmation</h3>
    </div>
    <div class="modal-body">
        Etes-vous sûr de vouloir supprimer votre CV?
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal" >Non</a>
        <a href="javascript:Supprimer_CV();" class="btn btn-primary">Oui</a>
    </div>
</div>
