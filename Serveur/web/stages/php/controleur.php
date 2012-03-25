<?php

require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';

/**
 * Cette classe permet de simplifier l'usage des requêtes sur la base
 * de données. Elle aide notamment à gérer les conditions, en évitant
 * au développeur de se soucier de la manière dont fonctionne PDO pour
 * sécuriser les requêtes et éviter les injections SQL.
 * 
 * Des conditions courantes (like et =) sont prêtes à l'emploi, mais
 * le développeur peut également rajouter des conditions qui lui sont
 * propres, s'il le désire (ajouterCondition).
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

	/**
	 * Constructeur principal.
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
	 */
	public function ajouterCondition($contenu, 
					$champ = NULL, 
					$valeur_champ = NULL) {
		if ($this->premiereCondition) {
			$this->requete .= ' WHERE ';
			$this->premiereCondition = false;	
		} else {
			$this->requete .= ' AND ';
		}
		$this->requete .= $contenu;

		if ($champ != NULL && $valeur_champ != NULL) {
			$this->attributs[$champ] = $valeur_champ;
		}
	}

	/**
	 * Ajoute une condition de type "comme" (LIKE, en SQL), sous
	 * la forme "$champ doit être comme $contenu", i.e au moins
	 * contenir $contenu.
	 * $contenu doit désigner une constante et non une variable.
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
	 */
	public function ajouterConditionEgale($champ, $contenu) {
		$this->ajouterCondition($champ . ' = :' . $champ,
					$champ, $contenu);
	}

	/**
	 * Renvoie les résultats issus de la base de données, sous la
	 * forme d'un tableau d'objets. Chacun des objets est rempli
	 * avec les attributs issus de la lecture, c'est-à-dire que
	 * chaque champ présent dans la sélection deviendra un champ
	 * de l'objet ; les noms des champs sont exactement les mêmes
	 * que ceux issus des noms de colonnes dans la/les tables lue(s).
	 */
	public function lire() {
		return BD::Prepare($this->requete, 
					$this->attributs,
					BD::RECUPERER_TOUT,
					PDO::FETCH_OBJ);
	}
}

// Tests à la va-vite pour l'instant... 
// TODO : effacer tout ça et écrire la fonction de récupération 
$req = new Requete("SELECT * FROM Stage");
// $req->ajouterConditionEgale('annee', '3');
$req->ajouterConditionComme('description', 'manager');

$resultats = $req->lire();

$count = count($resultats);
for ($i = 0; $i < $count; $i++) {
	echo 'Titre : ' . $resultats[$i]->titre . '<br/>';
}

?>
