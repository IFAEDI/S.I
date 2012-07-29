<?php
/*****************************************************************************************************
* Page d'index
* Authentification de l'utilisateur et redirection vers la bonne page en fonction de la variable 'page'
*****************************************************************************************************/


require_once dirname(__FILE__) . '/commun/php/base.inc.php';

inclure_fichier('commun', 'authentification.class', 'php');

/* * *********************************** AUTHENTIFICATION ********************************** */

$authentification = new Authentification();
$utilisateur = null;

/* Si on reçoit une demande pour le CAS */
if (@isset($_REQUEST['action'])) {
    if ($_POST['action'] == "login_cas") {
        $authentification->authentificationCAS();
    } else if ($_GET['action'] == "logout") {
        $authentification->forcerDeconnexion();
	header( 'location: index.php' );
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

/*******************************************************************************/


/* Accueil */
if ( !isset($_GET['page']) || strlen( $_GET['page'] ) == 0 || $_GET['page'] == 'Accueil') {
    $titre_page = 'Accueil';
    $nom_module = '';
    $titre_module = '';
    $nom_page = 'accueil';
/* CV Etudiant */
} elseif ($_GET['page'] == 'CV_Etudiant') {
    $titre_page = 'Accueil CV';
    $nom_module = 'cvtheque';
    $nom_page = 'accueil';
    $titre_module = 'Etudiants';
/* Edition CV */
} elseif ($_GET['page'] == 'edit_cv') {
    $titre_page = 'Edition CV';
    $nom_module = 'cvtheque';
    $nom_page = 'edit_cv';
    $titre_module = 'Etudiants';
/* CV Entreprise */
} elseif ($_GET['page'] == 'CV_Entreprise') {
    $titre_page = 'CV';
    $nom_module = 'cvtheque';
    $nom_page = 'cvtheque';
    $titre_module = 'Entreprises';
/* Administration CV */
} elseif ($_GET['page'] == 'Administration_CV') {
    $titre_page = 'Administration CV';
    $nom_module = 'cvtheque';
    $nom_page = 'admin';
    $titre_module = 'Administration';
/* Administration Annuaire */
} elseif ($_GET['page'] == 'Administration_Annuaire') {
    $titre_page = 'Annuaire';
    $nom_module = 'annuaire';
    $nom_page = 'annuaire';
    $titre_module = 'AEDI';
/* Simulation Entreprise */
} elseif ($_GET['page'] == 'Entretiens_Entreprise') {
    $titre_page = 'Inscription Entretien';
    $nom_module = 'entretien';
    $nom_page = 'inscription';
    $titre_module = 'Entreprises';
/* Simulation Etudiant */
} elseif ($_GET['page'] == 'Entretiens_Etudiant') {
    $titre_page = 'Entretien';
    $nom_module = 'entretien';
    $nom_page = 'entretienEtudiant';
    $titre_module = 'Etudiants';
/* Administration Entretiens */
} elseif ($_GET['page'] == 'Administration_Entretiens') {
    $titre_page = 'Administration Entretien';
    $nom_module = 'entretien';
    $nom_page = 'admin_entretien';
    $titre_module = 'Administration';
/* RIFs Entreprise */
} elseif ($_GET['page'] == 'RIFs_Entreprise') {
    $titre_page = 'Rencontres IF';
    $nom_module = 'rifs';
    $nom_page = 'rifs';
	$titre_module = 'Entreprises';
/* RIFs Inscription */
} elseif ($_GET['page'] == 'RIFs_Inscription') {
    $titre_page = 'Inscription aux Rencontres IF';
    $nom_module = 'rifs';
    $nom_page = 'inscription';
	$titre_module = 'Entreprises';
/* Stages Etudiant */
} elseif ($_GET['page'] == 'Stages_Etudiant') {
    $titre_page = "Recherche de Stages";
    $nom_module = 'stages';
    $nom_page = 'stages';
    $titre_module = 'Etudiants';
/* Administration Utilisateurs */
} elseif ($_GET['page'] == 'Administration_Utilisateurs') {
    $titre_page = 'Administration des Utilisateurs';
    $nom_module = 'commun';
    $nom_page = 'admin_utilisateurs';
    $titre_module = 'Administration';
/* Administration Journal */
} elseif ($_GET['page'] == 'Administration_Journal') {
    $titre_page = 'Journal d\'activité';
    $lien_module = 'index.php?page=Administration_Journal';
    $nom_module = 'commun';
    $nom_page = 'admin_journal';
    $titre_module = 'Administration';
}
elseif( $_GET['page'] == 'Contact' ) {
	$titre_page= 'Contact';
	$lien_module = '';
	$nom_module = '';
	$titre_module = '';
	$nom_page = 'contact';
}
elseif( $_GET['page'] == 'A_Propos' ) {
        $titre_page= 'A Propos';
        $lien_module = '';
        $nom_module = '';
        $titre_module = '';
        $nom_page = 'a_propos';

}
else {
    $titre_page = '404';
    $lien_module = '';
    $nom_module = '';
    $nom_page = '404';
    $titre_module = '';
}



inclure_fichier('', 'layout', 'php');
?>
