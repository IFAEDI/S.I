<?php

require_once dirname(__FILE__) . '/../commun/php/base.inc.php';
inclure_fichier('commun', 'bd.inc', 'php');

class Entretien {

    //****************  Attributs  ******************//
    private $ID;
    private $ENTREPRISE;
    private $VILLE;
    private $NOM_CONTACT;
	private $PRENOM_CONTACT;
	private $MAIL_CONTACT;
	private $TEL_CONTACT;
	private $DATE;


    //****************  Fonctions statiques  ******************//
    // Récuperation de l'objet Entretien par l'ID
    public static function GetEntretienByID($_id) {
        if (is_numeric($_id)) {
            return BD::Prepare('SELECT * FROM Entretien WHERE ID = :id', array('id' => $_id), BD::RECUPERER_UNE_LIGNE, PDO::FETCH_CLASS, __CLASS__);
        }
        return NULL;
    }

	// Récuperation des ids et noms de l'ensemble des entretien
	public static function GetListeEntretien() {
        return BD::Prepare('SELECT * FROM Entretien', array(), BD::RECUPERER_TOUT);
    }

	// Suppression d'un entretien par ID
    public static function SupprimerEntretienByID($_id) {
        if (is_numeric($_id)) {
            BD::Prepare('DELETE FROM Entretien WHERE ID = :id', array('id' => $_id));
        }
    }

	// Ajout ($_id <= 0) ou édition ($_id > 0) d'un entretien
    public static function UpdateEntretien($_id, $_entreprise, $_ville, $_nom_contact, $_prenom_contact, $_mail_contact, $_tel_contact, $_date){

		$info = array(
			'id'=> $_id,
			'entreprise'=>$_entreprise,
			'ville'=>$_ville,
			'nom_contact'=>$_nom_contact,
			'prenom_contact'=>$_prenom_contact,
			'mail_contact'=>$_mail_contact,
			'tel_contact'=>$_tel_contact,
			'date'=>$_date
		);
		
        if ($_id > 0 && is_numeric($_id)) {
            //Si l'etudiant à déjà un CV
            BD::Prepare('UPDATE Entretien SET 
                    ENTREPRISE = :entreprise,
                    VILLE = :ville,
					NOM_CONTACT = :nom_contact,
                    PRENOM_CONTACT = :prenom_contact,
                    MAIL_CONTACT = :mail_contact,
                    TEL_CONTACT = :tel_contact,
                    DATE = :date
                    WHERE ID = :id', $info);
              BD::MontrerErreur();
			return $_id;
        } else {
            BD::Prepare('INSERT INTO Entretien SET 
					ID = :id,
                    ENTREPRISE = :entreprise,
                    VILLE = :ville,
					NOM_CONTACT = :nom_contact,
                    PRENOM_CONTACT = :prenom_contact,
                    MAIL_CONTACT = :mail_contact,
                    TEL_CONTACT = :tel_contact,
                    DATE = :date
					', $info);

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

    public function getEntreprise() {
        return $this->ENTREPRISE;
    }

    public function getVille() {
        return $this->VILLE;
    }
    
    public function getNomContact() {
        return $this->NOM_CONTACT;
    }

    public function getPrenomContact() {
        return $this->PRENOM_CONTACT;
    }
	
	public function getMailContact() {
        return $this->MAIL_CONTACT;
    }

	public function getTelContact() {
        return $this->TEL_CONTACT;
    }
	
    public function getDate() {
        return $this->DATE;
    }

}

?>
