<?php
/**
 * -----------------------------------------------------------
 * Commentaire - CLASSE PHP
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Commentaire - benjamin.planche@aldream.net
 * ---------------------
 * Modèle associé à la table Commentaire
 */
 
require_once dirname(__FILE__) . '/../commun/php/base.inc.php';
inclure_fichier('commun', 'bd.inc', 'php');

inclure_fichier('modele', 'personne.class', 'php');
inclure_fichier('modele', 'ville.class', 'php');
inclure_fichier('modele', 'entreprise.class', 'php');

class CommentaireEntreprise {

	//****************  Constantes  ******************//
	public static $ERREUR_EXEC_REQUETE = -10;
	public static function getErreurExecRequete() { return self::$ERREUR_EXEC_REQUETE; }
	
	public static $ERREUR_CHAMP_INCONNU = -20;
	public static function getErreurChampInconnu() { return self::$ERREUR_CHAMP_INCONNU; }
	
	//****************  Attributs  ******************//
	private $ID_COMMENTAIRE;
	private $ID_PERSONNE;
	private $ID_ENTREPRISE;
	private $DATE;
	private $CONTENU;
	private $CATEGORIE;

	private $entreprise;
	private $personne;


	//****************  Fonctions statiques  ******************//
	/**
	* Récuperation de l'objet CommentaireEntreprise par l'ID
	* $_id : L'identifiant du CommentaireEntreprise à récupérer
	* @return L'instance du CommentaireEntreprise, ou null
	* @throws Une exeception si une erreur est survenue au niveau de la base
	*/
	public static function GetCommentaireByID(/* int */ $_id) {

		if (is_numeric($_id)) {
			return BD::executeSelect('SELECT * FROM COMMENTAIRE_ENTREPRISE WHERE ID_COMMENTAIRE = :id', array('id' => $_id), 
						BD::RECUPERER_UNE_LIGNE, PDO::FETCH_CLASS, __CLASS__);
		}

		return NULL;
	}

	/**
	* Récuperation l'ensemble des CommentaireEntreprise, ordonnés par date décroissante
	* @return Un tableau d'instance ou null
	* @throws Une exception en cas d'erreur provenant de la base.
	*/
	public static function GetListeCommentaires() {

		$obj = array();

                /* Tentative de sélection de tous les ID ordonnés par date décroissante */
                $result = BD::executeSelect( 'SELECT ID_COMMENTAIRE FROM COMMENTAIRE_ENTREPRISE ORDER BY DATE DESC', array(), BD::RECUPERER_TOUT );
                if( $result == null ) {
                        return null;
                }

                /* Pour chacun, on construit l'objet */
                $i = 0;
                foreach( $result as $row ) {
                        $obj[$i] = self::GetCommentaireByID( $row['ID_COMMENTAIRE'] );
                        $i++;
                }

                return $obj;
	}
	
	/**
	* Récuperation des données de l'ensemble des Commentaires, ordonné alphabétiquement par date décroissante, pour une entreprise donnée
	* $_idEntreprise : l'identifiant de l'entreprise 
	* $_entreprise : instance de l'entreprise qui sera associée au commentaire (optionnel)
	* @return Un tableau d'instance
	* @throws Une exception en cas d'erreur de la part de la BD
	*/
	public static function GetListeCommentairesParEntreprise(/* int */ $_idEntreprise, /*Entreprise*/ $_entreprise = null ) {

		$result = BD::executeSelect('SELECT ID_COMMENTAIRE FROM COMMENTAIRE_ENTREPRISE WHERE ID_ENTREPRISE = :idEntreprise ORDER BY DATE DESC', 
						array('idEntreprise' => $_idEntreprise), BD::RECUPERER_TOUT);
		if( $result == null ) {
			return null;
		}

		/* Pour chacun, on construit l'objet */
		$obj = array();
		$i = 0;
		foreach( $result as $row ) {

			$obj[$i] = self::GetCommentaireByID( $row['ID_COMMENTAIRE'] );
			$obj[$i]->entreprise = $_entreprise; // On fixe l'entreprise, comme ça, ça évitera de faire de la requête plus tard
			$i++;
		}

		return $obj;
	}

