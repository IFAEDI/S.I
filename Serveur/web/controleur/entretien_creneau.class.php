<?php

require_once dirname(__FILE__) . '/../commun/php/base.inc.php';
inclure_fichier('commun', 'bd.inc', 'php');

class Entretien {

    //****************  Attributs  ******************//
    private $ID;
    private $ID_ENTRETIEN;
    private $ID_INTERVENANT;
    private $HEURE_DEBUT;
    private $HEURE_FIN;
	private $ID_ETUDIANT;


    //****************  Fonctions statiques  ******************//
    // Récuperation de l'objet Entreprise par l'ID
    public static function GetCreneauByID($_id) {
        if (is_numeric($_id)) {
            return BD::Prepare('SELECT * FROM Entretien_creneau WHERE ID = :id', array('id' => $_id), BD::RECUPERER_UNE_LIGNE, PDO::FETCH_CLASS, __CLASS__);
        }
        return NULL;
    }

	// Récuperation de l'ensemble des creneau d'une journée passée en paramètre (ex: 27/03/2012)
	public static function GetListeCreneauByDate(date) {
        return BD::Prepare('SELECT * FROM Entretien_creneau WHERE id = (SELECT id FROM Entretien WHERE date ="'date'"'), array(), BD::RECUPERER_TOUT);
    }

	// Ajout ($_id <= 0) ou édition ($_id > 0) d'un creneau
    public static function UpdateCreneau($_id, $_id_entretien $_id_intervenant, $_heure_debut, $_heure_fin, $_id_etudiant){

		$info = array(
			'id'=> $_id,
			'id_entretien'=>$_id_entretien,
			'id_intervenant'=>$_id_intervenant,
			'heure_debut'=>$_heure_debut,
			'heure_fin'=>$_heure_fin,
			'id_etudiant'=>$_id_etudiant
		);   
		
        if ($_id > 0 && is_numeric($_id)) {

            //Si l'etudiant à déjà un CV
            BD::Prepare('UPDATE Entretien_creneau SET 
                    ID_ENTRETIEN = :id_entretien,
                    ID_INTERVENANT = :id_intervenant,
                    HEURE_DEBUT = :heure_debut,
					HEURE_FIN = :heure_fin,
                    ID_ETUDIANT = :id_etudiant,
                    WHERE ID = :id', $info);
            return $_id;
        } else {
            BD::Prepare('INSERT INTO Entretien_creneau SET 
					ID = :id,
                    ID_ENTRETIEN = :id_entretien,
                    ID_INTERVENANT = :id_intervenant,
                    HEURE_DEBUT = :heure_debut,
					HEURE_FIN = :heure_fin,
                    ID_ETUDIANT = :id_etudiant'
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

    public function getIdEntretien() {
        return $this->ID_ENTRETIEN;
    }

    public function getIdIntervenant() {
        return $this->ID_INTERVENANT;
    }
    
    public function getHeureDebut() {
        return $this->HEURE_DEBUT;
    }

    public function getHeureFin() {
        return $this->HEURE_FIN;
    }
	
	public function getIdEtudiant() {
        return $this->ID_ETUDIANT;
    }

}

?>
