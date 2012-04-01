<?php
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
inclure_fichier('controleur', 'creneau.class', 'php');

//Si l'utilisateur n'est pas une etudiant, on arrete tout
/*if (!Utilisateur_connecter('etudiant')) {
      die;
}*/
	
	if( empty($_POST) ) {
        die;
    }
	
	// On verifie la presence de l'id du creneau choisis
	if( !empty($_POST['id_creneau']) ){
		$_id_creneau = $_POST['id_creneau'];
	}
	
	//On recupere l'id de l'etudiant
	/*if( $authentification->isAuthentifie() ){
		// On récupère l'objet utilisateur associé
		$utilisateur = $authentification->getUtilisateur();
		$id_utilisateur = $utilisateur->getId();
		if ($utilisateur == null) {
			$authentification->forcerDeconnexion();
		}
	}else{
		die;
	}*/
	
	$id_utilisateur = 22;
	$creneau = new Creneau();
	$retour = $creneau::ReserverCreneau($_id_creneau, $id_utilisateur);
	
	
	//Test du retour
	if( $retour != $_id_creneau ){
		echo 'erreur de traitement en base de donnee';
	}else{
		echo 'Ok';
	}

?>