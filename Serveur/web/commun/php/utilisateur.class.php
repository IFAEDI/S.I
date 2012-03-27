<?php

require_once dirname(__FILE__) . '/base.inc.php';
inclure_fichier('commun', 'bd.inc', 'php');

class Utilisateur {

	//****************  Attributs  ******************//
	private $id;
	private $login;
	private $nom;
	private $prenom;
	private $annee;
	private $mail;


	/**
	* Constructeur
	* $login : chaîne de caractère contenant le nom de l'utilisateur à créer
	*/
	public function __construct( $login ) {
        
		/* Requête à la base pour récupérer le bon utilisateur et construire l'objet */
		$result = BD::Prepare( 'SELECT * FROM UTILISATEUR WHERE login = :login', array( 'login' => $login ), BD::RECUPERER_UNE_LIGNE );

		/* Si l'objet n'a pas pu être créé, exception */
		if( $result == null ) {
			throw new Exception( 'Impossible de construire l\'utilisateur (erreur de bdd).' );
		}

		$this->nom = $result['nom'];
		$this->prenom = $result['prenom'];
		$this->annee = $result['annee'];
		$this->mail = $result['mail'];
	}

	public function estEtudiant() {
        	//TODO
	        return true;
    	}

    public function estEntreprise() {
        //TODO
        return true;
    }

    //****************  Getters & Setters  ******************//
    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getAnnee() {
        return $this->annee;
    }

    public function getMail() {
        return $this->mail;
    }
	
}

?>
