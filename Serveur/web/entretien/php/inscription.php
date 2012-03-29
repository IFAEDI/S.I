<?php
/*if (!Utilisateur_connecter('entreprise')) {
    inclure_fichier('', '401', 'php');
    die;
}
*/
?>

<form class="form-horizontal" id="formInscription" name="formInscription" action="#" method="post">
	<fieldset>
	  <legend>Formulaire d'inscription</legend>
	  <!-- Partie relative au contact -->
	  <div class="control-group" id="control_nom">
		<label class="control-label">Nom</label>
		<div class="controls">
		  <input class="input-medium" type="text" id="nom"/>
		</div>
	  </div>
	  <div class="control-group" id="control_prenom">
		<label class="control-label">Prenom</label>
		<div class="controls">
		  <input class="input-medium" type="text" id="prenom"/>
		</div>
	  </div>
	  <div class="control-group" id="control_email">
		<label class="control-label" for="email">E mail</label>
		<div class="controls">
		  <div class="input-prepend">
			<span class="add-on">@</span><input class="input-medium" id="email" type="text">
		  </div>
		</div>
	  </div>
	  <div class="control-group">
		<label class="control-label" for="telephone">Telephone</label>
		<div class="controls">
			<input class="input-small" type="text" id="telephone"/>
		</div>
	  </div>
	  <!-- Partie relative a l'entreprise -->
	  <div class="control-group" id="control_nomEntreprise">
		<label class="control-label">Entreprise</label>
		<div class="controls">
		  <input class="input-medium" type="text" id="nomEntreprise"/>
		</div>
	  </div>
	  <div class="control-group" id="control_villeEntreprise">
		<label class="control-label">Ville</label>
		<div class="controls">
		  <input class="input-medium" type="text" id="villeEntreprise"/>
		</div>
	  </div>
	  <div class="control-group" id="control_date">
		<label class="control-label">Date</label>
		<div class="controls">
		  <input name="date1" id="date" class="input-medium date-pick"/>
		</div>
	  </div>
	  
	   <div class="control-group" id="control_heureDebut">
		<label class="control-label">Heure de Debut</label>
		<div class="controls">
		  <select id="heureDebut" class="input-small">
		  <option>choix</option>
			<option>14h</option>
			<option>15h</option>
			<option>16h</option>
		  </select>
		  <select id="minuteDebut" class="input-small">
			<option>00</option>
			<option>15</option>
			<option>30</option>
			<option>45</option>
		  </select>
		</div>
	  </div>
	  
	  <div class="control-group" id="control_heureFin">
		<label class="control-label">Heure de Fin</label>
		<div class="controls">
		  <select id="heureFin" class="input-small">
			<option>choix</option>
			<option>15h</option>
			<option>16h</option>
			<option>17h</option>
		  </select>
		  <select id="minuteFin" class="input-small">
			<option>00</option>
			<option>15</option>
			<option>30</option>
			<option>45</option>
		  </select>
		</div>
	  </div>

	<!-- Partie relative au intervenants -->
	<table class="table table-striped" id="tableParticipant">
	<thead>
	  <tr>
		<th>Nom</th>
		<th>Prenom</th>
		<th>E Mail</th>
	  </tr>
	</thead>
	<tbody>
	<td>Le Roux</td>
	<td>Bill</td>
	<td>bill.le.roux@laposte.fr</td>
	</tbody>
	</table>
	<a class="offset7 actuator btn btn-primary" data-toggle="modal" href="#myModal">
	Ajouter Intervenant
	</a>
	
	  <div class="form-actions">
		<button type="submit" class="btn btn-primary">Valider</button>
		<button class="btn">Effacer</button>
	  </div>
	</fieldset>
  </form>


<div class="modal hide fade" id="myModal">
	<div class="modal-header">
	<a class="close" data-dismiss="modal">×</a>
	<h3>Entretien</h3>
	</div>
	<div class="modal-body">
	   <form class="form-horizontal" method="post" id="formParticipant" action="#">
			<div class="control-group" id="control_nom">
			<label class="control-label">Nom</label>
			<div class="controls">
			  <input class="input-medium" type="text" id="nomParticipant"/>
			</div>
		  </div>
		  <div class="control-group" id="control_prenom">
			<label class="control-label">Prenom</label>
			<div class="controls">
			  <input class="input-medium" type="text" id="prenomParticipant"/>
			</div>
		  </div>
		  <div class="control-group" id="control_email">
			<label class="control-label" for="email">E mail</label>
			<div class="controls">
			  <div class="input-prepend">
				<span class="add-on">@</span><input class="input-medium" id="emailParticipant" type="text">
			  </div>
			</div>
		  </div>
		  
			<div class="modal-footer">
			<input type="submit" class="btn btn-primary" value="Valider"/>
			<a href="#" class="btn" data-dismiss="modal">Annuler</a>
			</div>
		</form>
	</div>
</div>
  
  
<!--[if lt IE 7]><script type="text/javascript" src="scripts/jquery.bgiframe.min.js"></script><![endif]-->

<script type="text/javascript" charset="utf-8">

	$(function(){
		$('.date-pick').datePicker();
	});
	
</script>


<?php
inclure_fichier('entretien', 'inscription', 'js');
inclure_fichier('entretien', 'jquery.datePicker', 'js');
inclure_fichier('entretien', 'date', 'js');
inclure_fichier('entretien', 'datePicker', 'css');

?>
