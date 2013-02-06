<?php
/**
 * -----------------------------------------------------------
 * Contact - CLASSE PHP
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Contact - benjamin.planche@aldream.net
 * ---------------------
 * Modèle associé à la table Contact
 */
 
require_once dirname(__FILE__) . '/../commun/php/base.inc.php';
inclure_fichier('commun', 'bd.inc', 'php');

inclure_fichier('modele', 'personne.class', 'php');
inclure_fichier('modele', 'ville.class', 'php');
inclure_fichier('modele', 'entreprise.class', 'php');

class Contact {

	//****************  Constantes  ******************//
	public static $ERREUR_EXEC_REQUETE = -10;
	public static function getErreurExecRequete() { return self::$ERREUR_EXEC_REQUETE; }
	
	public static $ERREUR_CHAMP_INCONNU = -20;
	public static function getErreurChampInconnu() { return self::$ERREUR_CHAMP_INCONNU; }
	
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
	
	
	public static function GetContactParNom(/* string */ $_nom ,$_prenom) {

		return BD::executeSelect('SELECT ID_CONTACT FROM CONTACT_ENTREPRISE c, PERSONNE p
					WHERE c.ID_PERSONNE = p.ID_PERSONNE AND
					p.NOM = :nom AND p.prenom = :prenom', array('nom' => $_nom, 'prenom' => $_prenom), 
					BD::RECUPERER_UNE_LIGNE, PDO::FETCH_CLASS, __CLASS__);

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
	 * Recherche des contacts appropriés selon des mots clés donnés.
	 *
	 * $listeElementsRecherche : Tableau contenant les mots clés et les champs auquels chacun s'applique (si ce champ vaut "*", alors le mot-clé doit être appliqué à tous les champs possibles)
	 *
	 * @return Une liste de contact triés par entreprise si la requête s'est bien passée, une erreur sinon.
	 */

	static function Rechercher($listeElementsRecherche) {

		$requete = 'SELECT ID_CONTACT FROM CONTACT_ENTREPRISE c 
			 LEFT OUTER JOIN ENTREPRISE e ON c.ID_ENTREPRISE = e.ID_ENTREPRISE
			 LEFT OUTER JOIN VILLE v ON c.ID_VILLE = v.ID_VILLE
			 LEFT OUTER JOIN PERSONNE p ON c.ID_PERSONNE = p.ID_PERSONNE
			 LEFT OUTER JOIN MAIL m ON m.ID_PERSONNE = p.ID_PERSONNE
			 LEFT OUTER JOIN TELEPHONE t ON t.ID_PERSONNE = p.ID_PERSONNE
			 WHERE 1=1';
			
		$clauseWhere = array();
		$parametres = array();
		
		$i = 0;
		foreach( $listeElementsRecherche as $el ) {
			$champConcerne = $el['champ'];
			$val = $el['val'];
			$valLike = '%'.$val.'%';
			if (isset($champConcerne)) { // Si ce champs est non-nul, alors on limite la recherche à celui-ci, sauf s'il vaut '*' :
				if ($champConcerne == 'nom' ) {
					array_push($clauseWhere, 'p.NOM LIKE :nom'.$i);
					$parametres['nom'.$i] = $valLike;
				}
				else if ($champConcerne == 'prénom') {
					array_push($clauseWhere, 'p.PRENOM LIKE :prenom'.$i);
					$parametres['prenom'.$i] = $valLike;
				}
				else if ($champConcerne == 'job') {
					array_push($clauseWhere, 'c.FONCTION LIKE :poste'.$i);
					$parametres['poste'.$i] = $valLike;
				}
				else if ($champConcerne == 'entr') {
					array_push($clauseWhere, 'e.NOM LIKE :entr'.$i);
					$parametres['entr'.$i] = $valLike;
				}
				else if ($champConcerne == 'entr') {
					array_push($clauseWhere, 'e.NOM LIKE :entr'.$i);
					$parametres['entr'.$i] = $valLike;
				}
				else if ($champConcerne == 'email') {
					array_push($clauseWhere, 'm.MAIL LIKE :email'.$i);
					$parametres['email'.$i] = $valLike;
				}
				else if ($champConcerne == 'tel') {
					array_push($clauseWhere, 't.NUMERO LIKE :tel'.$i);
					$parametres['tel'.$i] = $valLike;
				}
				else if ($champConcerne == 'ville') {
					array_push($clauseWhere, 'v.LIBELLE_VILLE LIKE :ville'.$i);
					$parametres['ville'.$i] = $valLike;
				}else if ($champConcerne == 'cp') {
					array_push($clauseWhere, 'v.CP_VILLE LIKE :cp'.$i);
					$parametres['cp'.$i] = $valLike;
				}else if ($champConcerne == 'pays') {
					array_push($clauseWhere, 'v.PAYS_VILLE LIKE :pays'.$i);
					$parametres['pays'.$i] = $valLike;
				}else if ($champConcerne == 'rem') {
					array_push($clauseWhere, 'c.COMMENTAIRE LIKE :rem'.$i);
					$parametres['rem'.$i] = $valLike;
				}
				else if ($champConcerne == 'prio') {
					array_push($clauseWhere, 'c.PRIORITE LIKE :prio'.$i);
					$parametres['prio'.$i] = $valLike;
				}
				else if ($champConcerne == '*') { // On porte la comparaison sur tous les champs prévus :
				array_push($clauseWhere, '(p.NOM LIKE :nom'.$i. ' OR p.PRENOM LIKE :prenom'.$i.' OR c.FONCTION LIKE :poste'.$i.' OR e.NOM LIKE :entr'.$i.' OR m.MAIL LIKE :email'.$i.' OR t.NUMERO LIKE :tel'.$i.' OR v.LIBELLE_VILLE LIKE :ville'.$i.' OR v.CP_VILLE LIKE :cp'.$i.' OR v.PAYS_VILLE LIKE :pays'.$i.' OR c.COMMENTAIRE LIKE :rem'.$i.' OR c.PRIORITE LIKE :prio'.$i.')');
				$parametres['nom'.$i] = $valLike;
				$parametres['prenom'.$i] = $valLike;
				$parametres['poste'.$i] = $valLike;
				$parametres['email'.$i] = $valLike;
				$parametres['tel'.$i] = $valLike;
				$parametres['entr'.$i] = $valLike;
				$parametres['ville'.$i] = $valLike;
				$parametres['cp'.$i] = $valLike;
				$parametres['pays'.$i] = $valLike;
				$parametres['rem'.$i] = $valLike;
				$parametres['prio'.$i] = $valLike;
				}
				else return Contact::getErreurChampInconnu();
			}
			
			$i++;
		}

		$nbCond = count($clauseWhere);
		for ($i = 0; $i < $nbCond; $i++) {
			$requete .= ' AND '.$clauseWhere[$i];
		}
		
		$requete .= ' ORDER BY e.NOM asc';
		
		try {
			$result = BD::executeSelect( $requete, $parametres, BD::RECUPERER_TOUT );
		}
		catch (Exception $e) {
			return Contact::getErreurExecRequete();
		}
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
	* Récuperation des noms de poste/fonction des contacts en BDD
	* @return Un tableau de postes (String)
	* @throws Une exception en cas d'erreur au niveau des requêtes
	*/
	public static function GetListeFonctions() {

		$obj = array();

		/* Tentative de sélection de tous les ID ordonnés par nom */
		$result = BD::executeSelect( 'SELECT DISTINCT FONCTION FROM CONTACT_ENTREPRISE', array(), BD::RECUPERER_TOUT );

		if( $result == null ) {
			return null;
		}

		/* On consturit une réponse lisible (fonction BD::executeSelect à améiorer pour ça ...) */
		foreach( $result as $row ) {
			array_push($obj, $row['FONCTION']);
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

		$result = 0;
		try {
			$result = BD::executeModif( 'DELETE FROM CONTACT_ENTREPRISE WHERE ID_CONTACT = :id', array('id' => $_id));
		}
		catch (Exception $e) {
			return Entreprise::getErreurExecRequete();;
		}

		return $result;

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
			return Contact::getErreurChampInconnu();
		}
		$result=0;
		
		/* Mise à jour du contact */
		if( $_id > 0 ) {
	
			/* Préparation du tableau associatif */
			$info = array( 'id' => $_id, 'idPersonne' => $_personne->getId(), 'idEntreprise' => $_entreprise->getId(), 'idVille' => $_ville->getId(), 'fonction' => $_fonction, 'commentaire' => $_com, 'priorite' => $_priorite );
		
			/* Execution de la requête */
			try {
				$result = BD::executeModif( 'UPDATE CONTACT_ENTREPRISE SET
					ID_PERSONNE = :idPersonne,
					ID_ENTREPRISE = :idEntreprise,
					ID_VILLE = :idVille,
					FONCTION = :fonction,
					COMMENTAIRE = :commentaire,
					PRIORITE = :priorite
					WHERE ID_CONTACT = :id', $info );
			}
			catch (Exception $e) {
				return Contact::getErreurExecRequete();;
			}
		}
		/* Ajout d'un nouveau contact */
		else {
			
			/* Préparation du tableau associatif */
			$info = array( 'idPersonne' => $_personne->getId(), 'idEntreprise' => $_entreprise->getId(), 
				'idVille' => $_ville->getId(), 'fonction' => $_fonction, 'commentaire' => $_com, 'priorite' => $_priorite );
			
			/* Execution de la requête */
			try {
				$result = BD::executeModif( 'INSERT INTO CONTACT_ENTREPRISE(ID_PERSONNE, ID_ENTREPRISE, ID_VILLE, FONCTION, COMMENTAIRE, PRIORITE) VALUES( :idPersonne, :idEntreprise, :idVille, :fonction, :commentaire, :priorite )', $info );
				if ($result != 0) {
					/* Récupération de l'identifiant du nouveau contact */
					$result = BD::getConnection()->lastInsertId();
				}
			}
			catch (Exception $e) {
				return Contact::getErreurExecRequete();;
			}
		}

		return $result;
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
