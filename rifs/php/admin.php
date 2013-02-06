<?php
/**
 * -----------------------------------------------------------
 * Vue - RIFS, Administration
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Contact - benjamin.planche@aldream.net
 * ---------------------
 * Page d'administration des inscriptions aux RIF (à compléter !).
 */
?>
<?php

global $authentification;
global $utilisateur;

?>

<div id="admin_journal">

<?php

/* Dans le cas où l'utilisateur n'est pas authentifié, on reste soft avec un petit message */
if( $authentification->isAuthentifie() == false ) {

	?>
		<div class="alert" style="text-align: center;">
			<p>Merci de prendre le temps de vous identifier en cliquant <a data-toggle="modal" href="#login_dialog">ici</a>.</p>
		</div>
	<?php
}
else {
	/* Si l'utilisateur est authentifié mais sans permission, on le dégage avec une 401 */
	if( $utilisateur->getPersonne()->getRole() != Personne::ADMIN ) {
		inclure_fichier('', '401', 'php');
	}
	else {
		/* Sinon on peut commencer à faire notre tambouille */
		?>

		<div id="rifs">
			<h2>Administration des RIFs</h2>

			<div id="erreur" class="alert alert-error" style="margin-top: 20px; text-align: center;"> TODO </div>
			
			<p class="centre">Lien vers le formulaire d'inscription <small>(à communiquer aux entreprises sélectionnées)</small> :<br/>
			<a class="btn btn-large btn-primary" href="index.php?page=RIFs_Inscription">index.php?page=RIFs_Inscription</a></p>
		</div>

	<?php
	}
}

?>


</div>

<?php

/*inclure_fichier( 'commun', 'admin_journal', 'js' );*/

?>
