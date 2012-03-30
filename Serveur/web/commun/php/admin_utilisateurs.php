<?php

global $authentification;
global $utilisateur;

?>

<div id="admin_utilisateurs">

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
	if( $utilisateur->getTypeUtilisateur() != Utilisateur::UTILISATEUR_ADMIN ) {
		inclure_fichier('', '401', 'php');
	}
	else {
		/* Sinon on peut commencer à faire notre tambouille */
	}
}

?>


</div>
