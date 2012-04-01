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

inclure_fichier('commun', 'personne.class', 'php');
inclure_fichier('controleur', 'ville.class', 'php');
inclure_fichier('controleur', 'entreprise.class', 'php');

class Contact {

	//****************  Attributs  ******************//
	private $ID_CONTACT;
	private $ID_ENTREPRISE;
	private $ID_PERSONNE;
	private $ID_VILLE;
	private $FONCTION;
	private $COMMENTAIRE;
	private $PRIORITE;

	private $entreprise;
	private $personne;
	private $ville;


	//****************  Fonctions statiques  ******************//
	/**
	* Récuperation de l'objet Contact par l'ID
	* $_id : L'identifiant du contact à récupérer
	* @return L'instance du contact, ou null
	* @throws Une exeception si une erreur est survenue au niveau de la base
	*/
	public static function GetContactByID(/* int */ $_id) {

		if (is_numeric($_id)) {
			return BD::executeSelect('SELECT * FROM CONTACT_ENTREPRISE WHERE ID_CONTACT = :id', array('id' => $_id), 
						BD::RECUPERER_UNE_LIGNE, PDO::FETCH_CLASS, __CLASS__);
		}

		return NULL;
	}

	/**
	* Récuperation l'ensemble des Contacts, ordonnés par priorité
	* @return Un tableau d'instance ou null
	* @throws Une exception en cas d'erreur provenant de la base.
	*/
	public static function GetListeContacts() {

		$obj = array();

                /* Tentative de sélection de tous les ID ordonnés par priorité */
                $result = BD::executeSelect( 'SELECT ID_CONTACT FROM CONTACT_ENTREPRISE ORDER BY PRIORITE', array(), BD::RECUPERER_TOUT );
                if( $result == null ) {
                        return null;
                }

                /* Pour chacun, on construit l'objet */
                $i = 0;
                foreach( $result as $row ) {
                        $obj[$i] = self::GetContactByID( $row['ID_CONTACT'] );
                        $i++;
                }

                return $obj;
	}
	
	/**
	* Récuperation des données de l'ensemble des Contacts, ordonné alphabétiquement par priorité, pour une entreprise donnée
	* $_idEntreprise : l'identifiant de l'entreprise 
	* $_entreprise : instance de l'entreprise qui sera associée au contact (optionnel)
	* @return Un tableau d'instance
	* @throws Une exception en cas d'erreur de la part de la BD
	*/
	public static function GetListeContactsParEntreprise(/* int */ $_idEntreprise, /*Entreprise*/ $_entreprise = null ) {

		$result = BD::executeSelect('SELECT ID_CONTACT FROM CONTACT_ENTREPRISE WHERE ID_ENTREPRISE = :idEntreprise ORDER BY PRIORITE DESC', 
						array('idEntreprise' => $_idEntreprise), BD::RECUPERER_TOUT);
		if( $result == null ) {
			return null;
		}

		/* Pour chacun, on construit l'objet */
		$obj = array();
		$i = 0;
		foreach( $result as $row ) {

			$obj[$i] = self::GetContactByID( $row['ID_CONTACT'] );
			$obj[$i]->entreprise = $_entreprise; // On fixe l'entreprise, comme ça, ça évitera de faire de la requête plus tard
			$i++;
		}

		return $obj;
	}

	/**
	* Suppression d'un contact par son ID
	* $_id : L'identifiant du contact à supprimer
	* @return True si tout est ok, false sinon
	* @throws Une exception en cas d'erreur au niveau de la BDD
	*/
	public static function SupprimerContactByID(/* int */ $_id) {

		if( !is_numeric($_id) ) {
			return false;
		}

		$result = BD::executeModif( 'DELETE FROM CONTACT_ENTREPRISE WHERE ID_CONTACT = :id', array('id' => $_id));
		if( $result == 0 ) {
			return false;
		}

		return true;
	}

