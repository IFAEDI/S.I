<?php
/************************************************
* Author : sebastien.meriot@gmail.com   	*
* Date : 31.03.2012				*
* Description : Objet représentant une ville	*
************************************************/

require_once dirname(__FILE__) . '/../commun/php/base.inc.php';
inclure_fichier('commun', 'bd.inc', 'php');

class Ville {

	private $id;
	private $libelle;
	private $code_postal;
	private $pays;

	/**
	* Constructeur
	* $id : ID de la ville à construire
	* @throws Exception si la ville n'existe pas
	*/
	public function __construct( $id ) {

		$result = BD::executeSelect( 'SELECT * FROM VILLE WHERE ID_VILLE = :id', array( 'id' => $id ) );

		if( $result == null ) {
			throw new Exception( 'Impossible de récupérer la ville demandée.' );
		}

		$this->id		= $result['ID_VILLE'];
		$this->libelle		= $result['LIBELLE_VILLE'];
		$this->code_postal	= $result['CP_VILLE'];
		$this->pays		= $result['PAYS_VILLE'];
	}

	/**
	 * Ajoute une nouvelle ville en base
	 * @return Retourne l'ID de la ville nouvellement ajouté
	 */
	public static function AjouterVille( $cp, $ville, $pays ) {

		$result = BD::executeModif( "INSERT INTO VILLE (CP_VILLE, LIBELLE_VILLE, PAYS_VILLE) VALUES (:cp, :ville, :pays)",
					array( 'cp' => $cp, 'ville' => $ville, 'pays' => $pays ) );

		return BD::getDernierID();

	}

	/**
	 * Détermine si une ville existe dans la base
	 * @return false si la ville n'existe pas, son ID unique si elle existe.
	 */
	public static function VilleExiste( $cp, $ville, $pays ) {

		$result = BD::executeSelect( 'SELECT ID_VILLE FROM VILLE WHERE CP_VILLE LIKE :cp AND LIBELLE_VILLE LIKE :ville AND PAYS_VILLE LIKE :pays',
					array( 'cp' => $cp, 'ville' => $ville, 'pays' => $pays ) );

		/* La ville n'existe pas */
		if( $result == null ) {
			return false;
		}

		/* La ville existe, on retourne l'ID */
		return $result['ID_VILLE'];
	}



	/* Getter / Setter */
	public function getId() {
		return $this->id;
	}

	public function getLibelle() {
		return $this->libelle;
	}

	public function getCodePostal() {
		return $this->code_postal;
	}

	public function getPays() {
		return $this->pays;
	}

	public function toArrayObject() {
		$arrayVille = array();
		$arrayVille['id'] = intval($this->id);
		$arrayVille['code_postal'] = $this->code_postal;
		$arrayVille['libelle'] = $this->libelle;
		$arrayVille['pays'] = $this->pays;

		return $arrayVille;
	}
};

?>
