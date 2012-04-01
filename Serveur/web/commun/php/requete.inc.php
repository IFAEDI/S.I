<?php

/**
 * Cette classe permet de simplifier l'usage des requêtes sur la base
 * de données. Elle aide notamment à gérer les conditions, en évitant
 * au développeur de se soucier de la manière dont fonctionne PDO pour
 * sécuriser les requêtes et éviter les injections SQL.
 * 
 * Des conditions courantes (like et =) sont prêtes à l'emploi, mais
 * le développeur peut également rajouter des conditions qui lui sont
 * propres, s'il le désire (ajouterCondition).
 *
 * Auteur : benjamin.bouvier@gmail.com (2011/2012)
 */

class Requete {
	/**
	 * Cette variable permet de savoir s'il s'agit de la première
	 * condition que l'on ajoute ou pas (permet de faire la
	 * distinction entre un ajout avec WHERE ou AND).
	 */
	private $premiereCondition = true;
	
	/**
	 * Contenu textuel de la requête.
	 */
	private $requete = null;

	/**
	 * Tableau contenant les correspondances entre les noms
	 * protégés par PDO et les valeurs à leur attribuer.
	 */
	private $attributs = array();

	private $clauseWhere = null;

	/**
	 * Constructeur principal.
	 *
	 * $texteRequete : texte de la requête, avant les conditions,
	 * au format SQL
	 */
	public function __construct($texteRequete) {
		$this->requete = $texteRequete;
	}

	/**
	 * Ajoute la condition $contenu, en ajoutant éventuellement
	 * la variable $champ au tableau des attributs remplacés par PDO
	 * avec la valeur $valeur_champ.
	 *
	 * $contenu : chaîne au format SQL
	 * $champ : chaîne
	 * $valeur_champ : chaîne
	 */
	public function ajouterCondition($contenu, 
					$champ = NULL, 
					$valeur_champ = NULL) {
		if ($this->premiereCondition) {
			$this->clauseWhere.= ' WHERE ';
			$this->premiereCondition = false;	
		} else {
			$this->clauseWhere .= ' AND ';
		}
		$this->clauseWhere .= $contenu;

		if ($champ != NULL && $valeur_champ != NULL) {
			$this->attributs[$champ] = $valeur_champ;
		}
	}

	/**
	 * Ajoute une condition de type "comme" (LIKE, en SQL), sous
	 * la forme "$champ doit être comme $contenu", i.e au moins
	 * contenir $contenu.
	 * $contenu doit désigner une constante et non une variable.
	 *
	 * $champ : chaîne
	 * $contenu : chaîne
	 */
	public function ajouterConditionComme($champ, $contenu) {
		$contenu = '%' . $contenu . '%';
		$this->ajouterCondition($champ . ' LIKE :' . $champ,
					$champ, $contenu);
	}

	/**
	 * Ajoute une condition de type "égale" (=, en SQL), sous la
	 * forme "$champ doit être égale strictement à $contenu".
	 * $contenu doit désigner une constante et non une variable.
	 *
	 * $champ : chaîne
	 * $contenu : chaîne
	 */
	public function ajouterConditionEgale($champ, $contenu) {
		$this->ajouterCondition($champ . ' = :' . $champ,
					$champ, $contenu);
	}

	/**
	 * Effectue une recherche des valeurs présentes dans le
	 * tableau de valeurs $valeurs, sur les champs présents
	 * dans le tableau $champs.
	 * 
	 * $champs : tableau de chaînes
	 * $valeurs : tableau de chaînes
	 *
	 * ATTENTION ! Il doit exister un index de type FULLTEXT
	 * sur l'ensemble formé par les colonnes précisées dans
	 * $champs.
	 */
	public function rechercherSur($champs, $valeurs) {
		// Rappel : la fonction implode prend un séparateur
		// et un tableau en entrée et renvoie une chaîne
		// contenant l'ensemble des éléments du tableau
		// séparés par le séparateur.
		$clauseChamps = 'MATCH (';
		$clauseChamps .= implode(', ', $champs);
		$clauseChamps .= ') AGAINST (:mots_cles IN BOOLEAN MODE)';

		$mots_cles = implode(' ', $valeurs); 
		$this->ajouterCondition($clauseChamps, 'mots_cles',
		       			$mots_cles);
	}

	/**
	 * Renvoie les résultats issus de la base de données, sous la
	 * forme d'un tableau d'objets. Chacun des objets est rempli
	 * avec les attributs issus de la lecture, c'est-à-dire que
	 * chaque champ présent dans la sélection deviendra un champ
	 * de l'objet ; les noms des champs sont exactement les mêmes
	 * que ceux issus des noms de colonnes dans la/les tables lue(s).
	 *
	 * @throws Exception si une erreur a eu lieu au niveau de la bdd.
	 */
	public function lire() {
		// Décommenter pour du debug
		// echo $this->requete;

		$this->requete .= $this->clauseWhere;

		return BD::executeSelect($this->requete, 
					$this->attributs,
					BD::RECUPERER_TOUT,
					PDO::FETCH_OBJ);
	}
};

?>
