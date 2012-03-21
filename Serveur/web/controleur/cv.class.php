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

  

}

?>
