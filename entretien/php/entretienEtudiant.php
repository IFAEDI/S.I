<?php
/**********************************************
* Vue pour l'inscription aux simulations d'entretiens
* Partie étudiante
* Auteur : Daniel Baudry
***********************************************/

global $authentification;
global $utilisateur;

if( $authentification->isAuthentifie() == false ) {
        ?>
                <div class="alert" style="text-align: center;">
                        <p>Merci de prendre le temps de vous identifier en cliquant <a data-toggle="modal" href="#login_dialog">ici</a>.</p>
                </div>
        <?php
	die;
}
else if($utilisateur->getPersonne()->getRole() != Personne::ADMIN &&
        $utilisateur->getPersonne()->getRole() != Personne::AEDI &&
        $utilisateur->getPersonne()->getRole() != Personne::ETUDIANT) {
    inclure_fichier('', '401', 'php');
    die;
}

?>
<div id="entretiens">
	<h1>Simulations d'entretiens</h1>
	<p class="alert alert-block alert-warning">
		Désolé, ce service est actuellement en cours de finition. Marie Rosain se charge des inscriptions en attendant, merci de la contacter.<br/>
		Pour en savoir plus, consultez la page du Département : <a title="IF - Simulations d'entretiens" href="http://if.insa-lyon.fr/entreprise/simulation-entretiens">if.insa-lyon.fr/entreprise/simulation-entretiens</a>.
	</p>
	<?php /**
	<div class="alert alert-block alert-info">
					<h4 class="alert-heading">Inscription</h4>
					Afin de faire votre demande d'inscription à des sessions de simulation d'entretiens, vous pouvez choisir la date souhaitée via le calendrier.<br/>
			Une fois la liste des créneaux disponibles, vous pourrez vous inscrire aux sessions encore disponibles. A noter que la validation de votre creneau se fera ultérieurement par l'administration.
	</div>
		
	<form class="well form-inline" id="formChoixDate" action="#" method="post">
		<label class="offset1" for="date1">Date</label>
		<input name="date1" id="date_creneaux" class="input-small date-pick"/>
		<button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i> Rechercher</button>
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
*/ ?>
</div>

<?php /**
<?php
inclure_fichier('entretien', 'inscription', 'js');
inclure_fichier('entretien', 'jquery.datePicker', 'js');
inclure_fichier('entretien', 'date', 'js');
inclure_fichier('entretien', 'datePicker', 'css');
?>

*/ ?>
