<?php
//TODO: decommenter
/*if (!Utilisateur_connecter('entreprise')) {
    inclure_fichier('', '401', 'php');
    die;
}
*/
inclure_fichier('controleur', 'entreprise.class', 'php');

$entreprises = Entreprise::GetListeEntreprises();

// On construit la liste de recherche des entreprises
$list_entreprises = "\"";
foreach( $entreprises as $entreprise){
	if( $list_entreprises != "\""){
		$list_entreprises = $list_entreprises."\",\"".$entreprise['NOM'];
	}else{
		$list_entreprises = $list_entreprises.$entreprise['NOM'];
	}
}
$list_entreprises =  $list_entreprises."\"";
?>

<form class="form-horizontal" id="formInscription" name="formInscription" action="#" method="post">
	<fieldset>
	  <legend>Formulaire d'inscription</legend>
		<br />
	<p>Grâce au formulaire ci-dessous vous avez la possibilite de vous inscrire aux sessions de simulations d'entretiens du Département Informatique.<br />
		Si votre entreprise n'est pas encore connue de notre système, n'hésitez pas à nous communiquer vos données qui pourront faciliter nos futurs echanges.
	</p>
	<br />
	<div class="control-group" id="control_nomEntreprise">
		<label class="control-label">Entreprise</label>
		<div class="controls">
		  <!-- On propose une liste d'entreprise deja connue -->
		  <input class="input-medium" type="text" id="entreprise" data-provide="typeahead" data-items="4" data-source='[<?php echo $list_entreprises; ?>]'>
		 <a data-toggle="modal" href="#entrepriseModal">Ajouter</a>
		</div>
	</div>	  
	  
	  
	  <!-- Partie relative au contact -->
	  <div class="control-group" id="control_nomContact">
		<label class="control-label">Contact</label>
		<div class="controls">
		  <!-- On propose une liste d'entreprise deja connue -->
		  <input class="input-medium" type="text" id="nomContact" data-provide="typeahead" data-items="4" data-source='[]'>
		 <a data-toggle="modal" href="#contactModal">Ajouter</a>
		</div>
	</div>
	  
	  <br />
	  <p>Rappel: les simulations d'entretiens ce déroulent les jeudi apres-midi hors vacances scolaires.</p>
	  <br />
	  
	  
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
	
	  <div class="form-actions">
		<button type="submit" class="btn btn-primary">Valider</button>
	  </div>
	</fieldset>
  </form>

  
<div class="modal hide fade" id="entrepriseModal">
	<div class="modal-header">
	<a class="close" data-dismiss="modal">×</a>
	<h3>Entretien</h3>
	</div>
	<div class="modal-body">
	   <form class="form-horizontal" name="formAjoutEntreprise" method="post" >
			<p>Parlez nous un peu plus de vous ...</p>
	
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
		  <div class="control-group" id="control_villeEntreprise">
			<label class="control-label">Secteur activite</label>
			<div class="controls">
			  <input class="input-medium" type="text" id="secteurEntreprise"/>
			</div>
		  </div>
		  <div class="control-group" id="control_descEntreprise">
			<label class="control-label">Description de votre entreprise</label>
			<div class="controls">
				<textarea class="input-xlarge" rows="3" id="description"></textarea>
			</div>
		</div>

			
			<div class="modal-footer">
			<input type="submit" class="btn btn-primary" value="Valider"/>
			<a href="#" class="btn" data-dismiss="modal">Annuler</a>
			</div>
		</form>
	</div>
</div>
  
  
<div class="modal hide fade" id="contactModal">
	<div class="modal-header">
	<a class="close" data-dismiss="modal">×</a>
	<h3>Entretien</h3>
	</div>
	<div class="modal-body">
		<form class="form-horizontal" name="formAjoutContact" method="post" >
	   <div class="control-group" id="control_nom">
		<label class="control-label">Nom</label>
		<div class="controls">
		  <input class="input-medium" type="text" id="nom_contact"/>
		</div>
	  </div>
	  <div class="control-group" id="control_prenom">
		<label class="control-label">Prenom</label>
		<div class="controls">
		  <input class="input-medium" type="text" id="prenom_contact"/>
		</div>
	  </div>
	  <div class="control-group" id="control_mail">
		<label class="control-label" for="email">E mail</label>
		<div class="controls">
		  <div class="input-prepend">
			<span class="add-on">@</span><input class="input-medium" id="mail_contact" type="text">
		  </div>
		</div>
	  </div>
	  <div class="control-group">
		<label class="control-label" for="telephone">Telephone</label>
		<div class="controls">
			<input class="input-small" type="text" id="telephone_contact"/>
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
