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
	public __construct( $id ) {

		$result = BD::executeSelect( 'SELECT * FROM VILLE WHERE ID_VILLE = :id', array( 'id' => $id ) );

		if( $result == null ) {
			throw new Exception( 'Impossible de récupérer la ville demandée.' );
		}

		$this->id		= $result['ID_VILLE'];
		$this->libelle		= $result['LIBELLE_VILLE'];
		$this->code_postal	= $result['CP_VILLE'];
		$this->pays		= $result['PAYS_VILLE'];
	}

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
};

?>
