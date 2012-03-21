<?php

require_once dirname(__FILE__) . '/../commun/php/base.inc.php';
inclure_fichier('commun', 'bd.inc', 'php');

class CV_XP {

    //****************  Attributs  ******************//
    private $ID_CVXP;
    private $DEBUT_XP;
    private $FIN_XP;
    private $TITRE_XP;
    private $DESC_XP;
    private $ID_CV;
    private $ENTREPRISE;
    private $ID_VILLE;
    private $ville;

    //****************  Fonctions statiques  ******************//
    //recuperation de l'objet CV par l'ID du CV
    public static function GetCVXPByIdCV($_id) {
        if (is_numeric($_id)) {
            return BD::Prepare('SELECT * FROM CV_XP WHERE ID_CV = :id', array('id' => $_id), BD::RECUPERER_UNE_LIGNE, PDO::FETCH_CLASS, __CLASS__);
        }
        return NULL;
    }

    //****************  Fonctions  ******************//
    //****************  Getters & Setters  ******************//
    public function getId() {
        return $this->ID_CVXP;
    }

    public function getIdCV() {
        return $this->ID_CV;
    }

    public function getDebut() {
        return $this->DEBUT_XP;
    }

    public function getFin() {
        return $this->FIN_XP;
    }

    public function getTitre() {
        return $this->TITRE_XP;
    }

    public function getDescription() {
        return $this->DESC_XP;
    }

    public function getEntreprise() {
        return $this->ENTREPRISE;
    }

    public function getVille() {
        if ($this->ville == NULL) {
            $this->ville = BD::Prepare('SELECT * FROM VILLE WHERE id_ville = :id', array('id' => $this->ID_VILLE), BD::RECUPERER_UNE_LIGNE);
        }
        return $this->ville;
    }

}

?>