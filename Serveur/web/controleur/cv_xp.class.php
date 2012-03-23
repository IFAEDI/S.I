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
    private $nom_ville;
    private $cp_ville;
    private $pays_ville;

    //****************  Fonctions statiques  ******************//
    //recuperation de l'objet CV par l'ID du CV
    public static function GetCVXPByIdCV($_id) {
        if (is_numeric($_id)) {
            return BD::Prepare('SELECT * FROM CV_XP WHERE ID_CV = :id', array('id' => $_id), BD::RECUPERER_TOUT, PDO::FETCH_CLASS, __CLASS__);
        }
        return NULL;
    }
    
     public static function AjouterXP($_debut_xp,$_fin_xp,$_titre_xp,$_desc_xp,$_entreprise,$_ville,$_cp,$_pays,$_id_cv) { 
        if ($_id_cv > 0 && is_numeric($_id_cv)) {
            $id_ville = Etudiant::GetVilleOrAdd($_ville, $_cp, $_pays);
            
            $info_XP = array(
                'id_cv' => $_id_cv,
                'debut_xp' => $_debut_xp,
                'fin_xp' => $_fin_xp,
                'titre_xp' => $_titre_xp,
                'desc_xp' => $_desc_xp,
                'entreprise' => $_entreprise,
                'id_ville' => $id_ville,
            );

            BD::Prepare('INSERT INTO CV_XP SET 
                    DEBUT_XP = :debut_xp,
                    FIN_XP = :fin_xp,
                    TITRE_XP = :titre_xp,
                    DESC_XP = :desc_xp, 
                    ENTREPRISE = :entreprise,
                    ID_VILLE = :id_ville,
                    ID_CV = :id_cv', $info_XP);
        } else {
            echo "Erreur 10 veuillez contacter l'administrateur du site";
            return;
        }
    }
    
    public static function SupprimerXPByIdCV($_id_cv) {
        if ($_id_cv > 0 && is_numeric($_id_cv)) {
            BD::Prepare('DELETE FROM CV_XP WHERE ID_CV = :id_cv', array('id_cv' => $_id_cv));
        } else {
            echo "Erreur 11 veuillez contacter l'administrateur du site";
            return;
        }
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
