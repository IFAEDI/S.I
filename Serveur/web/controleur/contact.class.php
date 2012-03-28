<?php
/**
 * -----------------------------------------------------------
 * Contact - CLASSE PHP
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Contact - benjamin.planche@aldream.net
 * ---------------------
 * Controleur associé à la table Contact
 */
 
require_once dirname(__FILE__) . '/../commun/php/base.inc.php';
inclure_fichier('commun', 'bd.inc', 'php');

class Contact {

    //****************  Attributs  ******************//
    private $ID;
	private $ID_UTILISATEUR;
	private $ID_ENTREPRISE;
    private $NOM;
    private $PRENOM;
    private $METIER;
	private $COMMENTAIRE;
	private $PRIORITE;



    //****************  Fonctions statiques  ******************//
    // Récuperation de l'objet Contact par l'ID
    public static function GetContactByID($_id) {
        if (is_numeric($_id)) {
            return BD::Prepare('SELECT * FROM Contact WHERE ID = :id', array('id' => $_id), BD::RECUPERER_UNE_LIGNE, PDO::FETCH_CLASS, __CLASS__);
        }
        return NULL;
    }

	// Récuperation des données de l'ensemble des Contacts, ordonné alphabétiquement
	public static function GetListeContacts() {
        return BD::Prepare('SELECT * FROM Contact ORDER BY NOM', array(), BD::RECUPERER_TOUT);
    }

	// Suppression d'une Contact par ID
    public static function SupprimerContactByID($_id) {
        if (is_numeric($_id)) {
            BD::Prepare('DELETE FROM Contact WHERE ID = :id', array('id' => $_id));
        }
    }

	// Ajout ($_id <= 0) ou édition ($_id > 0) d'une Contact
    public static function UpdateContact($_id, $_idUtilisateur, $_idEntreprise, $_nom, $_prenom, $_metier, $_com, $_priorite) {
		$info = array(
			'id' => $_id,
			'idUtilisateur' => $_idUtilisateur,
			'idEntreprise' => $_idEntreprise,
			'nom' => $_nom,
			'prenom' => $_prenom,
			'metier' => $_metier,
			'commentaire' => $_com,
			'priorite' => $_priorite,
		);
        if ($_id > 0 && is_numeric($_id)) {
            

            //Si l'etudiant à déjà un CV
            BD::Prepare('UPDATE Contact SET 
					ID_UTILISATEUR	:idUtilisateur,
					ID_ENTREPRISE	:idEntreprise,
                    NOM = 			:nom,
                    PRENOM = 		:prenom,
                    METIER = 		:metier,
					PRIORITE = 		:priorite,
                    COMMENTAIRE = 	:commentaire,
                    WHERE ID = 		:id', $info);
            return $_id;
        } else {
            BD::Prepare('INSERT INTO Contact SET 
					ID_UTILISATEUR	:idUtilisateur,
					ID_ENTREPRISE	:idEntreprise,
                    NOM = 			:nom,
                    PRENOM = 		:prenom,
                    METIER = 		:metier,
					PRIORITE = 		:priorite,
                    COMMENTAIRE = 	:commentaire,
                    WHERE ID = 		:id', $info);

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

    public function getPrenom() {
        return $this->DESCRIPTION;
    }
    
    public function getMetier() {
        return $this->SECTEUR;
    }
	
    public function getPriorite() {
        return $this->PRIORITE;
    }
    public function getCommentaire() {
        return $this->COMMENTAIRE;
    }

    public function getIdEntreprise() {
        return $this->ID_ENTREPRISE;
    }
	
	public function getIdUtilisateur() {
        return $this->ID_UTILISATEUR;
    }
	
}

?>
