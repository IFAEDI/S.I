<?php

/*
 * @author Loïc Gevrey
 *
 *
 */


require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';

$id_utilisater = 1;

//Edition ou Ajout d'un nouveau CV
if (isset($_GET['action']) && $_GET['action'] == 'edit_cv') {
    if (!Utilisateur_connecter('etudiant')) {
        die;
    }

    inclure_fichier('controleur', 'etudiant.class', 'php');

    $nom_etudiant = $_POST['nom_etudiant'];
    $prenom_etudiant = $_POST['prenom_etudiant'];
    $telephone_etudiant = $_POST['telephone_etudiant'];
    $adresse1_etudiant = $_POST['adresse1_etudiant'];
    $ville_etudiant = $_POST['ville_etudiant'];
    $cp_etudiant = $_POST['cp_etudiant'];
    $pays_etudiant = $_POST['pays_etudiant'];
    $anniv_etudiant = $_POST['anniv_etudiant'];
    $mail_etudiant = $_POST['mail_etudiant'];
    $adresse2_etudiant = $_POST['adresse2_etudiant'];
    $statut_marital_etudiant = $_POST['statut_marital_etudiant'];
    $permis_etudiant = $_POST['permis_etudiant'];
    $sexe_etudiant = $_POST['sexe_etudiant'];
    $loisir_etudiant = $_POST['loisir_etudiant'];
    $mobilite_etudiant = $_POST['mobilite_etudiant'];
    $titre_cv = $_POST['titre_cv'];
    $mots_clef = $_POST['mots_clef'];
    $annee = $_POST['annee'];
    $liste_experience = json_decode($_POST['liste_experience']);
    $liste_diplome = json_decode($_POST['liste_diplome']);
    $liste_formation = json_decode($_POST['liste_formation']);
    $liste_langue = json_decode($_POST['liste_langue']);
    $liste_comptetence = json_decode($_POST['liste_comptetence']);


    //On initialise l'array de retour
    $retour = array();

    //On verifie que les variables sont correcte
    if ($nom_etudiant == '') {
        $retour['code'] = 'error';
        $retour['msg'] = 'Erreur : Le nom de l\'étudiant ne peut être vide';
        echo json_encode($retour);
        die;
    }

    if ($ville_etudiant == '') {
        $retour['code'] = 'error';
        $retour['msg'] = 'Erreur : La ville de l\'étudiant ne peut être vide';
        echo json_encode($retour);
        die;
    }

    if ($cp_etudiant == '' || !is_numeric($cp_etudiant)) {
        $retour['code'] = 'error';
        $retour['msg'] = 'Erreur : Le code postal de l\'étudiant ne peut être vide';
        echo json_encode($retour);

        die;
    }

    if ($pays_etudiant == '') {
        $retour['code'] = 'error';
        $retour['msg'] = 'Erreur : Le pays de l\'étudiant ne peut être vide';
        echo json_encode($retour);
        die;
    }

    if ($anniv_etudiant == '') {
        $retour['code'] = 'error';
        $retour['msg'] = 'Erreur : L\'anniversaire de l\'étudiant ne peut être vide';
        echo json_encode($retour);
        die;
    }


    $Syntaxe = '#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
    if (preg_match($Syntaxe, $mail_etudiant) == false) {
        $retour['code'] = 'error';
        $retour['msg'] = 'Erreur : Le format de l\'adresse mail n\'est pas valide';
        echo json_encode($retour);
        die();
    }

    foreach ($liste_experience as $experience) {
        if ($experience[2] == '') {
            $retour['code'] = 'error';
            $retour['msg'] = 'Erreur : Le titre d\'une expérience ne peut être vide';
            echo json_encode($retour);
            die;
        }
        if ($experience[4] == '') {
            $retour['code'] = 'error';
            $retour['msg'] = 'Erreur : L\'entreprise d\'une expérience ne peut être vide';
            echo json_encode($retour);
            die;
        }
        if ($experience[5] == '') {
            $retour['code'] = 'error';
            $retour['msg'] = 'Erreur : La ville d\'une expérience ne peut être vide';
            echo json_encode($retour);
            die;
        }
    }

    foreach ($liste_diplome as $diplome) {
        if ($diplome[0] == '') {
            $retour['code'] = 'error';
            $retour['msg'] = 'Erreur : L\'année d\'un diplôme ne peut être vide';
            echo json_encode($retour);
            die;
        }
        if ($diplome[2] == '') {
            $retour['code'] = 'error';
            $retour['msg'] = 'Erreur : Le titre d\'un diplôme ne peut être vide';
            echo json_encode($retour);
            die;
        }
        if ($diplome[3] == '') {
            $retour['code'] = 'error';
            $retour['msg'] = 'Erreur : L\'institut d\'un diplôme ne peut être vide';
            echo json_encode($retour);
            die;
        }
        if ($diplome[4] == '') {
            $retour['code'] = 'error';
            $retour['msg'] = 'Erreur : La ville d\'un diplôme ne peut être vide';
            echo json_encode($retour);
            die;
        }
    }

    foreach ($liste_comptetence as $competence) {
        if ($competence[0] == '') {
            $retour['code'] = 'error';
            $retour['msg'] = 'Erreur : Le nom d\'une compétence ne peut être vide';
            echo json_encode($retour);
            die;
        }
    }

    foreach ($liste_formation as $formation) {
        if ($formation[2] == '') {
            $retour['code'] = 'error';
            $retour['msg'] = 'Erreur : L\'institut d\'une formation ne peut être vide';
            echo json_encode($retour);
            die;
        }
        if ($formation[3] == '') {
            $retour['code'] = 'error';
            $retour['msg'] = 'Erreur : La ville d\'une formation ne peut être vide';
            echo json_encode($retour);
            die;
        }
        if ($formation[4] == '') {
            $retour['code'] = 'error';
            $retour['msg'] = 'Erreur : L\'année d\'une formation ne peut être vide';
            echo json_encode($retour);
            die;
        }
    }

    foreach ($liste_langue as $langue) {
        $score_max = CV_Langue::GetScoreMaxCertif($langue[2]);
        if ((!is_numeric($langue[3]) || $langue[3] > $score_max) && $langue[2] != 1) {
            $retour['code'] = 'error';
            $retour['msg'] = "Erreur : Le score de la langue est incorrect (>$score_max)";
            echo json_encode($retour);
            die;
        }
    }



    //On recupere l'id_cv si l'étudiant en à deja un
    $etudiant = new Etudiant();
    $etudiant = Etudiant::GetEtudiantByID($id_utilisater);
    if ($etudiant == NULL) {
        $etudiant = new Etudiant();
    }

    //On met a jour/Ajoute le CV
    $id_cv = CV::UpdateCV($etudiant->getIdCV(), $titre_cv, $mobilite_etudiant, $loisir_etudiant, $mots_clef, $annee);
    if (!is_numeric($id_cv)) {
        $retour['code'] = 'error';
        $retour['msg'] = $id_cv;
        echo json_encode($retour);
        die;
    }

    //On met à jour/Ajoute les informations etudiante
    $retour_fct = Etudiant::UpdateEtudiant($id_utilisater, $id_cv, $nom_etudiant, $prenom_etudiant, $sexe_etudiant, $adresse1_etudiant, $adresse2_etudiant, $ville_etudiant, $cp_etudiant, $pays_etudiant, $telephone_etudiant, $mail_etudiant, $anniv_etudiant, $statut_marital_etudiant, $permis_etudiant);
    if (!$retour_fct) {
        $retour['code'] = 'error';
        $retour['msg'] = $retour_fct;
        echo json_encode($retour);
        die;
    }


    //On supprime toutes les langues du cv
    $retour_fct = CV_Langue::SupprimerLangueByIdCV($id_cv);
    if (!$retour_fct) {
        $retour['code'] = 'error';
        $retour['msg'] = $retour_fct;
        echo json_encode($retour);
        die;
    }

    //On ajoute les langues rentrées par l'utilisateur
    foreach ($liste_langue as $langue) {
        $retour_fct = CV_Langue::AjouterLangue($langue[0], $langue[1], $langue[2], $langue[3], $id_cv);
        if (!$retour_fct) {
            $retour['code'] = 'error';
            $retour['msg'] = $retour_fct;
            echo json_encode($retour);
            die;
        }
    }

    //On supprime toutes les formations du cv
    $retour_fct = CV_Formation::SupprimerFormationByIdCV($id_cv);
    if (!$retour_fct) {
        $retour['code'] = 'error';
        $retour['msg'] = $retour_fct;
        echo json_encode($retour);
        die;
    }

    //On ajoute les fomration rentrées par l'utilisateur
    foreach ($liste_formation as $formation) {
        $retour_fct = CV_Formation::AjouterFormation($formation[0], $formation[1], $formation[2], $formation[3], '', '', $formation[4], $id_cv);
        if (!$retour_fct) {
            $retour['code'] = 'error';
            $retour['msg'] = $retour_fct;
            echo json_encode($retour);
            die;
        }
    }

    //On supprime toutes les diplomes du cv
    $retour_fct = CV_Diplome::SupprimerDiplomeByIdCV($id_cv);
    if (!$retour_fct) {
        $retour['code'] = 'error';
        $retour['msg'] = $retour_fct;
        echo json_encode($retour);
        die;
    }

    //On ajoute les diplomes rentrés par l'utilisateur
    foreach ($liste_diplome as $diplome) {
        $retour_fct = CV_Diplome::AjouterDiplome($diplome[0], $diplome[1], $diplome[2], $diplome[3], $diplome[4], '', '', $id_cv);
        if (!$retour_fct) {
            $retour['code'] = 'error';
            $retour['msg'] = $retour_fct;
            echo json_encode($retour);
            die;
        }
    }

    //On supprime toutes les experiences du cv
    $retour_fct = CV_XP::SupprimerXPByIdCV($id_cv);
    if (!$retour_fct) {
        $retour['code'] = 'error';
        $retour['msg'] = $retour_fct;
        echo json_encode($retour);
        die;
    }

    //On ajoute les experiences rentrées par l'utilisateur
    foreach ($liste_experience as $experience) {
        $retour_fct = CV_XP::AjouterXP($experience[0], $experience[1], $experience[2], $experience[3], $experience[4], $experience[5], $_cp, $_pays, $id_cv);
        if (!$retour_fct) {
            $retour['code'] = 'error';
            $retour['msg'] = $retour_fct;
            echo json_encode($retour);
            die;
        }
    }


    //On supprime toutes les competences
    $retour_fct = CV_Competence::SupprimerDiplomeByIdCV($id_cv);
    if (!$retour_fct) {
        $retour['code'] = 'error';
        $retour['msg'] = $retour_fct;
        echo json_encode($retour);
        die;
    }

    //On ajoute les competences rentrées par l'utilisateur
    foreach ($liste_comptetence as $competence) {
        $retour_fct = CV_Competence::AjouterCompetence($competence[0], $id_cv);
        if (!$retour_fct) {
            $retour['code'] = 'error';
            $retour['msg'] = $retour_fct;
            echo json_encode($retour);
            die;
        }
    }

    //On indique que le CV vient d'etre mis a jour (s'il vient d'etre creer rien ne se passe)
    $retour_fct = Etudiant::MettreEnVu($id_utilisater, '', 1);
    if (!$retour_fct) {
        $retour['code'] = 'error';
        $retour['msg'] = $retour_fct;
        echo json_encode($retour);
        die;
    }

    $retour['code'] = 'ok';
    $retour['msg'] = '';
    echo json_encode($retour);
    die;
}




