<?php

/*
 * @author Loïc Gevrey
 *
 *
 */

require_once dirname(__FILE__) . '/../commun/php/base.inc.php';
inclure_fichier('commun', 'bd.inc', 'php');

inclure_fichier('controleur', 'cv.class', 'php');

class Etudiant {

    //****************  Attributs  ******************//
    private $ID_ETUDIANT;
    private $NOM;
    private $PRENOM;
    private $SEXE_ETUDIANT;
    private $ADRESSE1_ETUDIANT;
    private $ADRESSE2_ETUDIANT;
    private $ID_VILLE;
    private $nom_ville;
    private $cp_ville;
    private $pays_ville;
    private $TEL_ETUDIANT;
    private $MAIL_ETUDIANT;
    private $ANNIV_ETUDIANT;
    private $ID_MARITAL;
    private $LIBELLE_MARITAL;
    private $ID_PERMIS;
    private $LIBELLE_PERMIS;
    private $PHOTO_ETUDIANT;
    private $ID_CV;
    private $cv;
    private $diplome;
    private $formation;
    private $langue;
    private $xp;
    private $competence;

    //****************  Fonctions statiques  ******************//
    //recuperation de l'objet Etudiant par l'ID de l'étudiant
    public static function GetEtudiantByID($_id) {
        if (is_numeric($_id)) {
            return BD::Prepare('SELECT * FROM ETUDIANT, PERMIS, STATUT_MARITAL, PERSONNE 
                WHERE ID_UTILISATEUR = :id 
                AND PERSONNE.ID_PERSONNE = ETUDIANT.ID_PERSONNE
                AND PERMIS.ID_PERMIS = ETUDIANT.ID_PERMIS 
                AND STATUT_MARITAL.ID_MARITAL=ETUDIANT.ID_MARITAL'
                            , array('id' => $_id), BD::RECUPERER_UNE_LIGNE, PDO::FETCH_CLASS, __CLASS__);
        }
        return NULL;
    }

    public static function GetNbSuivi($_id) {
        if (is_numeric($_id)) {
            $nb_suivi = BD::Prepare('SELECT COUNT(*) FROM ETUDIANT_FAVORIS
                WHERE ID_ETUDIANT = :id'
                            , array('id' => $_id), BD::RECUPERER_UNE_LIGNE);
            return $nb_suivi['COUNT(*)'];
        }
        return "Erreur 430 veuillez contacter l'administrateur système";
    }

    public static function ListeAccesCvtheque() {
        return BD::Prepare('SELECT UTILISATEUR.nom, UTILISATEUR.prenom, UTILISATEUR.id,
                            IFNULL(ACCES_CVTHEQUE.ID_UTILISATEUR,0) as acces_cvtheque 
                            FROM UTILISATEUR LEFT JOIN ACCES_CVTHEQUE ON UTILISATEUR.id=ACCES_CVTHEQUE.ID_UTILISATEUR'
                        , array(), BD::RECUPERER_TOUT);
    }

     //Permet de verifier que l'utilisateur à bien acces à la cvtheque
    public static function AccesCVtheque($_id_utilisateur) {
        if (is_numeric($_id_utilisateur)) {
            $resultat =  BD::Prepare('SELECT COUNT(*) FROM ACCES_CVTHEQUE WHERE ID_UTILISATEUR = :id_utilisateur'
                            , array("id_utilisateur" => $_id_utilisateur));
            return $resultat['COUNT(*)'];
        } else {
            return "Erreur 450 veuillez contacter l'administrateur système";
        }
    }
    
    //Permet d'autoriser l'acces à la cvtheque à un utilisateur
    public static function AutoriserAcces($_id_utilisateur) {
        if (is_numeric($_id_utilisateur)) {
            return BD::Prepare('INSERT INTO ACCES_CVTHEQUE SET ID_UTILISATEUR = :id_utilisateur'
                            , array("id_utilisateur" => $_id_utilisateur));
        } else {
            return "Erreur 451 veuillez contacter l'administrateur système";
        }
    }

    //Permet d'autoriser l'acces à la cvtheque à un utilisateur
    public static function Interdir_Acces($_id_utilisateur) {
        if (is_numeric($_id_utilisateur)) {
            return BD::Prepare('DELETE FROM ACCES_CVTHEQUE WHERE ID_UTILISATEUR = :id_utilisateur'
                            , array("id_utilisateur" => $_id_utilisateur));
        } else {
            return "Erreur 452 veuillez contacter l'administrateur système";
        }
    }

