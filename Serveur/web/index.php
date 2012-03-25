<?php

require_once dirname(__FILE__) . '/commun/php/base.inc.php';
session_start();
//Definition du theme
if (isset($_POST['theme']) && is_numeric($_POST['theme'])) {
    $_SESSION['theme'] = $_POST['theme'];
}

if (isset($_SESSION['theme'])) {
    $theme = $_SESSION['theme'];
} else {
    $theme = 0;
}


/* * **********************Tant que la classe utilisateur n'est pas fini*********** */

$_SESSION['utilisateur'] = new Utilisateur();
$_SESSION['utilisateur'] = Utilisateur::GetUtilisateur(1);
/* * ***************************************************************************** */

//Inclusion d'utils des le debut de la page d'index car necessaire pour la cvtheque



if (!isset($_GET['page'])) {
    header("location: index.php?page=accueil");
}


if ($_GET['page'] == 'accueil') {
    $titre_page = 'Accueil';
    $lien_module = '';
    $nom_module = '';
    $nom_page = 'Accueil';
} elseif ($_GET['page'] == 'accueil_cv') {
    $titre_page = 'Accueil CV';
    $lien_module = 'index.php?page=accueil_cv';
    $nom_module = 'cvtheque';
    $nom_page = 'accueil';
} elseif ($_GET['page'] == 'edit_cv') {
    $titre_page = 'Edition CV';
    $lien_module = 'index.php?page=accueil_cv';
    $nom_module = 'cvtheque';
    $nom_page = 'edit_cv';
}elseif ($_GET['page'] == 'cvtheque') {
    $titre_page = 'CV';
    $lien_module = 'index.php?page=accueil_cv';
    $nom_module = 'cvtheque';
    $nom_page = 'cvtheque';
} elseif ($_GET['page'] == 'annuaire') {
    $titre_page = 'Annuaire';
    $lien_module = 'index.php?page=annuaire';
    $nom_module = 'annuaire';
    $nom_page = 'annuaire';
} elseif ($_GET['page'] == 'inscription') {
    $titre_page = 'Inscription Entretien';
    $lien_module = 'index.php?page=inscription';
    $nom_module = 'entretien';
    $nom_page = 'inscription';
} elseif ($_GET['page'] == 'rifs') {
    $titre_page = 'Rencontres IF';
    $lien_module = 'index.php?page=rifs';
    $nom_module = 'rifs';
    $nom_page = 'rifs';

} else {
    $titre_page = '404';
    $lien_module = '';
    $nom_module = '';
    $nom_page = '404';
}

inclure_fichier('', 'layout', 'php');
?>
