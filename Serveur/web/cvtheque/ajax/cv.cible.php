<?php

require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
session_start();

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
    $nationalite_etudiant = $_POST['nationalite_etudiant'];
    $ville_naissance_etudiant = $_POST['ville_naissance_etudiant'];
    $cp_naissance_etudiant = $_POST['cp_naissance_etudiant'];
    $pays_naissance_etudiant = $_POST['pays_naissance_etudiant'];
    $mail_etudiant = $_POST['mail_etudiant'];
    $adresse2_etudiant = $_POST['adresse2_etudiant'];
    $statut_marital_etudiant = $_POST['statut_marital_etudiant'];
    $permis_etudiant = $_POST['permis_etudiant'];
    $sexe_etudiant = $_POST['sexe_etudiant'];
    $loisir_etudiant = $_POST['loisir_etudiant'];
    $mobilite_etudiant = $_POST['mobilite_etudiant'];
    $titre_cv = $_POST['titre_cv'];
    $liste_experience = json_decode($_POST['liste_experience']);
    $liste_diplome = json_decode($_POST['liste_diplome']);
    $liste_formation = json_decode($_POST['liste_formation']);
    $liste_langue = json_decode($_POST['liste_langue']);


    //On verifie que les variables sont correcte
    if ($nom_etudiant == '') {
        echo "Erreur : le nom étudiant ne peut être vide";
        die;
    }

    if ($telephone_etudiant == '' || !is_numeric($telephone_etudiant)) {
        echo "Erreur : le prenom étudiant ne peut être vide";
        die;
    }


    if ($ville_etudiant == '') {
        echo "Erreur : la ville étudiant ne peut être vide";
        die;
    }

    if ($cp_etudiant == '' || !is_numeric($cp_etudiant)) {
        echo "Erreur : le code postal étudiant ne peut être vide";
        die;
    }

    if ($pays_etudiant == '') {
        echo "Erreur : le pays étudiant ne peut être vide";
        die;
    }

    if ($anniv_etudiant == '') {
        echo "Erreur : l'anniversaire étudiant ne peut être vide";
        die;
    }

    if ($nationalite_etudiant == '') {
        echo "Erreur : la nationalite étudiante ne peut être vide";
        die;
    }

    if ($ville_naissance_etudiant == '') {
        echo "Erreur : la ville de naissance étudiant ne peut être vide";
        die;
    }

    if ($cp_naissance_etudiant == '' || !is_numeric($cp_naissance_etudiant)) {
        echo "Erreur : le code postal de naissance étudiant ne peut être vide";
        die;
    }

    if ($pays_naissance_etudiant == '') {
        echo "Erreur : la pays de naissance étudiant ne peut être vide";
        die;
    }

    $Syntaxe = '#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
    if (preg_match($Syntaxe, $mail_etudiant) == false) {
        echo "Erreur : Le format de l'adresse mail n'est pas valide";
        die();
    }

    foreach ($liste_experience as $experience) {
        if ($experience[2] == '') {
            echo "Erreur : Le titre d'une expérience ne peut etre vide";
            die;
        }
        if ($experience[4] == '') {
            echo "Erreur : L'entreprise d'une expérience ne peut etre vide";
            die;
        }
        if ($experience[5] == '') {
            echo "Erreur : La ville d'une expérience ne peut etre vide";
            die;
        }
    }

    foreach ($liste_diplome as $diplome) {
        if ($diplome[0] == '') {
            echo "Erreur : L'année d'un diplome ne peut etre vide";
            die;
        }
        if ($diplome[2] == '') {
            echo "Erreur : Le titre d'un diplome ne peut etre vide";
            die;
        }
        if ($diplome[3] == '') {
            echo "Erreur : L'institut d'un diplome ne peut etre vide";
            die;
        }
        if ($diplome[4] == '') {
            echo "Erreur : La ville d'un diplome ne peut etre vide";
            die;
        }
    }

    foreach ($liste_formation as $formation) {
        if ($formation[2] == '') {
            echo "Erreur : L'institut d'une formation ne peut etre vide";
            die;
        }
        if ($formation[3] == '') {
            echo "Erreur : La ville d'une formation ne peut etre vide";
            die;
        }
        if ($formation[4] == '') {
            echo "Erreur : L'année d'une formation ne peut etre vide";
            die;
        }
    }

    foreach ($liste_langue as $langue) {
        $score_max = CV_Langue::GetScoreMaxCertif($langue[2]);
        if ((!is_numeric($langue[3]) || $langue[3] > $score_max) && $langue[2] != 1) {
            echo "Erreur : Le score de la langue est incorrect (>$score_max)";
            die;
        }
    }



    //On recupere l'id_cv si l'étudiant en à deja un
    $etudiant = new Etudiant();
    $etudiant = Etudiant::GetEtudiantByID($_SESSION['utilisateur']->getId());
    if ($etudiant == NULL) {
        $etudiant = new Etudiant();
    }

    //On met a jour/Ajoute le CV
    $id_cv = CV::UpdateCV($etudiant->getIdCV(), $titre_cv, $mobilite_etudiant, $loisir_etudiant, '1');

    //On met à jour/Ajoute les informations etudiante
    Etudiant::UpdateEtudiant($_SESSION['utilisateur']->getId(), $id_cv, $nom_etudiant, $prenom_etudiant, $sexe_etudiant, $adresse1_etudiant, $adresse2_etudiant, $ville_etudiant, $cp_etudiant, $pays_etudiant, $telephone_etudiant, $mail_etudiant, $anniv_etudiant, $ville_naissance_etudiant, $cp_naissance_etudiant, $pays_naissance_etudiant, $nationalite_etudiant, $statut_marital_etudiant, $permis_etudiant);

    //On supprime toutes les langues du cv
    CV_Langue::SupprimerLangueByIdCV($id_cv);

    //On ajoute les langues rentrées par l'utilisateur
    foreach ($liste_langue as $langue) {
        CV_Langue::AjouterLangue($langue[0], $langue[1], $langue[2], $langue[3], $id_cv);
    }

    //On supprime toutes les formations du cv
    CV_Formation::SupprimerFormationByIdCV($id_cv);

    //On ajoute les fomration rentrées par l'utilisateur
    foreach ($liste_formation as $formation) {
        CV_Formation::AjouterFormation($formation[0], $formation[1], $formation[2], $formation[3], '', '', $formation[4], $id_cv);
    }

    //On supprime toutes les diplomes du cv
    CV_Diplome::SupprimerDiplomeByIdCV($id_cv);

    //On ajoute les diplomes rentrés par l'utilisateur
    foreach ($liste_diplome as $diplome) {
        CV_Diplome::AjouterDiplome($diplome[0], $diplome[1], $diplome[2], $diplome[3], $diplome[4], '', '', $id_cv);
    }

    //On supprime toutes les experiences du cv
    CV_XP::SupprimerXPByIdCV($id_cv);

    //On ajoute les experiences rentrées par l'utilisateur
    foreach ($liste_experience as $experience) {
        CV_XP::AjouterXP($experience[0], $experience[1], $experience[2], $experience[3], $experience[4], $experience[5], $_cp, $_pays, $id_cv);
    }


    echo "1";
}




