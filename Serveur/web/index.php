<?php
require_once dirname(__FILE__) . '/global/php/base.inc.php';

if (isset($_GET['page']) && $_GET['p'] == 'h') {
    $titre_page = 'Acceuil';
    $lien_module = '';
    $nom_module = '';
    $nom_page = 'Acceuil';
} elseif (isset($_GET['page']) && $_GET['p'] == 'ar') {
    
} else {
    $titre_page = 'Acceuil';
    $lien_module = '';
    $nom_module = '';
    $nom_page = 'Acceuil';
}

inclure_fichier('', 'layout', 'php');
?>
