<?php
require_once dirname(__FILE__) . '/global/php/base.inc.php';

/************************Tant que la classe utilisateur n'est pas fini************/
session_start();
$_SESSION['utilisateur'] = new Utilisateur();
$_SESSION['utilisateur'] = Utilisateur::GetUtilisateur(0);

 /********************************************************************************/



if (!isset($_GET['page'])){
     header("location: index.php?page=home");
}


if ($_GET['page'] == 'home') {
    $titre_page = 'Accueil';
    $lien_module = '';
    $nom_module = '';
    $nom_page = 'Accueil';
} elseif ($_GET['page'] == 'accueil_cv') {
    $titre_page = 'Accueil CV';
    $lien_module = 'index.php?page=accueil_cv';
    $nom_module = 'cvtheque';
    $nom_page = 'accueil';
} else {
    $titre_page = '404';
    $lien_module = '';
    $nom_module = '';
    $nom_page = '404';
}

inclure_fichier('', 'layout', 'php');
?>
