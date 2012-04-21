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
	if( $utilisateur->getPersonne()->getRole() != Personne::ADMIN ) {
		inclure_fichier('', '401', 'php');
	}
	else {
		/* Sinon on peut commencer à faire notre tambouille */
		?>

		<div>
			<h2>Listes des utilisateurs enregistrés</h2>

			<div id="erreur" class="alert alert-error hide" style="margin-top: 20px"></div>

			<div style="text-align: right;">
				<a href="#" id="raffraichir" class="btn btn-info"><i class="icon-refresh icon-white"></i> Raffraîchir</a>
				<a href="#" id="ajouter"  class="btn btn-success"><i class="icon-plus-sign icon-white"></i> Ajouter un utilisateur</a>
			</div>

			<table id="table_liste_utilisateurs" class="table table-striped table-bordered table-condensed" style="margin-top: 20px;">
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
			</ul>
			</div>

		</div>

	<?php
	}
}

?>


</div>

<!-- Dialog pour l'édition / ajout -->
<div class="modal hide fade" id="admin_user_dialog">
   <div class="modal-header">
        <a class="close" data-dismiss="modal" >&times;</a>
        <h3>Utilisateur</h3>
    </div>
    <div class="modal-body" style="text-align: center;">

	<div class="hide alert alert-error" id="erreur"></div>

	<div class="control-group">
		<p>Nom d'utilisateur</p>
		<div class="controls">
			<input class="input-medium" style="margin: 0px;" id="login" type="text" />
		</div>
	</div>
	<div class="control-group">
		<p>Mot de passe</p>
		<div class="controls">
			<input class="input-medium" style="margin: 0px;" id="pwd" type="password" />
		</div>
	</div>
	<!--
	<div class="control-group">
		<p>Service d'authentification</p>
		<div class="controls">
			<select id="service" class="input-medium disabled" disabled="disabled">
			</select>
		</div>
	</div>
	-->
	<div class="control-group">
		<p>Nom</p>
		<div class="controls">
			<input class="input-medium" style="margin: 0px;" id="nom" type="text" />
		</div>
	</div>
	<div class="control-group">
		<p>Prénom</p>
		<div class="controls">
			<input class="input-medium" style="margin: 0px;" id="prenom" type="text" />
		</div>
	</div>
	<div class="control-group">
		<p>Rôle</p>
		<div class="controls">
			<select id="role" class="input-medium">
			</select>
		</div>
	</div>
	 <div class="control-group">
		<p>Adresse mail</p>
		<div class="controls">
			  <div class="input-prepend">
				<span class="add-on" style="margin-top: -9px;"><i class="icon-info-sign"></i></span><input class="input-medium libelle_mail" type="text" />
				<span class="add-on" style="margin-top: -9px;">@</span><input class="input-medium mail" type="text" />
			  </div>
			  <div class="input-prepend">
				<span class="add-on" style="margin-top: -9px;"><i class="icon-info-sign"></i></span><input class="input-medium libelle_mail" type="text" />
				<span class="add-on" style="margin-top: -9px;">@</span><input class="input-medium mail" type="text" />
			  </div>
			  <div class="input-prepend">
				<span class="add-on" style="margin-top: -9px;"><i class="icon-info-sign"></i></span><input class="input-medium libelle_mail" type="text" />
				<span class="add-on" style="margin-top: -9px;">@</span><input class="input-medium mail" type="text" />
			  </div>
		</div>
	</div>

	<div class="control-group">
		<p>Téléphone</p>
		<div class="controls">
			  <div class="input-prepend">
				<span class="add-on" style="margin-top: -9px;"><i class="icon-info-sign"></i></span><input class="input-medium libelle_telephone" type="text" />
				<span class="add-on" style="margin-top: -9px;"><i class="icon-volume-up"></i></span><input class="input-medium telephone" type="text" />
			  </div>
			  <div class="input-prepend">
				<span class="add-on" style="margin-top: -9px;"><i class="icon-info-sign"></i></span><input class="input-medium libelle_telephone" type="text" />
				<span class="add-on" style="margin-top: -9px;"><i class="icon-volume-up"></i></span><input class="input-medium telephone" type="text" />
			  </div>
			  <div class="input-prepend">
				<span class="add-on" style="margin-top: -9px;"><i class="icon-info-sign"></i></span><input class="input-medium libelle_telephone" type="text" />
				<span class="add-on" style="margin-top: -9px;"><i class="icon-volume-up"></i></span><input class="input-medium telephone" type="text" />
			  </div>
		</div>
	</div>

    </div>
    <div class="modal-footer" style="text-align: center;">
		<a href="#" id="enregistrer" class="btn btn-success">Enregistrer</a>
                <a href="#" data-dismiss="modal" class="btn btn-danger">Annuler</a>
    </div>
</div>

<!-- Dialog pour la suppression -->
<div class="modal hide fade" id="admin_del_user_dialog">
   <div class="modal-header">
        <a class="close" data-dismiss="modal" >&times;</a>
        <h3>Suppression d'un utilisateur</h3>
    </div>
    <div class="modal-body" style="text-align: center;">

        <p>Êtes-vous sûr de vouloir bannir cette utilisateur?</p>

	<input type="checkbox" id="del_personne" class="input-medium" /> <span>Supprimer également la personne associée</span>

    </div>
    <div class="modal-footer" style="text-align: center;">
                <a href="#" data-dismiss="modal" class="btn btn-primary">Annuler</a>
                <a href="#" id="confirmer" class="btn btn-danger">Confirmer</a>
    </div>
</div>

<?php

inclure_fichier( 'commun', 'admin_utilisateurs', 'js' );

?>
