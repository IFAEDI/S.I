<?php
global $authentification;
global $utilisateur;

if ($authentification->isAuthentifie() == false || $utilisateur->getTypeUtilisateur() != Utilisateur::UTILISATEUR_ADMIN ) {
    inclure_fichier('', '401', 'php');
    die;
}

inclure_fichier('controleur', 'etudiant.class', 'php');
inclure_fichier('cvtheque', 'admin', 'js');

?>

<h1>Bienvenue sur la page d'administration de la cvthque </h1><br/>

<div class="alert alert-error" id="div_erreur" style="display: none;"></div>

<div class="alert alert-success" style="padding: 20px;">
    Il y a actuellement <?php echo Etudiant::GetNbCV(); ?> cv dans la cvtheque </br>
    Il y a actuellement <?php echo Etudiant::GetNbDiffuseCV(); ?> cv diffusé dans la cvtheque <br/>
</div>

<div class="alert" style="padding: 20px;">
    <a class='btn btn-primary' href="javascript:ArreterDiffusion();">Arreter la diffusion de tout les cv</a>
</div>