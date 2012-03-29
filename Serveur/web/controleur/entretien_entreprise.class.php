<?php

require_once dirname(__FILE__) . '/../commun/php/base.inc.php';
inclure_fichier('commun', 'bd.inc', 'php');

class Entreprise {

    //****************  Attributs  ******************//
    private $ID;
    private $NOM;
    private $DESCRIPTION;
    private $SECTEUR;
	private $COMMENTAIRE;
	private $ID_VILLE;



    //****************  Fonctions statiques  ******************//
    // Récuperation de l'objet Entreprise par l'ID
    public static function GetEntrepriseByID($_id) {
        if (is_numeric($_id)) {
            return BD::Prepare('SELECT * FROM Entretien_entreprise WHERE ID = :id', array('id' => $_id), BD::RECUPERER_UNE_LIGNE, PDO::FETCH_CLASS, __CLASS__);
        }
        return NULL;
    }

	// Récuperation des ids et noms de l'ensemble des entreprises, ordonné alphabétiquement
	public static function GetListeEntreprises() {
        return BD::Prepare('SELECT ID, NOM FROM Entretien_entreprise ORDER BY NOM', array(), BD::RECUPERER_TOUT);
    }

	// Suppression d'une entreprise par ID
    public static function SupprimerEntrepriseByID($_id) {
        if (is_numeric($_id)) {
            BD::Prepare('DELETE FROM Entretien_entreprise WHERE ID = :id', array('id' => $_id));
        }
    }

	// Ajout ($_id <= 0) ou édition ($_id > 0) d'une entreprise
    public static function UpdateEntreprise($_id, $_nom, $_desc, $_secteur, $_com, $_id_ville) {
		$info = array(
			'id' => $_id,
			'nom' => $_titre_cv,
			'description' => $_desc,
			'secteur' => $_secteur,
			'commentaire' => $_com,
			'id_ville' => $_id_ville,
		);
        if ($_id > 0 && is_numeric($_id)) {
            

            //Si l'etudiant à déjà un CV
            BD::Prepare('UPDATE Entretien_entreprise SET 
                    NOM = :nom,
                    DESCRIPTION = :description,
                    LOISIR = :loisir,
					SECTEUR = :secteur,
                    COMMENTAIRE = :commentaire,
                    ID_VILLE = :id_ville
                    WHERE ID = :id', $info);
            return $_id;
        } else {
            BD::Prepare('INSERT INTO Entretien_entreprise SET 
                    NOM = :nom,
                    DESCRIPTION = :description,
                    LOISIR = :loisir,
					SECTEUR = :secteur,
                    COMMENTAIRE = :commentaire,
                    ID_VILLE = :id_ville'
                    , $info);

            $id = BD::GetConnection()->lastInsertId();
            if ($id > 0) {
                return $id;
            } else {
                echo "Erreur 2 veuillez contacter l'administrateur du site";
                return;
            }
        }
    }

    //****************  Fonctions  ******************//


    //****************  Getters & Setters  ******************//
    public function getId() {
        return $this->ID;
    }

    public function getNom() {
        return $this->NOM;
    }

    public function getDescription() {
        return $this->DESCRIPTION;
    }
    
    public function getSecteur() {
        return $this->SECTEUR;
    }

    public function getCommentaire() {
        return $this->COMMENTAIRE;
    }

    public function getIdVille() {
        return $this->ID_VILLE;
    }

}

?>
