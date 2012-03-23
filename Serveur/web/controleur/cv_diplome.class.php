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
    
      public static function AjouterDiplome($_annee_diplome,$_id_mention,$_libelle_diplome,$_institut,$_ville,$_cp,$_pays,$_id_cv) { 
        if ($_id_cv > 0 && is_numeric($_id_cv)) {
            $id_ville = Etudiant::GetVilleOrAdd($_ville, $_cp, $_pays);
            
            $info_diplome = array(
                'id_cv' => $_id_cv,
                'annee_diplome' => $_annee_diplome,
                'id_mention' => $_id_mention,
                'libelle_diplome' => $_libelle_diplome,
                'institut' => $_institut,
                'id_ville' => $id_ville,
            );

            BD::Prepare('INSERT INTO CV_DIPLOME SET 
                    ANNEE_DIPLOME = :annee_diplome,
                    ID_MENTION = :id_mention,
                    LIBELLE_DIPLOME = :libelle_diplome,
                    INSTITUT = :institut, 
                    ID_VILLE = :id_ville,
                    ID_CV = :id_cv', $info_diplome);
        } else {
            echo "Erreur 8 veuillez contacter l'administrateur du site";
            return;
        }
    }
    
    public static function SupprimerDiplomeByIdCV($_id_cv) {
        if ($_id_cv > 0 && is_numeric($_id_cv)) {
            BD::Prepare('DELETE FROM CV_DIPLOME WHERE ID_CV = :id_cv', array('id_cv' => $_id_cv));
        } else {
            echo "Erreur 9 veuillez contacter l'administrateur du site";
            return;
        }
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
