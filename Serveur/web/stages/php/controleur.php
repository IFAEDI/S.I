<?php

require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('commun', 'requete.inc', 'php'); // Ajouter dans base.inc ?

/**
 * Cette classe s'occupe de tous les appels pouvant être effectués
 * sur le module Stages, à savoir la recherche de stages.
 *
 * Auteur : benjamin.bouvier@gmail.com (2011/2012)
 */
class Stages {

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
	 */

	static function rechercher($mots_cles, $annee, $duree,
				$lieu, $entreprise) {

		$requete = new Requete("SELECT titre, annee, description, " .
		"duree, lieu, entreprise FROM Stage");

		if ( isset($annee) ) {
			$requete->ajouterConditionEgale('annee', $annee);	
		}

		if ( isset($duree) ) {
			$requete->ajouterConditionEgale('duree', $duree);
		}

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

		return $requete->lire();
	}
}

?>
