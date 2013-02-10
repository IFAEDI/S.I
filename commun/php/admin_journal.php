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

		<div>
			<h2>Journalisation</h2>

			<div id="erreur" class="alert alert-error" style="margin-top: 20px; text-align: center;"> TODO </div>

			<div style="text-align: right;">
				<a href="#" id="raffraichir" class="btn btn-info"><i class="icon-refresh icon-white"></i> Rafraîchir</a>
			</div>

			<table id="table_liste_utilisateurs" class="table table-striped table-bordered table-condensed" style="margin-top: 20px;">
			<thead>
				<tr>
				<th>Date</th>
				<th>Script</th>
				<th>Informations</th>
				<th>IP</th>
				</tr>
			</thead>
			<tbody id="liste_utilisateurs">
			</tbody>
			</table>

			<div class="pagination" style="text-align: center;">
			<ul>
			</ul>
			</div>

		</div>

	<?php
	}
}

?>


</div>

<?php

/*inclure_fichier( 'commun', 'admin_journal', 'js' );*/

?>