	/**
	* Suppression d'un commentaire par son ID
	* $_id : L'identifiant du commentaire à supprimer
	* @return True si tout est ok, false sinon
	* @throws Une exception en cas d'erreur au niveau de la BDD
	*/
	public static function SupprimerCommentaireByID(/* int */ $_id) {

		if( !is_numeric($_id) ) {
			return false;
		}

		try {
			$result = BD::executeModif( 'DELETE FROM COMMENTAIRE_ENTREPRISE WHERE ID_COMMENTAIRE = :id', array('id' => $_id));
		}
		catch (Exception $e) {
			return CommentaireEntreprise::getErreurExecRequete();
		}

		return $result;
	}

	/**
	* Ajout ($_id <= 0) ou édition ($_id > 0) d'un Commentaire
	* $_id : L'identifiant du commentaire à mettre à jour ( ou <= 0 si il doit être créé )
	* $_personne : ID d'une personne à attacher
	* $_entreprise : ID d'une entreprise à attacher
	* @return Le nouvel identifiant si tout est ok, false sinon
	* @throws Une exception si une erreur est survenue au niveau de la BDD
	*/
	public static function UpdateCommentaire( $_id, $_personne, $_entreprise, $_contenu, $_categorie, $_date) {

		/* Il faut impérativement une instance entreprise et une instance de personne passée en paramètre */
		if( $_entreprise == null || $_personne == null ) {
			return CommentaireEntreprise::getErreurChampInconnu();
		}
		$result = 0;
		
		/* Mise à jour du commentaire */
		if( $_id > 0 ) {
	
			/* Préparation du tableau associatif */
			$info = array( 'id' => $_id, 'idPersonne' => $_personne, 'idEntreprise' => $_entreprise, 
				'contenu' => $_contenu, 'categorie' => $_categorie, 'date' => $_date);

			/* Execution de la requête */
			try {
				$result = BD::executeModif( 'UPDATE COMMENTAIRE_PERSONNE SET ID_PERSONNE = :idPersonne, ID_ENTREPRISE = idEntreprise, CONTENU = :contenu, CATEGORIE = :categorie, DATE = :date WHERE ID_COMMENTAIRE = :id', $info );
			}
			catch (Exception $e) {
				return CommentaireEntreprise::getErreurExecRequete();
			}
		}
		/* Ajout d'un nouveau commentaire */
		else {
			
			/* Préparation du tableau associatif */
			$info = array('idPersonne' => $_personne, 'idEntreprise' => $_entreprise, 
				'contenu' => $_contenu, 'categorie' => $_categorie);

			/* Execution de la requête */
			try {
				$result = BD::executeModif( 'INSERT INTO COMMENTAIRE_ENTREPRISE(ID_PERSONNE, ID_ENTREPRISE, CONTENU, CATEGORIE) VALUES( :idPersonne, :idEntreprise, :contenu, :categorie)', $info );
			}
			catch (Exception $e) {
				return CommentaireEntreprise::getErreurExecRequete();
			}

			$result = BD::GetConnection()->lastInsertId();

		}

		return $result;
	}

    //****************  Fonctions  ******************//


    //****************  Getters & Setters  ******************//
    public function getId() {
        return $this->ID_COMMENTAIRE;
    }

    public function getContenu() {
        return $this->CONTENU;
    }
	
    public function getCategorie() {
        return $this->CATEGORIE;
    }

    public function getTimestamp() {
        return $this->DATE;
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


	
	public function toArrayObject($avecEntreprise, $avecMails, $avecTels, $avecRole, $avec1ereConnexion, $avecPersonne) {
		$arrayCommentaire = array();
		$arrayCommentaire['id_commentaire'] = intval($this->ID_COMMENTAIRE);
		$arrayCommentaire['contenu'] = $this->CONTENU;
		$arrayCommentaire['categorie'] = $this->CATEGORIE;
		$arrayCommentaire['timestamp'] = $this->DATE;
		$arrayCommentaire['personne'] = $this->getPersonne()->toArrayObject($avecMails, $avecTels, $avecRole, $avec1ereConnexion, $avecPersonne);
		if ($avecEntreprise) {
			$arrayCommentaire['entreprise'] = $this->getEntreprise()->toArrayObject();
		}
		return $arrayCommentaire;
	}
	
}

?>