//Changement de l'état de diffusion du CV
if (isset($_GET['action']) && $_GET['action'] == 'diffusion_cv') {
    if (!Utilisateur_connecter('etudiant')) {
        die;
    }
    inclure_fichier('controleur', 'etudiant.class', 'php');
    
    $etat = $_POST['etat'];

    $etudiant = new Etudiant();
    $etudiant = Etudiant::GetEtudiantByID($_SESSION['utilisateur']->getId());
    if ($etudiant == NULL) {
       echo "Erreur 18 veuillez contacter l'administrateur";
       die;
    }
    
    $cv = $etudiant->getCV();
    $cv->ChangeDiffusion($etat);
    echo "1";
}


//Supression du CV
if (isset($_GET['action']) && $_GET['action'] == 'supprimer_cv') {
    if (!Utilisateur_connecter('etudiant')) {
        die;
    }
    inclure_fichier('controleur', 'etudiant.class', 'php');
    $etudiant = new Etudiant();
    $etudiant = Etudiant::GetEtudiantByID($_SESSION['utilisateur']->getId());
    if ($etudiant == NULL) {
       echo "Erreur 19 veuillez contacter l'administrateur";
       die;
    }
    
    $id_cv = $etudiant->getIdCV();
    Etudiant::SupprimerCV($_SESSION['utilisateur']->getId(), $id_cv);
    
    
    echo "1";
}
?>
