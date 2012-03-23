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

    public static function SupprimerCVByID($_id) {
        if (is_numeric($_id)) {
            BD::Prepare('DELETE FROM CV WHERE ID_CV = :id', array('id' => $_id));
        }
    }

    //Recupération de la liste des mobilité possible
    public static function GetListeMobilite() {
        return BD::Prepare('SELECT * FROM MOBILITE', array(), BD::RECUPERER_TOUT);
    }

    public static function UpdateCV($_id, $_titre_cv, $_id_mobilite, $_loisir) {
        if ($_id > 0 && is_numeric($_id)) {
            $info_cv = array(
                'id' => $_id,
                'titre_cv' => $_titre_cv,
                'id_mobilite' => $_id_mobilite,
                'loisir' => $_loisir,
            );

            //Si l'etudiant à déjà un CV
            BD::Prepare('UPDATE CV SET 
                    TITRE_CV = :titre_cv,
                    ID_MOBILITE = :id_mobilite,
                    LOISIRS_CV = :loisir
                    WHERE ID_CV = :id', $info_cv);
            return $_id;
        } else {
            $info_cv = array(
                'titre_cv' => $_titre_cv,
                'id_mobilite' => $_id_mobilite,
                'loisir' => $_loisir,
                'agreement' => "0",
            );

            BD::Prepare('INSERT INTO CV SET 
                    TITRE_CV = :titre_cv,
                    ID_MOBILITE = :id_mobilite,
                    LOISIRS_CV = :loisir,
                    AGREEMENT = :agreement'
                    , $info_cv);

            $id_cv = BD::GetConnection()->lastInsertId();
            if ($id_cv > 0) {
                return $id_cv;
            } else {
                echo "Erreur 2 veuillez contacter l'administrateur du site";
                return;
            }
        }
    }

    //****************  Fonctions  ******************//
    public function ChangeDiffusion($_etat) {
        if (is_numeric($_etat)) {
            BD::Prepare('UPDATE CV SET AGREEMENT= :etat WHERE ID_CV = :id', array('id' => $this->getId(), 'etat' => $_etat));
            return;
        }
        return NULL;
    }

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
