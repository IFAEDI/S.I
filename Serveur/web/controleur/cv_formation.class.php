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
    private $nom_ville;
    private $cp_ville;
    private $pays_ville;

    //****************  Fonctions statiques  ******************//
    //recuperation de l'objet CV par l'ID du CV
    public static function GetFormationByIdCV($_id) {
        if (is_numeric($_id)) {
            return BD::Prepare('SELECT * FROM CV_FORMATION WHERE ID_CV = :id', array('id' => $_id), BD::RECUPERER_TOUT, PDO::FETCH_CLASS, __CLASS__);
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

    public function getNomVille() {
        if ($this->nom_ville == NULL) {
            $this->nom_ville = BD::Prepare('SELECT LIBELLE_VILLE FROM VILLE WHERE id_ville = :id', array('id' => $this->ID_VILLE), BD::RECUPERER_UNE_LIGNE);
        }
        return $this->nom_ville['LIBELLE_VILLE'];
    }

    public function getCPVille() {
        if ($this->cp_ville == NULL) {
            $this->cp_ville = BD::Prepare('SELECT CP_VILLE FROM VILLE WHERE id_ville = :id', array('id' => $this->ID_VILLE), BD::RECUPERER_UNE_LIGNE);
        }
        return $this->cp_ville['CP_VILLE'];
    }

    public function getPaysVille() {
        if ($this->pays_ville == NULL) {
            $this->pays_ville = BD::Prepare('SELECT PAYS_VILLE FROM VILLE WHERE id_ville = :id', array('id' => $this->ID_VILLE), BD::RECUPERER_UNE_LIGNE);
        }
        return $this->pays_ville['PAYS_VILLE'];
    }

}

?>