//Changement de l'état de diffusion du CV
if (isset($_GET['action']) && $_GET['action'] == 'diffusion_cv') {
    if (!Utilisateur_connecter('etudiant')) {
        die;
    }
    inclure_fichier('controleur', 'etudiant.class', 'php');

    $etat = $_POST['etat'];

    $etudiant = new Etudiant();
    $etudiant = Etudiant::GetEtudiantByID($id_utilisater);
    if ($etudiant == NULL) {
        echo "Erreur 18 veuillez contacter l'administrateur";
        die;
    }

    $cv = $etudiant->getCV();
    $cv->ChangeDiffusion($etat);


    $retour['code'] = 'ok';
    $retour['msg'] = '';
    echo json_encode($retour);
}


//Supression du CV
if (isset($_GET['action']) && $_GET['action'] == 'supprimer_cv') {
    if (!Utilisateur_connecter('etudiant')) {
        die;
    }
    inclure_fichier('controleur', 'etudiant.class', 'php');
    $etudiant = new Etudiant();
    $etudiant = Etudiant::GetEtudiantByID($id_utilisater);
    if ($etudiant == NULL) {
        echo "Erreur 19 veuillez contacter l'administrateur";
        die;
    }

    $id_cv = $etudiant->getIdCV();
    Etudiant::SupprimerCV($id_utilisater, $id_cv);

    $retour['code'] = 'ok';
    $retour['msg'] = '';
    echo json_encode($retour);
}


