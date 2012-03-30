<?php

require_once dirname(__FILE__) . '/commun/php/base.inc.php';

inclure_fichier('commun', 'authentification.class', 'php');

/* * *********************************** AUTHENTIFICATION ********************************** */

$authentification = new Authentification();
$utilisateur = null;

/* Si on reçoit une demande pour le CAS */
if (isset($_REQUEST['action'])) {
    if ($_POST['action'] == "login_cas") {
        $authentification->authentificationCAS();
    } else if ($_GET['action'] == "logout") {
        $authentification->forcerDeconnexion();
    }
}

/* On regarde si l'utilisateur est authentifié */
if ($authentification->isAuthentifie()) {

    /* On récupère l'objet utilisateur associé */
    $utilisateur = $authentification->getUtilisateur();
    if ($utilisateur == null) {
        $authentification->forcerDeconnexion();
    }
}

/* * ***************************************** THEME *************************************** */

/* Définition du theme */
if (isset($_POST['theme']) && is_numeric($_POST['theme'])) {
    $_SESSION['theme'] = $_POST['theme'];
}

if (isset($_SESSION['theme'])) {
    $theme = $_SESSION['theme'];
} else {
    $theme = 0;
}


/* * ***************************************************************************** */

//Inclusion d'utils des le debut de la page d'index car necessaire pour la cvtheque



if (!isset($_GET['page'])) {
    header("location: index.php?page=accueil");
}


if ($_GET['page'] == 'accueil') {
    $titre_page = 'Accueil';
    $lien_module = '';
    $nom_module = '';
    $titre_module = '';
    $nom_page = 'accueil';
} elseif ($_GET['page'] == 'accueil_cv') {
    $titre_page = 'Accueil CV';
    $lien_module = 'index.php?page=accueil_cv';
    $nom_module = 'cvtheque';
    $nom_page = 'accueil';
    $titre_module = 'Etudiants';
} elseif ($_GET['page'] == 'edit_cv') {
    $titre_page = 'Edition CV';
    $lien_module = 'index.php?page=accueil_cv';
    $nom_module = 'cvtheque';
    $nom_page = 'edit_cv';
    $titre_module = 'Etudiants';
} elseif ($_GET['page'] == 'cvtheque') {
    $titre_page = 'CV';
    $lien_module = 'index.php?page=accueil_cv';
    $nom_module = 'cvtheque';
    $nom_page = 'cvtheque';
    $titre_module = 'Entreprises';
} elseif ($_GET['page'] == 'admincv') {
    $titre_page = 'Administration CV';
    $lien_module = 'index.php?page=admincv';
    $nom_module = 'cvtheque';
    $nom_page = 'admin';
    $titre_module = 'Entreprises';
} elseif ($_GET['page'] == 'annuaire') {
    $titre_page = 'Annuaire';
    $lien_module = 'index.php?page=annuaire';
    $nom_module = 'annuaire';
    $nom_page = 'annuaire';
    $titre_module = 'AEDI';
} elseif ($_GET['page'] == 'inscription') {
    $titre_page = 'Inscription Entretien';
    $lien_module = 'index.php?page=inscription';
    $nom_module = 'entretien';
    $nom_page = 'inscription';
    $titre_module = 'Entreprises';
} elseif ($_GET['page'] == 'entretienEtudiant') {
    $titre_page = 'Entretien';
    $lien_module = 'index.php?page=entretienEtudiant';
    $nom_module = 'entretien';
    $nom_page = 'entretienEtudiant';
    $titre_module = 'Etudiants';
} elseif ($_GET['page'] == 'rifs') {
    $titre_page = 'Rencontres IF';
    $lien_module = 'index.php?page=rifs';
    $nom_module = 'rifs';
    $nom_page = 'rifs';
} elseif ($_GET['page'] == 'inscriptionRIFs') {
    $titre_page = 'Inscription aux Rencontres IF';
    $lien_module = 'index.php?page=inscriptionRIFs';
    $nom_module = 'rifs';
    $nom_page = 'inscription';
} elseif ($_GET['page'] == 'stages') {
    $titre_page = "Recherche de Stages";
    $lien_module = 'index.php?page=stages';
    $nom_module = 'stages';
    $nom_page = 'stages';
} elseif ($_GET['page'] == 'Administration_Utilisateurs') {
    $titre_page = 'Administration des Utilisateurs';
    $lien_module = 'index.php?page=Administration_Utilisateurs';
    $nom_module = 'commun';
    $nom_page = 'admin_utilisateurs';
} elseif ($_GET['page'] == 'Administration_Journal') {
    $titre_page = 'Journal d\'activité';
    $lien_module = 'index.php?page=Administration_Journal';
    $nom_module = 'commun';
    $nom_page = 'admin_journal';
} else {
    $titre_page = '404';
    $lien_module = '';
    $nom_module = '';
    $nom_page = '404';
    $titre_module = '';
}

inclure_fichier('', 'layout', 'php');
?>
