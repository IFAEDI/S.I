<?php
require_once dirname(__FILE__) . '/commun/php/base.inc.php';
session_start();
//Definition du theme
if (isset($_POST['theme']) && is_numeric($_POST['theme'])){
    $_SESSION['theme'] = $_POST['theme'];
}

if (isset($_SESSION['theme'])) {
    $theme = $_SESSION['theme'];
}else{
    $theme = 0;
}


/************************Tant que la classe utilisateur n'est pas fini************/

$_SESSION['utilisateur'] = new Utilisateur();
$_SESSION['utilisateur'] = Utilisateur::GetUtilisateur(0);
 /********************************************************************************/



if (!isset($_GET['page'])){
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
} else {
    $titre_page = '404';
    $lien_module = '';
    $nom_module = '';
    $nom_page = '404';
}

inclure_fichier('', 'layout', 'php');

?>
