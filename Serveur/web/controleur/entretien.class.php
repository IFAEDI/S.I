<?php

require_once dirname(__FILE__) . '/../commun/php/base.inc.php';
inclure_fichier('commun', 'bd.inc', 'php');

class Entretien {

    //****************  Attributs  ******************//
    private $ID;
    private $ID_ENTREPRISE;
    private $VILLE;
    private $ID_CONTACT;
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
    public static function UpdateEntretien($_id, $_id_entreprise, $_ville, $_id_contact, $_date){

		$info = array(
			'id'=> $_id,
			'id_entreprise'=>$_id_entreprise,
			'ville'=>$_ville,
			'id_contact'=>$_id_contact,
			'date'=>$_date
		);
		
        if ($_id < 0 && is_numeric($_id)) {
			echo 'coucou';
            //Si l'etudiant à déjà un CV
            BD::executeModif('UPDATE Entretien SET 
                    ID_ENTREPRISE = :id_entreprise,
                    VILLE = :ville,
					ID_CONTACT = :id_contact,
                    DATE = :date
                    WHERE ID = :id', $info);
              BD::MontrerErreur();
			return $_id;
        } else {
			
            $retour = BD::executeModif('INSERT INTO Entretien SET 
					ID = :id,
                    ID_ENTREPRISE = :id_entreprise,
                    VILLE = :ville,
					ID_CONTACT = :id_contact,
                    DATE = :date
					', $info);

            if ($retour != null ) {
                echo $retour;
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
