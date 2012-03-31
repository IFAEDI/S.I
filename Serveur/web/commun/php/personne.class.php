<?php

require_once dirname(__FILE__) . '/base.inc.php';
inclure_fichier('commun', 'bd.inc', 'php');

class Personne {

	/* Constantes liées aux personnes */
	const ETUDIANT		= 0;
	const ENSEIGNANT	= 1;
	const ENTREPRISE	= 2;
	const ADMIN		= 3;
	const AEDI		= 4;

	public static $ROLES = array( self::ETUDIANT => 'Etudiant',
				self::ENSEIGNANT => 'Enseignant',
				self::ENTREPRISE => 'Entreprise',
				self::ADMIN => 'Administrateur',
				self::AEDI => 'Membre de l\'AEDI' );

	/****************  Attributs  ******************/
	private $id;
	private $nom;
	private $prenom;
	private $mails;
	private $telephones;
	private $premiereConnexion;
	private $role;

	private $utilisateur;


	/**
	* Constructeur
	* $utilisateur : Objet Utilisateur auquel est rattachée la personne
	* @throws Exception si impossibilité de créer la personne
	*/
	public function __construct( $utilisateur ) {

		$this->utilisateur = $utilisateur;

		/* Avec un utilisateur null, on ira pas loin donc on s'arrête là. */
		if( $utilisateur == null ) return;


		$id_utilisateur = $this->utilisateur->getId();		
		$result = $this->_fetchDataByUserID( $id_utilisateur );

		/* Si l'objet n'a pas pu être créé, c'est sans doute que c'est une auth via le CAS et que l'user est pas en base */
		if( $result == false ) {

			/* On s'en occupe donc ! */
			/* Ajout en base */
			$result = BD::executeModif( 'INSERT INTO PERSONNE(NOM, ID_UTILISATEUR) VALUES( :nom, :id )', 
					array( 'nom' => $this->utilisateur->getLogin(), 'id' => $id_utilisateur ) );

			if( $result == 0 ) {
				throw new Exception( 'Impossible d\'insérer le nouvel utilisateur en base.' );
			} 

			/* Et on rappelle pour fetcher les éléments */
			$result = $this->_fetchDataByUserID( $id_utilisateur );
			if( $result == false ) {
				throw new Exception( 'Impossible de construire l\'utilisateur (erreur de bdd).' );
			}
		}
	}

	/**
	* Fonction récupérant tous les attributs de la personne
	* $id : Identifiant de l'utilisateur rattachée à la personne à qui on doit récupérer les données
	* @return True si tout est ok, false sinon
	*/
	private function _fetchDataByUserID( $id ) {

		/* Requête à la base pour récupérer la bonne personne et construire l'objet */
		$result = BD::executeSelect( 'SELECT * FROM PERSONNE WHERE ID_UTILISATEUR = :id', array( 'id' => $id ), BD::RECUPERER_UNE_LIGNE );

		if( $result == null )
			return false;

		return $this->_autoComplete( $result );
	}

	/**
        * Fonction récupérant tous les attributs de la personne
        * $id : Identifiant de la personne à qui on doit récupérer les données
        * @return True si tout est ok, false sinon
        */
        private function _fetchDataByPersonID( $id ) {

                /* Requête à la base pour récupérer la bonne personne et construire l'objet */
                $result = BD::executeSelect( 'SELECT * FROM PERSONNE WHERE ID_PERSONNE = :id', array( 'id' => $id ), BD::RECUPERER_UNE_LIGNE );

                if( $result == null )
                        return false;

                return $this->_autoComplete( $result );
        }


	private function _autoComplete( $result ) {

		$this->id = $result['ID_PERSONNE'];
		$this->nom = $result['NOM'];
		$this->prenom = $result['PRENOM'];
		$this->role = $result['ROLE'];
		$this->premiereConnexion = $result['PREMIERE_CONNEXION'];

		/* Récupération des adresses mails associées */
		$result = BD::executeSelect( 'SELECT * FROM MAIL WHERE ID_PERSONNE = :id ORDER BY PRIORITE',
					array( 'id' => $this->id ), BD::RECUPERER_TOUT );

		$this->mails = array();
		if( $result != null ) {

			$i = 0;
			foreach( $result as $row ) {
				$this->mails[$i] = array( $row['INTITULE'], $row['MAIL'] );
				$i++;
			}
		}

		/* Récupération des numéros de tél associés */
		$result = BD::executeSelect( 'SELECT * FROM TELEPHONE WHERE ID_PERSONNE = :id ORDER BY PRIORITE',
					array( 'id' => $this->id ), BD::RECUPERER_TOUT );

		$this->telephones = array();
		if( $result != null ) {

                        $i = 0;
                        foreach( $result as $row ) {
                                $this->telephones[$i] = array( $row['INTITULE'], $row['NUMERO'] );
                                $i++;
                        }
		}

		return true;
	}

	