    public static function RechercherCVEtudiant($_annee, $_mots_clef, $_id_entreprise) {
        $connexion = BD::GetConnection();
        if (is_numeric($_id_entreprise)) {
            $requete = "SELECT ETUDIANT.ID_ETUDIANT, NOM,  PRENOM,CV.ANNEE,CV.TITRE_CV, 
                        IF(ETUDIANT_FAVORIS.id_entreprise = #id_entreprise, 1, 0) as favoris, 
                        IF(NEW_UPDATE_CV.id_entreprise = #id_entreprise, etat, 0) as etat
                        FROM PERSONNE, CV JOIN (ETUDIANT LEFT OUTER JOIN ETUDIANT_FAVORIS USING(id_etudiant) LEFT OUTER JOIN  NEW_UPDATE_CV USING(id_etudiant)) USING (id_cv)
                        WHERE CV.AGREEMENT = 1
                        AND ETUDIANT.ID_PERSONNE = PERSONNE.ID_PERSONNE 
                        #WHERE
                        ORDER BY etat ASC, RAND()";
            $requete = str_replace('#id_entreprise', $_id_entreprise, $requete);
            $where = '';
            if (is_numeric($_annee) && $_mots_clef != '') {
                if ($_annee != -1) {
                    $_mots_clef = $connexion->quote($_mots_clef, PDO::PARAM_STR);
                    $_annee = $connexion->quote($_annee, PDO::PARAM_STR);
                    $where = "AND  MATCH MOTS_CLEF AGAINST ($_mots_clef) AND CV.ANNEE = $_annee";
                } else {
                    $_mots_clef = $connexion->quote($_mots_clef, PDO::PARAM_STR);
                    $where = "AND  MATCH MOTS_CLEF AGAINST ($_mots_clef) HAVING(favoris = 1)";
                }
            } else if (is_numeric($_annee)) {
                if ($_annee != -1) {
                    $_annee = $connexion->quote($_annee, PDO::PARAM_STR);
                    $where = " AND CV.ANNEE = $_annee";
                } else {
                    $where = " HAVING(favoris = 1)";
                }
            } else if ($_mots_clef != '') {
                $_mots_clef = $connexion->quote($_mots_clef, PDO::PARAM_STR);
                $where = "AND  MATCH MOTS_CLEF AGAINST ($_mots_clef)";
            }
            $requete = str_replace('#WHERE', $where, $requete);
            return BD::Prepare($requete, array(), BD::RECUPERER_TOUT);
        } else {
            return "Erreur 43 veuillez contacter l'administrateur système";
        }
    }

    public static function UpdateEtudiant($_id, $_id_cv, $_sexe, $_adresse1, $_adresse2, $_ville, $_cp, $_pays, $_telephone, $_mail, $_anniv, $_id_marital, $_id_permis) {
        if (is_numeric($_id)) {
            $id_ville = self::GetVilleOrAdd($_ville, $_cp, $_pays);

            $id_cv = BD::Prepare('SELECT ID_ETUDIANT FROM ETUDIANT WHERE id_etudiant = :id', array('id' => $_id), BD::RECUPERER_UNE_LIGNE);

            if ($id_cv['ID_ETUDIANT'] > 0) {
                $info_etudiant = array(
                    'id' => $_id,
                    'sexe' => $_sexe,
                    'adresse1' => $_adresse1,
                    'adresse2' => $_adresse2,
                    'id_ville' => $id_ville,
                    'telephone' => $_telephone,
                    'mail' => $_mail,
                    'anniv' => $_anniv,
                    'id_marital' => $_id_marital,
                    'id_permis' => $_id_permis,
                );
                //Si l'etudiant à déjà un CV
                BD::Prepare('UPDATE ETUDIANT SET 
                    SEXE_ETUDIANT = :sexe,
                    ADRESSE1_ETUDIANT = :adresse1,
                    ADRESSE2_ETUDIANT = :adresse2,
                    ID_VILLE = :id_ville,
                    TEL_ETUDIANT = :telephone,
                    MAIL_ETUDIANT = :mail,
                    ANNIV_ETUDIANT = :anniv,
                    ID_MARITAL = :id_marital,
                    ID_PERMIS = :id_permis
                    WHERE ID_ETUDIANT = :id', $info_etudiant);

                return true;
            } else {
                $info_etudiant = array(
                    'id' => $_id,
                    'sexe' => $_sexe,
                    'adresse1' => $_adresse1,
                    'adresse2' => $_adresse2,
                    'id_ville' => $id_ville,
                    'telephone' => $_telephone,
                    'mail' => $_mail,
                    'anniv' => $_anniv,
                    'id_marital' => $_id_marital,
                    'id_permis' => $_id_permis,
                    'id_cv' => $_id_cv,
                );

                BD::Prepare('INSERT INTO ETUDIANT SET
                    ID_ETUDIANT = :id,                    
                    SEXE_ETUDIANT = :sexe,
                    ADRESSE1_ETUDIANT = :adresse1,
                    ADRESSE2_ETUDIANT = :adresse2,
                    ID_VILLE = :id_ville,
                    TEL_ETUDIANT = :telephone,
                    MAIL_ETUDIANT = :mail,
                    ANNIV_ETUDIANT = :anniv,
                    ID_MARITAL = :id_marital,
                    ID_PERMIS = :id_permis,
                    ID_CV = :id_cv'
                        , $info_etudiant);

                return true;
            }
        }
        return "Erreur 429 veuillez contacter l'administrateur système";
    }

    //Recupération de la liste des permis possible
    public static function SupprimerCV($_id_etudiant, $_id_cv) {
        if (is_numeric($_id_etudiant) && is_numeric($_id_cv)) {
            CV_Langue::SupprimerLangueByIdCV($_id_cv);
            CV_Formation::SupprimerFormationByIdCV($_id_cv);
            CV_Diplome::SupprimerDiplomeByIdCV($_id_cv);
            CV_XP::SupprimerXPByIdCV($_id_cv);
            CV::SupprimerCVByID($_id_cv);
            BD::Prepare('DELETE FROM ETUDIANT_FAVORIS WHERE id_etudiant = :id', array('id' => $_id_etudiant));
            BD::Prepare('DELETE FROM NEW_UPDATE_CV WHERE id_etudiant = :id', array('id' => $_id_etudiant));
            BD::Prepare('DELETE FROM ETUDIANT WHERE id_etudiant = :id', array('id' => $_id_etudiant));
            return;
        } else {
            return "Erreur 20 veuillez contacter l'administrateur du site";
        }
    }

    public static function MettreEnVu($_id_etudiant, $_id_entreprise, $_etat) {
        if (is_numeric($_id_etudiant) && is_numeric($_etat)) {
            if ($_etat == 2 && is_numeric($_id_entreprise)) {
                BD::Prepare('REPLACE INTO NEW_UPDATE_CV SET ID_ETUDIANT = :id_etudiant, ID_ENTREPRISE = :id_entreprise, ETAT = 2', array('id_etudiant' => $_id_etudiant, 'id_entreprise' => $_id_entreprise));
            } elseif ($_etat == 1) {
                BD::Prepare('UPDATE NEW_UPDATE_CV SET ETAT = 1 WHERE ID_ETUDIANT = :id_etudiant', array('id_etudiant' => $_id_etudiant));
            } else {
                return "Erreur 56 veuillez contacter l'administrateur du site";
            }
            return true;
        } else {
            return "Erreur 47 veuillez contacter l'administrateur du site";
        }
    }

    public static function MettreEnFavoris($_id_etudiant, $_id_entreprise, $_etat) {
        if (is_numeric($_id_etudiant) && is_numeric($_id_entreprise) && is_numeric($_etat)) {
            if ($_etat == 0) {
                BD::Prepare('DELETE FROM ETUDIANT_FAVORIS WHERE ID_ETUDIANT = :id_etudiant AND ID_ENTREPRISE = :id_entreprise', array('id_etudiant' => $_id_etudiant, 'id_entreprise' => $_id_entreprise));
            } else {
                BD::Prepare('INSERT INTO ETUDIANT_FAVORIS SET ID_ETUDIANT = :id_etudiant, ID_ENTREPRISE = :id_entreprise', array('id_etudiant' => $_id_etudiant, 'id_entreprise' => $_id_entreprise));
            }
            return true;
        } else {
            return "Erreur 42 veuillez contacter l'administrateur du site";
        }
    }

    //Retourne le nombre total de cv dans la base
    public static function GetNbCV() {
        $resultat = BD::Prepare('SELECT COUNT(*) FROM ETUDIANT', array());
        return $resultat['COUNT(*)'];
    }

    //Retourne le nombre total de cv diffusé
    public static function GetNbDiffuseCV() {
        $resultat = BD::Prepare('SELECT COUNT(*) FROM ETUDIANT, CV 
           WHERE CV.AGREEMENT = 1
           AND ETUDIANT.ID_CV = CV.ID_CV', array());
        return $resultat['COUNT(*)'];
    }

    public static function StoperTouteDiffusion() {
        BD::Prepare('UPDATE CV SET AGREEMENT=0', array());
    }

    //****************  Fonctions  ******************//
    //Renvoi l'id de la ville correspondante ou si elle n'existe pas l'ajoute
    public function GetVilleOrAdd($_nom, $_code_postal, $_pays) {
        $_nom = strtoupper($_nom);

        if ($_code_postal == '' && $_pays == '') {
            $ville = BD::Prepare('SELECT ID_VILLE FROM VILLE WHERE LIBELLE_VILLE = :nom', array('nom' => $_nom));
        } elseif ($_code_postal == '') {
            $ville = BD::Prepare('SELECT ID_VILLE FROM VILLE WHERE LIBELLE_VILLE = :nom AND PAYS_VILLE = :pays', array('nom' => $_nom, 'pays' => $_pays));
        } elseif ($_pays == '') {
            $ville = BD::Prepare('SELECT ID_VILLE FROM VILLE WHERE LIBELLE_VILLE = :nom AND CP_VILLE = :code_postal', array('nom' => $_nom, 'code_postal' => $_code_postal));
        } else {
            $ville = BD::Prepare('SELECT ID_VILLE FROM VILLE WHERE LIBELLE_VILLE = :nom AND CP_VILLE = :code_postal AND PAYS_VILLE = :pays', array('nom' => $_nom, 'pays' => $_pays, 'code_postal' => $_code_postal));
        }

        if ($ville['ID_VILLE'] > 0) {
            return $ville['ID_VILLE'];
        } else {
            BD::Prepare('INSERT INTO VILLE SET LIBELLE_VILLE = :nom, CP_VILLE = :code_postal, PAYS_VILLE = :pays', array('nom' => $_nom, 'pays' => $_pays, 'code_postal' => $_code_postal));
            $id_ville = BD::GetConnection()->lastInsertId();
            if ($id_ville > 0) {
                return $id_ville;
            } else {
                return "Erreur 3 veuillez contacter l'administrateur du site";
            }
        }
    }

    public static function GetVilleByName($_nom) {
        return BD::Prepare('SELECT * FROM VILLE WHERE LIBELLE_VILLE = :nom', array('nom' => $_nom));
    }

    //Recupération de la liste des permis possible
    public static function GetListePermis() {
        return BD::Prepare('SELECT * FROM PERMIS', array(), BD::RECUPERER_TOUT);
    }

    //Recupération de la liste statuts maritals possible
    public static function GetListeStatutMarital() {
        return BD::Prepare('SELECT * FROM STATUT_MARITAL', array(), BD::RECUPERER_TOUT);
    }

    //****************  Getters & Setters  ******************//
    public function getId() {
        return $this->ID_ETUDIANT;
    }

    public function getNom() {
        return $this->NOM;
    }

    public function getPrenom() {
        return $this->PRENOM;
    }

    public function getSexe() {
        return $this->SEXE_ETUDIANT;
    }

    public function getAdresse1() {
        return $this->ADRESSE1_ETUDIANT;
    }

    public function getAdresse2() {
        return $this->ADRESSE2_ETUDIANT;
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

    public function getTel() {
        return $this->TEL_ETUDIANT;
    }

    public function getMail() {
        return $this->MAIL_ETUDIANT;
    }

    public function getAnniv() {
        return $this->ANNIV_ETUDIANT;
    }

    public function getIdMarital() {
        return $this->ID_MARITAL;
    }

    public function getNomMarital() {
        return $this->LIBELLE_MARITAL;
    }

    public function getIdPermis() {
        return $this->ID_PERMIS;
    }

    public function getNomPermis() {
        return $this->LIBELLE_PERMIS;
    }

    public function getPhotos() {
        return $this->PHOTO_ETUDIANT;
    }

    public function getIdCV() {
        return $this->ID_CV;
    }

    public function getCV() {
        if ($this->cv == NULL) {
            $this->cv = CV::GetCVByID($this->ID_CV);
        }
        return $this->cv;
    }

    public function getDiplome() {
        if ($this->diplome == NULL) {
            $this->diplome = CV_Diplome::GetDiplomeByIdCV($this->ID_CV);
        }
        return $this->diplome;
    }

    public function getFormation() {
        if ($this->formation == NULL) {
            $this->formation = CV_Formation::GetFormationByIdCV($this->ID_CV);
        }
        return $this->formation;
    }

    public function getLangue() {
        if ($this->langue == NULL) {
            $this->langue = CV_Langue::GetLangueByIdCV($this->ID_CV);
        }
        return $this->langue;
    }

    public function getXP() {
        if ($this->xp == NULL) {
            $this->xp = CV_XP::GetCVXPByIdCV($this->ID_CV);
        }
        return $this->xp;
    }

    public function getCompetence() {
        if ($this->competence == NULL) {
            $this->competence = CV_Competence::GetCompetenceByIdCV($this->ID_CV);
        }
        return $this->competence;
    }

}

?>