//Autocompletion de la ville si celle-ci est connue
if (isset($_GET['action']) && $_GET['action'] == 'autocomplete_ville') {
    if (!Utilisateur_connecter('etudiant')) {
        die;
    }
    inclure_fichier('controleur', 'etudiant.class', 'php');
    echo json_encode(Etudiant::GetVilleByName($_POST['nom_ville']));
}


//Recherche d'un cv
if (isset($_GET['action']) && $_GET['action'] == 'rechercher_cv') {
    if (!Utilisateur_connecter('entreprise')) {
        die;
    }
    inclure_fichier('controleur', 'etudiant.class', 'php');
    echo json_encode(Etudiant::RechercherCVEtudiant($_POST['annee'], $_POST['mots_clefs'], $_SESSION['utilisateur']->getId()));
}


//Enlever des favoris un cv
if (isset($_GET['action']) && $_GET['action'] == 'unstar_cv') {
    if (!Utilisateur_connecter('entreprise')) {
        die;
    }
    inclure_fichier('controleur', 'etudiant.class', 'php');
    Etudiant::MettreEnFavoris($_POST['id_etudiant'], $id_utilisater, 0);

    $retour['code'] = 'ok';
    $retour['msg'] = '';
    echo json_encode($retour);
}

//Mettre en favoris un cv
if (isset($_GET['action']) && $_GET['action'] == 'star_cv') {
    if (!Utilisateur_connecter('entreprise')) {
        die;
    }
    inclure_fichier('controleur', 'etudiant.class', 'php');
    Etudiant::MettreEnFavoris($_POST['id_etudiant'], $id_utilisater, 1);

    $retour['code'] = 'ok';
    $retour['msg'] = '';
    echo json_encode($retour);
}
?>