	/**
	* Ajout ($_id <= 0) ou édition ($_id > 0) d'un Contact
	* $_id : L'identifiant du contact à mettre à jour ( ou <= 0 si il doit être créé )
	* $_personne : Instance d'une personne à attacher
	* $_entreprise : Instance d'une entreprise à attacher
	* @return Le nouvel identifiant si tout est ok, false sinon
	* @throws Une exception si une erreur est survenue au niveau de la BDD
	*/
	public static function UpdateContact( $_id, $_personne, $_entreprise, $_ville, $_fonction, $_com = '', $_priorite = 0) {

		/* Il faut impérativement une instance entreprise et une instance de personne passée en paramètre */
		if( $_entreprise == null || $_personne == null ) {
			return -1;
		}

		/* Mise à jour du contact */
		if( $_id > 0 ) {
	
			/* Préparation du tableau associatif */
			$info = array( 'id' => $_id, 'idPersonne' => $_personne->getId(), 'idEntreprise' => $_entreprise->getId(), 'idVille' => $_ville->getId(), 'fonction' => $_fonction, 'commentaire' => $_com, 'priorite' => $_priorite );

			/* Execution de la requête */
			$result = BD::executeModif( 'UPDATE CONTACT_ENTREPRISE SET
				ID_PERSONNE = :idPersonne,
				ID_ENTREPRISE = :idEntreprise,
				ID_VILLE = :idVille,
				FONCTION = :fonction,
				COMMENTAIRE = :commentaire,
				PRIORITE = :priorite
				WHERE ID_CONTACT = :id', $info );

			if( $result == 0 ) {
				return -1;
			}
			
			return 0;
		}
		/* Ajout d'un nouveau contact */
		else {
			
			/* Préparation du tableau associatif */
			$info = array( 'idPersonne' => $_personne->getId(), 'idEntreprise' => $_entreprise->getId(), 
				'idVille' => $_ville->getId(), 'fonction' => $_fonction, 'commentaire' => $_com, 'priorite' => $_priorite );

			/* Execution de la requête */
			$result = BD::executeModif( 'INSERT INTO CONTACT_ENTREPRISE(ID_PERSONNE, ID_ENTREPRISE, ID_VILLE, FONCTION, COMMENTAIRE, PRIORITE) VALUES( :idPersonne, :idEntreprise, :idVille, :fonction, :commentaire, :priorite )', $info );
			if( $result == 0 ) {
				return -1;
			}

			/* Récupération de l'identifiant du nouveau contact */
			$_id = BD::getConnection()->lastInsertId();
		}

		return $_id;
	}

    //****************  Fonctions  ******************//


    //****************  Getters & Setters  ******************//
    public function getId() {
        return $this->ID_CONTACT;
    }

    public function getFonction() {
        return $this->FONCTION;
    }
	
    public function getPriorite() {
        return $this->PRIORITE;
    }

    public function getCommentaire() {
        return $this->COMMENTAIRE;
    }

    public function getEntreprise() {

	/* Si l'objet n'est pas instancié, on le fait */
	if( $this->entreprise == null ) {
		$this->entreprise = Entreprise::GetEntrepriseByID( $this->ID_ENTREPRISE );
	}

        return $this->entreprise;
    }
	
    public function getPersonne() {

	/* Si l'objet n'est pas instancié, on le fait */
	if( $this->personne == null ) {
		$this->personne = Personne::GetPersonneParID( $this->ID_PERSONNE );
	}

        return $this->personne;
    }

    public function getVille() {

	/* Si l'objet n'est pas instancié, même rangaine */
	if( $this->ville == null ) {
		$this->ville = new Ville( $this->ID_VILLE );
	}

	return $this->ville;
    }
	
	public function toArrayObject($avecEntreprise, $avecVille, $avecMails, $avecTels, $avecRole, $avec1ereConnexion, $avecUtilisateur) {
		$arrayContact = array();
		$arrayContact['id_contact'] = intval($this->ID_CONTACT);
		$arrayContact['fonction'] = $this->FONCTION;
		$arrayContact['priorite'] = $this->PRIORITE;
		$arrayContact['commentaire'] = $this->COMMENTAIRE;
		$arrayContact['personne'] = $this->getPersonne()->toArrayObject($avecMails, $avecTels, $avecRole, $avec1ereConnexion, $avecUtilisateur);
		if ($avecVille) {
			$arrayContact['ville'] = $this->getVille()->toArrayObject();
		}
		if ($avecEntreprise) {
			$arrayContact['entreprise'] = $this->getEntreprise()->toArrayObject();
		}
		return $arrayContact;
	}
	
}

?>
