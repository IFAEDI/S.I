<?php

require_once dirname(__FILE__) . '/../commun/php/base.inc.php';
inclure_fichier('commun', 'bd.inc', 'php');

inclure_fichier('controleur', 'cv.class', 'php');

class Etudiant {

    //****************  Attributs  ******************//
    private $ID_ETUDIANT;
    private $NOM_ETUDIANT;
    private $PRENOM_ETUDIANT;
    private $SEXE_ETUDIANT;
    private $ADRESSE1_ETUDIANT;
    private $ADRESSE2_ETUDIANT;
    private $ID_VILLE;
    private $ville;
    private $TEL_ETUDIANT;
    private $MAIL_ETUDIANT;
    private $ANNIV_ETUDIANT;
    private $ID_VILLE_NAISSANCE;
    private $ville_naissance;
    private $NATIONALITE_ETUDIANT;
    private $ID_MARITAL;
    private $ID_PERMIS;
    private $PHOTO_ETUDIANT;
    private $ID_CV;
    private $cv;
    private $diplome;
    private $formation;
    private $langue;
    private $xp;

    //****************  Fonctions statiques  ******************//
    //recuperation de l'objet Etudiant par l'ID de l'étudiant
    public static function GetEtudiantByID($_id) {
        if (is_numeric($_id)) {
            return BD::Prepare('SELECT * FROM ETUDIANT WHERE id_etudiant = :id', array('id' => $_id), BD::RECUPERER_UNE_LIGNE, PDO::FETCH_CLASS, __CLASS__);
        }
        return NULL;
    }

    //Recupération de la liste des permis possible
    public static function GetListePermis() {
        return BD::Prepare('SELECT * FROM PERMIS', array(), BD::RECUPERER_TOUT);
    }

    //Recupération de la liste statuts maritals possible
    public static function GetListeStatutMarital() {
        return BD::Prepare('SELECT * FROM STATUT_MARITAL', array(), BD::RECUPERER_TOUT);
    }

    //****************  Fonctions  ******************//
    //****************  Getters & Setters  ******************//
    public function getId() {
        return $this->ID_ETUDIANT;
    }

    public function getNom() {
        return $this->NOM_ETUDIANT;
    }

    public function getPrenom() {
        return $this->PRENOM_ETUDIANT;
    }

    public function getSexe() {
        return $this->SEXE_ETUDIANT;
    }

    public function getAdresse1() {
        return $this->ADRESSE1_ETUDIANT;
    }

    public function getAdresse2() {
        return $this->ADRESSE2_ETUDIANT;
    }

    public function getVille() {
        if ($this->ville == NULL) {
            $this->ville = BD::Prepare('SELECT * FROM VILLE WHERE id_ville = :id', array('id' => $this->ID_VILLE), BD::RECUPERER_UNE_LIGNE);
        }
        return $this->ville;
    }

    public function getVilleNaissance() {
        if ($this->ville_naissance == NULL) {
            $this->ville_naissance = BD::Prepare('SELECT * FROM VILLE WHERE id_ville = :id', array('id' => $this->ID_VILLE_NAISSANCE), BD::RECUPERER_UNE_LIGNE);
        }
        return $this->ville_naissance;
    }

    public function getTel() {
        return $this->TEL_ETUDIANT;
    }

    public function getMail() {
        return $this->MAIL_ETUDIANT;
    }

    public function getAnniv() {
        return $this->ANNIV_ETUDIANT;
    }

    public function getNationalite() {
        return $this->NATIONALITE_ETUDIANT;
    }

    public function getIdMarital() {
        return $this->ID_MARITAL;
    }

    public function getIdPermis() {
        return $this->ID_PERMIS;
    }

    public function getPhotos() {
        return $this->PHOTO_ETUDIANT;
    }

    public function getIdCV() {
        return $this->ID_CV;
    }

    public function getCV() {
        if ($this->cv == NULL) {
            $this->cv = CV::GetCVByID($this->ID_CV);
        }
        return $this->cv;
    }

    public function getDiplome() {
        if ($this->diplome == NULL) {
            $this->diplome = CV_Diplome::GetDiplomeByIdCV($this->ID_CV);
        }
        return $this->diplome;
    }

    public function getFormation() {
        if ($this->formation == NULL) {
            $this->formation = CV_Formation::GetFormationByIdCV($this->ID_CV);
        }
        return $this->formation;
    }

    public function getLangue() {
        if ($this->langue == NULL) {
            $this->langue = CV_Langue::GetLangueByIdCV($this->ID_CV);
        }
        return $this->langue;
    }

    public function getXP() {
        if ($this->xp == NULL) {
            $this->xp = CV_XP::GetCVXPByIdCV($this->ID_CV);
        }
        return $this->xp;
    }

}

?>
