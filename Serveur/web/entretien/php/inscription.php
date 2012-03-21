<?php
if (!Utilisateur_connecter('entreprise')) {
    inclure_fichier('', '401', 'php');
    die;
}



?>

<form class="form-horizontal" onsubmit="return valider()" name="formInscription" action="entretien.php" method="post">
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
			<option>13h</option>
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
			<option>18h</option>
		  </select>
		  <select id="minuteFin" class="input-small">
			<option>00</option>
			<option>15</option>
			<option>30</option>
			<option>45</option>
		  </select>
		</div>
	  </div>
	  
	  <div class="form-actions">
		<button type="submit" class="btn btn-primary">Valider</button>
		<button class="btn">Effacer</button>
	  </div>
	</fieldset>
  </form>

<!-- datePicker required styles -->
<link rel="stylesheet" type="text/css" media="screen" href="styles/datePicker.css">

<!-- required plugins -->
<script type="text/javascript" src="scripts/date.js"></script>
<!--[if lt IE 7]><script type="text/javascript" src="scripts/jquery.bgiframe.min.js"></script><![endif]-->
<!-- jquery.datePicker.js -->
<script type="text/javascript" src="scripts/jquery.datePicker.js"></script>

<script type="text/javascript" charset="utf-8">

	$(function(){
		$('.date-pick').datePicker();
	});
	
</script>


<?php
inclure_fichier('entretien', 'inscription', 'js');

?>