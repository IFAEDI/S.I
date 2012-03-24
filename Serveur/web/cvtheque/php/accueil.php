<?php
if (!Utilisateur_connecter('etudiant')) {
    inclure_fichier('', '401', 'php');
    die;
}

inclure_fichier('controleur', 'etudiant.class', 'php');
inclure_fichier('cvtheque', 'accueil', 'js');

$etudiant = new Etudiant();
$etudiant = Etudiant::GetEtudiantByID($_SESSION['utilisateur']->getId());
?>
<div class="alert alert-error" id="div_erreur" style="display: none;"></div>

<?php if ($etudiant == null) { ?>
    <div class='alert alert-error'>Oooooooh mais tu n'as pas de CV comme c'est dommage : 
        <a class="btn" href="index.php?page=edit_cv">Creer mon CV</a></div>
    <?php
} else {
    $cv = $etudiant->getCV();
    if ($cv->getAgreement() == 1) {
        echo "<div class='alert alert-success'><table style='width : 100%;'><tr>";
        echo "<td><a class='btn' data-toggle='modal' href='#mod_supression' style='margin-right : 10px;'>Supprimer mon CV</a>";
        echo "<a class='btn' href=\"index.php?page=edit_cv\">Editer mon CV</a></td>";
        echo "<td style='text-align : right;'>Ton CV est actuellement diffusé : <a href='javascript:Diffusion(0);'>Arrêter sa diffusion</a></td>";
        echo "</tr></table></div>";
    } else {
        echo "<div class='alert alert-error'><table style='width : 100%;'><tr>";
        echo "<td><a class='btn' data-toggle='modal' href='#mod_supression' style='margin-right : 10px;'>Supprimer mon CV</a>";
        echo "<a class='btn' href=\"index.php?page=edit_cv\">Editer mon CV</a></td>";
        echo "<td style='text-align : right;'>Ton CV n'est pas diffusé actuellement : <a href='javascript:Diffusion(1);'>Autoriser sa diffusion</a></td>";
        echo "</tr></table></div>";
    }
}
?>
<div >
  <?php
  inclure_fichier("cvtheque", "cv", "php");
  
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