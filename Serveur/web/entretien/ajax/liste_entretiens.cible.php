<?php
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('controleur', 'entretien.class', 'php');

/*if (!Utilisateur_connecter('administrateur')) {
      die;
}
*/
	$entretien = Entretien::GetListeEntretien();
	
	/*
	 * Renvoyer le JSON
	 */
	// FIXME comment distinguer s'il n'y a pas de résultats ou une erreur ?
	$json['code'] = 'ok';
	if ($entretien != NULL) {
		$json['entretien'] = $entretien;
	}
	echo json_encode($json);
	
?>