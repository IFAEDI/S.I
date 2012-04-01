<?php
global $authentification;
global $utilisateur;

if ($authentification->isAuthentifie() == false || (
        $utilisateur->getPersonne()->getRole() != Personne::ADMIN &&
        $utilisateur->getPersonne()->getRole() != Personne::AEDI &&
        $utilisateur->getPersonne()->getRole() != Personne::ETUDIANT)) {
    inclure_fichier('', '401', 'php');
    die;
}

?>
<form class="form-horizontal" id="formChoixDate" action="#" method="post">
	<fieldset>
		<legend>Simulations d'entretiens</legend>
		<br />
		<p>Afin de faire votre demande d'inscription a des sessions de simulation d'entretiens, vous pouvez choisir la date souhaitee via le calendrier.
		Une fois la liste des creneaux disponibles vous pourrez vous inscrire aux sessions encore disponibles. A noter que la validation de votre creneau se
		fera ulterieurement par l'administration.
		</p>
		<br />
		<div class="control-group" id="control_date">
		<label class="offset1 control-label">Date</label>
		<div class="controls">
		  <input name="date1" id="date_creneaux" class="input-medium date-pick"/>
		<button type="submit" class="btn btn-primary offset1">Rechercher</button>
		</div>
		 </div>
	</fieldset>
</form>
			
<div class="accordion" id="accordion_creneau">
	
	
</div>


<div class="modal hide fade" id="myModal">
	<div class="modal-header">
	<a class="close" data-dismiss="modal">×</a>
	<h3>Entretien</h3>
	</div>
	<div class="modal-body">
	   <form class="form-horizontal" id="formReservation" method="post" action="#" >
			<input type="hidden" id="id_creneau"/>
			<p>Etes-vous sur de vouloir vous inscrire a cette session ?</p>
			<div class="modal-footer">
			<input type="submit" class="btn btn-primary" value="Valider"/>
			<a href="#" class="btn" data-dismiss="modal">Annuler</a>
			</div>
		</form>
	</div>
</div>


<script type="text/javascript" charset="utf-8">


// Permet d'afficher le choix de la date
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