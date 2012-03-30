<?php
/*
 * @author Loïc Gevrey
 *
 *
 */

require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
session_start();
inclure_fichier('controleur', 'etudiant.class', 'php');
if (isset($_GET['id_etudiant']) && Utilisateur_connecter('entreprise')) {
    $id_etudiant = $_GET['id_etudiant'];
    Etudiant::MettreEnVu($id_etudiant, $_SESSION['utilisateur']->getId(), 2);
} elseif (Utilisateur_connecter('etudiant')) {
    $id_etudiant = $_SESSION['utilisateur']->getId();
} else {
    inclure_fichier('', '401', 'php');
    die();
}

$etudiant = new Etudiant();
$etudiant = Etudiant::GetEtudiantByID($id_etudiant);

if ($etudiant == NULL) {
    $etudiant = new Etudiant();
}

$cv = $etudiant->getCV();
$liste_diplome_etudiant = $etudiant->getDiplome();
$liste_langue_etudiant = $etudiant->getLangue();
$liste_formation_etudiant = $etudiant->getFormation();
$liste_XP = $etudiant->getXP();
$liste_competence = $etudiant->getCompetence();

if ($cv == NULL) {
    $cv = new CV();
}
if ($liste_diplome_etudiant == NULL) {
    $liste_diplome_etudiant = new CV_Diplome();
}
if ($liste_langue_etudiant == NULL) {
    $liste_langue_etudiant = new CV_Langue();
}
if ($liste_formation_etudiant == NULL) {
    $liste_formation_etudiant = new CV_Formation();
}
if ($liste_XP == NULL) {
    $liste_XP = new CV_XP();
}
if ($liste_competence == NULL) {
    $liste_competence = new CV_Competence();
}


$tmp_cv = file_get_contents(dirname(__FILE__) . "/../template_cv/defaut/cv.html");
$tmp_xp = file_get_contents(dirname(__FILE__) . "/../template_cv/defaut/experience.html");
$tmp_langue = file_get_contents(dirname(__FILE__) . "/../template_cv/defaut/langue.html");
$tmp_formation = file_get_contents(dirname(__FILE__) . "/../template_cv/defaut/formation.html");
$tmp_diplome = file_get_contents(dirname(__FILE__) . "/../template_cv/defaut/diplome.html");
$tmp_competence = file_get_contents(dirname(__FILE__) . "/../template_cv/defaut/competence.html");

$experiences = '';
$nb_tot_xp = count($liste_XP);
if ($nb_tot_xp > 0) {
    $nb_xp = 0;
    foreach ($liste_XP as $XP) {
        $nb_xp++;
        $experience = $tmp_xp;
        $experience = str_replace('#entreprise_xp', Protection_XSS($XP->getEntreprise()), $experience);
        $experience = str_replace('#ville_xp', Protection_XSS($XP->getNomVille()), $experience);
        $experience = str_replace('#titre_xp', Protection_XSS($XP->getTitre()), $experience);
        $experience = str_replace('#debut_xp', Protection_XSS($XP->getDebut()), $experience);
        $experience = str_replace('#fin_xp', Protection_XSS($XP->getFin()), $experience);
        $experience = str_replace('#description_xp', nl2br(Protection_XSS($XP->getDescription())), $experience);
        if($nb_xp ==  $nb_tot_xp){
            $experience = str_replace('#last', 'last', $experience);
        }else{
            $experience = str_replace('#last', '', $experience);
        }
        
        $experiences .= $experience;
    }
}



$diplomes = '';
if (count($liste_diplome_etudiant) > 0) {
    foreach ($liste_diplome_etudiant as $diplome_etudiant) {
        $diplome = $tmp_diplome;
        $diplome = str_replace('#annee_diplome', Protection_XSS($diplome_etudiant->getAnnee()), $diplome);
        $diplome = str_replace('#libelle_diplome', Protection_XSS($diplome_etudiant->getLibelle()), $diplome);
        if ($diplome_etudiant->getIdMention() != 1) {
            $diplome = str_replace('#mention_diplome', ' mention ' . Protection_XSS($diplome_etudiant->getNomMention()), $diplome);
        }
        $diplome = str_replace('#institut_diplome', Protection_XSS($diplome_etudiant->getInstitut()), $diplome);
        $diplome = str_replace('#ville_diplome', Protection_XSS($diplome_etudiant->getNomVille()), $diplome);
    }
    $diplomes .= $diplome;
}

