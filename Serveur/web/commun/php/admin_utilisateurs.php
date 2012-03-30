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
		?>

		<div>
			<h2>Listes des utilisateurs enregistrés</h2>

			<div id="erreur" class="alert alert-error hide" style="margin-top: 20px"></div>

			<div style="text-align: right;">
				<a href="#admin_user_dialog" data-toggle="modal" class="btn btn-success"><i class="icon-plus-sign icon-white"></i> Ajouter un utilisateur</a>
			</div>

			<table class="table table-striped table-bordered table-condensed" style="margin-top: 20px;">
			<thead>
				<tr>
				<th></th>
				<th colspan="3">Authentification</th>
				<th colspan="2">Informations Perso</th>
				<th></th>
				</tr><tr>
				<th>#</th>
				<th>Login</th>
				<th>
					<ul style="list-style: none; margin: 0px;"><li class="dropdown" id="service_hdr">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#service_hdr">Service <b class="caret"></b></a>
					<ul class="dropdown-menu"></ul>
					</li></ul>
				</th>
				<th>
					<ul style="list-style: none; margin: 0px;"><li class="dropdown" id="type_hdr">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#type_hdr">Type <b class="caret"></b></a>
					<ul class="dropdown-menu"></ul>
					</li></ul>
				</th>
				<th>Nom</th>
				<th>Prénom</th>
				<th style="text-align: center;"><i class="icon-eye-open" style="padding: 0px;"></i></th>
				</tr>
			</thead>
			<tbody id="liste_utilisateurs">
			</tbody>
			</table>

			<div class="pagination" style="text-align: center;">
			<ul>
				<li><a href="#">Prev</a></li>
				<li class="active"><a href="#">1</a></li>
				<li><a href="#">Next</a></li>
			</ul>
			</div>

		</div>

	<?php
	}
}

?>


</div>

<div class="modal hide fade" id="admin_user_dialog">
   <div class="modal-header">
        <a class="close" data-dismiss="modal" >&times;</a>
        <h3>Utilisateur</h3>
    </div>
    <div class="modal-body" style="text-align: center;">

	<p>Je veux des chips !</p>

    </div>
    <div class="modal-footer" style="text-align: center;">
                <a href="#" data-dismiss="modal" class="btn btn-danger">Annuler</a>
    </div>
</div>


<?php

inclure_fichier( 'commun', 'admin_utilisateurs', 'js' );

?>
