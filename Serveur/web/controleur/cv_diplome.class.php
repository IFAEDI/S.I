<?php

require_once dirname(__FILE__) . '/../commun/php/base.inc.php';
inclure_fichier('commun', 'bd.inc', 'php');

class CV_Diplome {

    //****************  Attributs  ******************//
    private $ID_CVDIPLOME;
    private $ANNEE_DIPLOME;
    private $ID_MENTION;
    private $LIBELLE_DIPLOME;
    private $INSTITUT;
    private $ID_VILLE;
    private $ID_CV;
    private $nom_ville;
    private $cp_ville;
    private $pays_ville;

    //****************  Fonctions statiques  ******************//
    //recuperation de l'objet CV par l'ID du CV
    public static function GetDiplomeByIdCV($_id) {
        if (is_numeric($_id)) {
            return BD::Prepare('SELECT * FROM CV_DIPLOME WHERE ID_CV = :id', array('id' => $_id), BD::RECUPERER_TOUT, PDO::FETCH_CLASS, __CLASS__);
        }
        return NULL;
    }

    //Recupération de la liste des mobilité possible
    public static function GetListeMention() {
        return BD::Prepare('SELECT * FROM MENTION', array(), BD::RECUPERER_TOUT);
    }

    //****************  Fonctions  ******************//
    //****************  Getters & Setters  ******************//
    public function getId() {
        return $this->ID_CVDIPLOME;
    }

    public function getIdCV() {
        return $this->ID_CV;
    }

    public function getAnnee() {
        return $this->ANNEE_DIPLOME;
    }

    public function getIdMention() {
        return $this->ID_MENTION;
    }

    public function getLibelle() {
        return $this->LIBELLE_DIPLOME;
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
