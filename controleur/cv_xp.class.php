<?php

/*
 * @author Loïc Gevrey
 *
 *
 */

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
            $liste_xp = BD::Prepare('SELECT * FROM CV_XP WHERE ID_CV = :id', array('id' => $_id), BD::RECUPERER_TOUT, PDO::FETCH_CLASS, __CLASS__);
            $i = null;
            $j = null;
            $temp = null;
            $n = count($liste_xp);

            for ($i = 0; $i < ($n - 1); $i++) {
                for ($j = ($i + 1); $j < $n; $j++) {
                    if (CV_XP::Comparaison_Date($liste_xp[$j]->getDebut(), $liste_xp[$i]->getDebut())) {
                        //echo "tableau " . $j . " < tableau " . $i . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $ptab[$j] . " < " . $ptab[$i] . "<br />";
                        $temp = $liste_xp[$i];
                        $liste_xp[$i] = $liste_xp[$j];
                        $liste_xp[$j] = $temp;
                    }
                }
            }

            return $liste_xp;
        }
        return NULL;
    }

    public static function AjouterXP($_debut_xp, $_fin_xp, $_titre_xp, $_desc_xp, $_entreprise, $_ville, $_cp, $_pays, $_id_cv) {
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
            return true;
        } else {
            return "Erreur 10 veuillez contacter l'administrateur du site";
        }
    }

    public static function SupprimerXPByIdCV($_id_cv) {
        if ($_id_cv > 0 && is_numeric($_id_cv)) {
            BD::Prepare('DELETE FROM CV_XP WHERE ID_CV = :id_cv', array('id_cv' => $_id_cv));
            return true;
        } else {
            return "Erreur 11 veuillez contacter l'administrateur du site";
        }
    }

    //****************  Fonctions  ******************//
    //renvoi true si date1>date2 sinon false
    //LEs date doivent etre dans se format jj/mm/aaaa
    public static function Comparaison_Date($_date1, $_date2) {
        $date1 = explode("/", $_date1);
        $date2 = explode("/", $_date2);

        $taille_date1 = count($date1);
        $taille_date2 = count($date2);

        for ($i = 1; $i <= min($taille_date1, $taille_date2); $i++) {
            if ($date1[$taille_date1 - $i] > $date2[$taille_date2 - $i]) {
                return true;
            } else if ($date1[$taille_date1 - $i] < $date2[$taille_date2 - $i]){
                return false;
            }
        }
        return false;
    }

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
