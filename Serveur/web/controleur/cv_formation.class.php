<?php

require_once dirname(__FILE__) . '/../commun/php/base.inc.php';
inclure_fichier('commun', 'bd.inc', 'php');

class CV_Formation {

    //****************  Attributs  ******************//
    private $ID_CVFORMATION;
    private $DEBUT_FORMATION;
    private $FIN_FORMATION;
    private $INSTITUT;
    private $ID_VILLE;
    private $ANNEE_FORMATION;
    private $ID_CV;
    private $ville;

    //****************  Fonctions statiques  ******************//
    //recuperation de l'objet CV par l'ID du CV
    public static function GetFormationByIdCV($_id) {
        if (is_numeric($_id)) {
            return BD::Prepare('SELECT * FROM CV_FORMATION WHERE ID_CV = :id', array('id' => $_id), BD::RECUPERER_UNE_LIGNE, PDO::FETCH_CLASS, __CLASS__);
        }
        return NULL;
    }

    //****************  Fonctions  ******************//
    //****************  Getters & Setters  ******************//
    public function getId() {
        return $this->ID_CVFORMATION;
    }

    public function getIdCV() {
        return $this->ID_CV;
    }

    public function getDebut() {
        return $this->DEBUT_FORMATION;
    }

    public function getFin() {
        return $this->FIN_FORMATION;
    }

    public function getAnnee() {
        return $this->ANNEE_FORMATION;
    }

    public function getInstitut() {
        return $this->INSTITUT;
    }

    public function getVille() {
        if ($this->ville == NULL) {
            $this->ville = BD::Prepare('SELECT * FROM VILLE WHERE id_ville = :id', array('id' => $this->ID_VILLE), BD::RECUPERER_UNE_LIGNE);
        }
        return $this->ville;
    }

}

?>
