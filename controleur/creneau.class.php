<?php

require_once dirname(__FILE__) . '/../commun/php/base.inc.php';
inclure_fichier('commun', 'bd.inc', 'php');

class Creneau {

    //****************  Attributs  ******************//
    private $ID_CRENEAU;
    private $ID_ENTRETIEN;
    private $DEBUT;
    private $FIN;
	private $ID_ETUDIANT;


    //****************  Fonctions statiques  ******************//
    // Récuperation de l'objet Creneau par l'ID
    public static function GetCreneauByID($_id) {
        if (is_numeric($_id)) {
            return BD::Prepare('SELECT * FROM Creneau WHERE ID = :id', array('id' => $_id), BD::RECUPERER_UNE_LIGNE, PDO::FETCH_CLASS, __CLASS__);
        }
        return NULL;
    }

	// Récuperation de l'ensemble des creneau d'une journée passée en paramètre (ex: 27/03/2012) et etat = 1 valide
	public static function GetListeCreneauxByDate($date) {
        return BD::Prepare('SELECT e.nom, cr.id_creneau, cr.debut, cr.fin, cr.id_etudiant
							FROM Creneau cr, Entreprise e, Contact_entreprise c, Entretien et
							WHERE e.id_entreprise = c.id_entreprise
							AND et.id_contact = c.id_contact
							AND cr.id_entretien = et.id_entretien
							AND et.etat =1 AND et.date = :date
							ORDER BY e.nom AND cr.debut', array('date'=>$date), BD::RECUPERER_TOUT);
    }

	// Ajout ($_id <= 0) ou édition ($_id > 0) d'un creneau
    public static function UpdateCreneau($_id, $_id_entretien, $_debut, $_fin, $_id_etudiant){

		$info = array(
			'id_creneau'=> $_id,
			'id_entretien'=>$_id_entretien,
			'debut'=>$_debut,
			'fin'=>$_fin,
			'id_etudiant'=>$_id_etudiant
		);   
		
        if(is_numeric($_id) && $_id > 0) {

            //Si l'etudiant à déjà un CV
            BD::Prepare('UPDATE Creneau SET 
                    ID_ENTRETIEN = :id_entretien,
                    DEBUT = :debut,
					FIN = :fin,
                    ID_ETUDIANT = :id_etudiant,
                    WHERE ID_CRENEAU = :id_creneau', $info);
            return $_id;
        } else {
            BD::Prepare('INSERT INTO Creneau SET 
					ID_CRENEAU = :id_creneau,
                    ID_ENTRETIEN = :id_entretien,
                    DEBUT = :debut,
					FIN = :fin,
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
	
	// Reservation d'un creneau par un etudiant
    public static function ReserverCreneau($_id_creneau, $_id_etudiant){
		$info = array(
			'id_creneau'=> $_id_creneau,
			'id_etudiant'=>$_id_etudiant
		);
		
		// On remplit le creneau par l'id de l'etudiant correspondant
		BD::Prepare('UPDATE Creneau SET
                    ID_ETUDIANT = :id_etudiant
                    WHERE ID_CRENEAU = :id_creneau', $info);
		return $_id_creneau;
	}	
	
    //****************  Fonctions  ******************//

    //****************  Getters & Setters  ******************//
    public function getId() {
        return $this->ID;
    }

    public function getIdEntretien() {
        return $this->ID_ENTRETIEN;
    }
    
    public function getHeureDebut() {
        return $this->DEBUT;
    }

    public function getHeureFin() {
        return $this->FIN;
    }
	
	public function getIdEtudiant() {
        return $this->ID_ETUDIANT;
    }

}

?>
