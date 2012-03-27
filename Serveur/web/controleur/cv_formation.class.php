<?php
/*
 * @author Loïc Gevrey
 *
 *
 */

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
    public static function GetFormationByIdCV($_id_cv) {
        if (is_numeric($_id_cv)) {
            return BD::Prepare('SELECT * FROM CV_FORMATION WHERE ID_CV = :id_cv ORDER BY DEBUT_FORMATION DESC', array('id_cv' => $_id_cv), BD::RECUPERER_TOUT, PDO::FETCH_CLASS, __CLASS__);
        }
        return NULL;
    }

    public static function AjouterFormation($_debut_formation,$_fin_formation,$_institut,$_ville,$_cp,$_pays, $_annee_formation,$_id_cv) { 
        if ($_id_cv > 0 && is_numeric($_id_cv)) {
            $id_ville = Etudiant::GetVilleOrAdd($_ville, $_cp, $_pays);
            
            $info_formation = array(
                'id_cv' => $_id_cv,
                'debut_formation' => $_debut_formation,
                'fin_formation' => $_fin_formation,
                'institut' => $_institut,
                'annee_formation' => $_annee_formation,
                'id_ville' => $id_ville,
            );

            BD::Prepare('INSERT INTO CV_FORMATION SET 
                    DEBUT_FORMATION = :debut_formation,
                    FIN_FORMATION = :fin_formation,
                    INSTITUT = :institut,
                    ID_VILLE = :id_ville, 
                    ANNEE_FORMATION = :annee_formation,
                    ID_CV = :id_cv', $info_formation);
            return true;
        } else {
            echo "Erreur 6 veuillez contacter l'administrateur du site";
            return;
        }
    }
    
    public static function SupprimerFormationByIdCV($_id_cv) {
        if ($_id_cv > 0 && is_numeric($_id_cv)) {
            BD::Prepare('DELETE FROM CV_FORMATION WHERE ID_CV = :id_cv', array('id_cv' => $_id_cv));
            return true;
        } else {
            return "Erreur 7 veuillez contacter l'administrateur du site";
        }
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
