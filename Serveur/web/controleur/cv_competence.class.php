<?php

/*
 * @author Loïc Gevrey
 *
 *
 */

require_once dirname(__FILE__) . '/../commun/php/base.inc.php';
inclure_fichier('commun', 'bd.inc', 'php');

class CV_Competence {

    //****************  Attributs  ******************//
    private $ID_CVCOMPETENCE;
    private $ID_CV;
    private $LIBELLE_COMPETENCE;

    //****************  Fonctions statiques  ******************//
    //recuperation de l'objet CV par l'ID du CV
    public static function GetCompetenceByIdCV($_id) {
        if (is_numeric($_id)) {
            return BD::Prepare('SELECT * FROM CV_COMPETENCE WHERE ID_CV = :id ORDER BY LIBELLE_COMPETENCE', array('id' => $_id), BD::RECUPERER_TOUT, PDO::FETCH_CLASS, __CLASS__);
        }
        return NULL;
    }

    public static function AjouterCompetence($_libelle_competence, $_id_cv) {
        if ($_id_cv > 0 && is_numeric($_id_cv)) {
            $info_competence = array(
                'id_cv' => $_id_cv,
                'libelle_competence' => $_libelle_competence,
            );

            BD::Prepare('INSERT INTO CV_COMPETENCE (LIBELLE_COMPETENCE,ID_CV) VALUES  
                     (:libelle_competence, :id_cv)', $info_competence);
            return true;
        } else {
            return "Erreur 87 veuillez contacter l'administrateur du site";
        }
    }

    public static function SupprimerDiplomeByIdCV($_id_cv) {
        if ($_id_cv > 0 && is_numeric($_id_cv)) {
            BD::Prepare('DELETE FROM CV_COMPETENCE WHERE ID_CV = :id_cv', array('id_cv' => $_id_cv));
            return true;
        } else {
            return "Erreur 88 veuillez contacter l'administrateur du site";
        }
    }

    //****************  Fonctions  ******************//
    //****************  Getters & Setters  ******************//
    public function getId() {
        return $this->ID_CVCOMPETENCE;
    }

    public function getIdCV() {
        return $this->ID_CV;
    }

    public function getNomCompetence() {
        return $this->LIBELLE_COMPETENCE;
    }

}

?>