$formations = '';
if (count($liste_formation_etudiant) > 0) {
    foreach ($liste_formation_etudiant as $formation_etudiant) {
        $formation = $tmp_formation;
        $formation = str_replace('#debut_formation', Protection_XSS($formation_etudiant->getDebut()), $formation);
        $formation = str_replace('#fin_formation', Protection_XSS($formation_etudiant->getFin()), $formation);
        $formation = str_replace('#institut_formation', Protection_XSS($formation_etudiant->getInstitut()), $formation);
        $formation = str_replace('#ville_formation', Protection_XSS($formation_etudiant->getNomVille()), $formation);
        $formation = str_replace('#annee_formation', Protection_XSS($formation_etudiant->getAnnee()), $formation);
        $formations .= $formation;
    }
}


$langues = '';
if (count($liste_langue_etudiant) > 0) {
    foreach ($liste_langue_etudiant as $langue_etudiant) {
        $langue = $tmp_langue;
        $langue = str_replace('#nom_langue', Protection_XSS($langue_etudiant->getNomLangue()), $langue);
        $langue = str_replace('#nom_niveau_langue', Protection_XSS($langue_etudiant->getNomNiveau()), $langue);
        if ($langue_etudiant->getIdCertif() != 1) {
            $langue = str_replace('#nom_certif_langue', Protection_XSS($langue_etudiant->getNomCertif()), $langue);
            if ($langue_etudiant->getMaxScoreCertif() != NULL && $langue_etudiant->getScoreCertif() != '') {
                $langue = str_replace('#score', Protection_XSS($langue_etudiant->getScoreCertif()) . '/' . Protection_XSS($langue_etudiant->getMaxScoreCertif()), $langue);
            } else {
                $langue = str_replace('#score', '', $langue);
            }
        } else {
            $langue = str_replace('#score', '', $langue);
            $langue = str_replace('#nom_certif_langue', '', $langue);
        }
        $langues .= $langue;
    }
}

$competences1 = '';
$competences2 = '';
$competences3 = '';
if (count($liste_competence) > 0) {
    $colonne = 1;
    foreach ($liste_competence as $competence) {
        if ($colonne == 1) {
            $competence1 = $tmp_competence;
            $competence1 = str_replace('#competence', Protection_XSS($competence->getNomCompetence()), $competence1);
            $competences1 .= $competence1;
            $colonne++;
        }elseif($colonne == 2) {
            $competence2 = $tmp_competence;
            $competence2 = str_replace('#competence', Protection_XSS($competence->getNomCompetence()), $competence2);
            $competences2 .= $competence2;
            $colonne++;
        }else{
            $competence3 = $tmp_competence;
            $competence3 = str_replace('#competence', Protection_XSS($competence->getNomCompetence()), $competence3);
            $competences3 .= $competence3;
            $colonne = 1;
        }
    }
}

$cv_search = array(
    '#nom',
    '#prenom',
    '#titre_cv',
    '#mail',
    '#tel',
    '#adresse1',
    '#adresse2',
    '#code_postale',
    '#ville',
    '#pays',
    '#anniv',
    '#mobilite',
    '#permis',
    '#marital',
    '#experiences',
    '#diplomes',
    '#formations',
    '#langues',
    '#loisir',
    '#competence1',
    '#competence2',
    '#competence3',
);

if ($etudiant->getSexe() == 0) {
    $ne = "Né le ";
} else {
    $ne = "Née le ";
}

$cv_replace = array(
    Protection_XSS($etudiant->getNom()),
    Protection_XSS($etudiant->getPrenom()),
    Protection_XSS($cv->getTitre()),
    Protection_XSS($etudiant->getMail()),
    Protection_XSS($etudiant->getTel()),
    Protection_XSS($etudiant->getAdresse1()),
    Protection_XSS($etudiant->getAdresse2()),
    Protection_XSS($etudiant->getCPVille()),
    Protection_XSS($etudiant->getNomVille()),
    Protection_XSS($etudiant->getPaysVille()),
    $ne . ' ' . Protection_XSS($etudiant->getAnniv()),
    Protection_XSS($cv->getNomMobilite()),
    Protection_XSS($etudiant->getNomPermis()),
    Protection_XSS($etudiant->getNomMarital()),
    $experiences,
    $diplomes,
    $formations,
    $langues,
    Protection_XSS($cv->getLoisir()),
    $competences1,
    $competences2,
    $competences3,
);

$tmp_cv = str_replace($cv_search, $cv_replace, $tmp_cv);
echo $tmp_cv;
?>
