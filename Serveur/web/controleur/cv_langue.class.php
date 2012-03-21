<?php

require_once dirname(__FILE__) . '/../commun/php/base.inc.php';
inclure_fichier('commun', 'bd.inc', 'php');

class CV_Langue {

    //****************  Attributs  ******************//
    private $ID_CVLANGUE;
    private $ID_LANGUE;
    private $ID_NIVEAU;
    private $ID_CERTIF;
    private $SCORE_CERTIF;
    private $ID_CV;

    //****************  Fonctions statiques  ******************//
    //recuperation de l'objet CV par l'ID du CV
    public static function GetLangueByIdCV($_id) {
        if (is_numeric($_id)) {
            return BD::Prepare('SELECT * FROM CV_LANGUE WHERE ID_CV = :id', array('id' => $_id), BD::RECUPERER_TOUT, PDO::FETCH_CLASS, __CLASS__);
        }
        return NULL;
    }

    //Recupération de la liste des langues possible
    public static function GetListeLangue() {
        return BD::Prepare('SELECT * FROM LANGUE', array(), BD::RECUPERER_TOUT);
    }

    //Recupération de la liste des niveaux possible
    public static function GetListeNiveau() {
        return BD::Prepare('SELECT * FROM NIVEAU_LANGUE', array(), BD::RECUPERER_TOUT);
    }

    //Recupération de la liste des certification possible
    public static function GetListeCertif() {
        return BD::Prepare('SELECT * FROM CERTIF_LNG', array(), BD::RECUPERER_TOUT);
    }

    //****************  Fonctions  ******************//
    //****************  Getters & Setters  ******************//
    public function getId() {
        return $this->ID_CVLANGUE;
    }

    public function getIdCV() {
        return $this->ID_CV;
    }

    public function getIdLAngue() {
        return $this->ID_LANGUE;
    }

    public function getIdNiveau() {
        return $this->ID_NIVEAU;
    }

    public function getIdCertif() {
        return $this->ID_CERTIF;
    }

    public function getScoreCertif() {
        return $this->SCORE_CERTIF;
    }

}

?>