	/**
	* Change les informations personnelles de l'utilisateur
	* @return Vrai si tout est ok, faux sinon
	*/
	public function changeInfo( $nom, $prenom ) {

		/* Requête à la base */
		$result = BD::executeModif( 'UPDATE PERSONNE SET nom = :nom, prenom = :prenom, premiere_connexion = 0 WHERE ID_PERSONNE = :id',
			array( 'nom' => $nom, 'prenom' => $prenom, 'id' => $this->id ) );

		if( $result == 0 ) {
			return false;
		}

		$this->nom = $nom;
		$this->prenom = $prenom;
		$this->premiereConnexion = false;

		return true;
	}

	/**
	* Met à jour les adresses mails de la personne
	* $mails : Un tableau contenant tous les mails de la forme { { Libellé, Mail }, { Libellé, Mail } }
	* @return Vrai si tout est ok, faux sinon
	*/
	public function changeMails( $mails ) {

		/* Vide la base pour ajouter les nouveaux mails */
		BD::executeModif( 'DELETE FROM MAIL WHERE ID_PERSONNE = :id', array( 'id' => $this->id ) );
		$this->mails = array();

		$i = 0;
		foreach( $mails as $mail ) {
			if( strlen( $mail[1] ) > 0 ) {

				$libelle  = strip_tags( mysql_escape_string( $mail[0] ) );
				$cur_mail = strip_tags( mysql_escape_string( $mail[1] ) );

				$result = BD::executeModif( 'INSERT INTO MAIL VALUES( :id, :libelle, :mail, :priorite )', 
					array( 'id' => $this->id, 'libelle' => $libelle, 'mail' => $cur_mail, 'priorite' => $i ) );

				if( $result == 0 ) {
					return false;
				}

				$this->mails[$i] = array( $libelle, $cur_mail );

				$i++;
			}
		}

		return true;
	}

	/**
	* Met à jour les numéros de téléphone de la personne
	* $telephones : Un tableau contenant tous les numéros de la forme { { Libellé, Tel }, { Libellé, Tel } }
	* @return Vrai si tout est ok, faux sinon
	*/
	public function changeTelephones( $telephones ) {

                /* Vide la base pour ajouter les nouveaux mails */
                BD::executeModif( 'DELETE FROM TELEPHONE WHERE ID_PERSONNE = :id', array( 'id' => $this->id ) );
		$this->telephones = array();

                $i = 0;
                foreach( $telephones as $tel ) {
                        if( strlen( $tel[1] ) > 0 ) {

                                $libelle  = strip_tags( mysql_escape_string( $tel[0] ) );
                                $cur_tel  = strip_tags( mysql_escape_string( $tel[1] ) );

                                $result = BD::executeModif( 'INSERT INTO TELEPHONE VALUES( :id, :libelle, :tel, :priorite )',
                                        array( 'id' => $this->id, 'libelle' => $libelle, 'tel' => $cur_tel, 'priorite' => $i ) );

                                if( $result == 0 ) {
                                        return false;
                                }

                                $this->telephones[$i] = array( $libelle, $cur_tel );

                                $i++;
                        }
                }

                return true;
	}

	/**
	* Change le type d'utilisateur
	* @return Vrai si tout est ok, faux sinon
	*/
	public function changeUtilisateurType( $type ) {

		/* Requête de mise à jour */
		$result = BD::executeModif( 'UPDATE UTILISATEUR SET type = :type WHERE id = :id', array( 'type' => $type, 'id' => $this->id ) );

		if( $result == 0 ) {
			return false;
		}

		$this->typeUtilisateur = $type;

		return true;
	}


	/**
	* Détermine si c'est la première connexion de l'utilisateur ou non
	* @return Vrai si c'est le cas, faux sinon
	*/
	public function premiereConnexion() {
		return $this->premiereConnexion;
	}

	/**
	* Retourne le role de la personne
	*/
	public function getRole() {
		return $this->role;
	}

	public function getId() {
		return $this->id;
	}

	public function getNom() {
		return $this->nom;
	}

	public function getPrenom() {
		return $this->prenom;
	}

	public function getMails() {
		return $this->mails;
	}

	public function getTelephones() {
		return $this->telephones;
	}

	/**
	* Récupère l'ensemble des personnes qui ont le rôle passé en paramètre
	* @return Un tableau d'instances
	* @throws Une exception en cas d'erreur
	*/
	public static function getPersonnesParRole( $role ) {

		$obj = array();

		/* Requête à la base pour récupérer les  et construire les objets */
		$result = BD::executeSelect( 'SELECT ID_PERSONNE FROM PERSONNE WHERE ROLE = :role', array( 'role' => $role ), BD::RECUPERER_TOUT );

		$i = 0;
		foreach( $result as $row ) {

			$obj[$i] = new Personne( null );
			$obj[$i]->_fetchDataByPersonID( $row['ID_PERSONNE'] );
			$i++;
		}

		return $obj;
	}

	/**
        * Récupère une personne bien précise
	* $id : L'identifiant de la personne à récupérer
        * @return L'instance de la personne concernée ou null
        * @throws Une exception en cas d'erreur
        */
        public static function getPersonneParID( $id ) {

		$p = new Personne( null );
		$p->_fetchDataByPersonID( $id );

                return $p;
        }
}

?>
