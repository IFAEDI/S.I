<?php

require_once dirname(__FILE__) . '/../commun/php/base.inc.php';
inclure_fichier('commun', 'bd.inc', 'php');

inclure_fichier('controleur', 'cv_diplome.class', 'php');
inclure_fichier('controleur', 'cv_formation.class', 'php');
inclure_fichier('controleur', 'cv_langue.class', 'php');
inclure_fichier('controleur', 'cv_xp.class', 'php');

class CV {

    //****************  Attributs  ******************//
    private $ID_CV;
    private $TITRE_CV;
    private $ID_MOBILITE;
    private $LOISIRS_CV;
    private $AGREEMENT;
    private $diplome;
    private $formation;
    private $langue;
    private $xp;

    //****************  Fonctions statiques  ******************//
    //recuperation de l'objet CV par l'ID du CV
    public static function GetCVByID($_id) {
        if (is_numeric($_id)) {
            return BD::Prepare('SELECT * FROM CV WHERE ID_CV = :id', array('id' => $_id), BD::RECUPERER_UNE_LIGNE, PDO::FETCH_CLASS, __CLASS__);
        }
        return NULL;
    }

    //Recupération de la liste des mobilité possible
    public static function GetListeMobilite() {
        return BD::Prepare('SELECT * FROM MOBILITE', array(), BD::RECUPERER_TOUT);
    }

    //****************  Fonctions  ******************//
    //****************  Getters & Setters  ******************//
    public function getId() {
        return $this->ID_CV;
    }

    public function getTitre() {
        return $this->TITRE_CV;
    }

    public function getIDMobilite() {
        return $this->ID_MOBILITE;
    }

    public function getLoisir() {
        return $this->LOISIRS_CV;
    }

    public function getAgreement() {
        return $this->AGREEMENT;
    }

    public function getDiplome() {
        if ($this->diplome == NULL) {
            $this->diplome == new CV_Diplome();
            $this->diplome == CV_Diplome::GetDiplomeByIdCV($this->ID_CV);
        }
        return $this->diplome;
    }

    public function getFormation() {
        if ($this->formation == NULL) {
            $this->formation == new CV_Formation;
            $this->formation == CV_Formation::GetFormationByIdCV($this->ID_CV);
        }
        return $this->formation;
    }

    public function getLangue() {
        if ($this->langue == NULL) {
            $this->langue == new CV_Langue();
            $this->langue == CV_Langue::GetLangueByIdCV($this->ID_CV);
        }
        return $this->langue;
    }

    public function getXP() {
        if ($this->xp == NULL) {
            $this->xp == new CV_XP();
            $this->xp == CV_XP::GetCVXPByIdCV($this->ID_CV);
        }
        return $this->xp;
    }

}

?>
