<?php
require_once dirname(__FILE__) . '/global/php/base.inc.php';

if (isset($_GET['page']) && $_GET['p'] == 'h') {
    $titre_page = 'Accueil';
    $lien_module = '';
    $nom_module = '';
    $nom_page = 'Accueil';
} elseif (isset($_GET['page']) && $_GET['p'] == 'ar') {
    
} else {
    $titre_page = 'Accueil';
    $lien_module = '';
    $nom_module = '';
    $nom_page = 'Accueil';
}

inclure_fichier('', 'layout', 'php');
?>
