<?php

require_once dirname(__FILE__) . '/../commun/php/base.inc.php';
inclure_fichier('commun', 'requete.inc', 'php'); // Ajouter dans base.inc ?

/**
 * Cette classe s'occupe de tous les appels pouvant être effectués
 * sur le module Stages, à savoir la recherche de stages.
 *
 * Auteur : benjamin.bouvier@gmail.com (2011/2012)
 */
class Stages {

	/**
	 * Valeur renvoyée en cas de d'erreur au niveau de la BDD.
	 */
	const ERROR = -1; 

	/**
	 * Variable stockant le message d'erreur de la dernière erreur survenue
	 */
	private static $last_error = "";
	

	/**
	 * Recherche des stages appropriés selon les paramètres donnés.
	 *
	 * $mots_cles : tableau contenant les mots clés sous forme de
	 * chaînes (une case du tableau par mot clé)
	 * $annee : valeur parmi 3, 4 ou 5
	 * $duree : valeur comprise entre 1 et 12 inclus (12 peut indiquer
	 * 	plus de 12 mois, le cas échéant). 
	 * $lieu : chaîne
	 * $entreprise : chaîne
	 *
	 * @return Une liste de stages si la requête s'est bien passée,
	 * (si cette liste vaut NULL, elle est vide), ou self::ERROR si
	 * il y a eu une erreur au niveau de la BDD.
	 */

	static function rechercher($mots_cles, $annee, $duree,
				$lieu, $entreprise) {

		$requete = new Requete("SELECT titre, annee, description, " .
		"duree, lieu, entreprise, contact, lien_fichier FROM STAGE");

		if ( isset($annee) ) {
			switch($annee) {
			// En bdd, les valeurs des années peuvent être 3, 4, 5, 7, 9.
			// Outre les valeurs auxquelles on peut s'attendre, les valeurs
			// 7 correspondent à 3ème et 4ème année (3+4=7) et 9 à 4ème et
			// 5ème année (4+5=9). Il faut donc adapter les requêtes
			// de recherche en conséquent
				case '3':
					$condition = '(annee = 3 OR annee = 7)';
					break;
				case '4':
					$condition = '(annee = 4 OR annee = 7 OR annee = 9)';
					break;
				case '5':
					$condition = '(annee = 5 OR annee = 9)';
					break;
			}
			$requete->ajouterCondition($condition);
		}

		/* TODO durée encore non prise en compte */
		/*
		if ( isset($duree) ) {
			$requete->ajouterConditionEgale('duree', $duree);
		}
		 */

		if ( isset($lieu) ) {
			$requete->ajouterConditionComme('lieu', $lieu);
		}

		if ( isset($entreprise) ) {
			$requete->ajouterConditionComme('entreprise', $entreprise);
		}

		if ( isset($mots_cles) ) {
			$requete->rechercherSur(
				array('titre','description', 'mots_cles'),
				$mots_cles);	
		}

		// Pré-traitement des résultats
		try {
			$resultats = $requete->lire();
			$nb_resultats = count($resultats);

			for ($i = 0; $i < $nb_resultats; ++$i) {
				// Reformater les années correctement.
				if ($resultats[$i]->annee == 7) {
					$resultats[$i]->annee = '3 et 4';
				} else if ($resultats[$i]->annee == 9) {
					$resultats[$i]->annee = '4 et 5';
				}
			}

			return $resultats; 
		} catch (Exception $e) {
			Stages::$last_error = $e->getMessage();
			return self::ERROR;
		}
	}

	/**
	 * Récupère La dernière erreur qui a eu lieu
	 * @return Le descriptif de l'erreur (String)
	 */
	static function getLastError() {
		return Stages::$last_error;
	}
}

?>
