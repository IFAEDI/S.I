<?php
/*****************************************************
* Vue de la partie des stages
* Auteur : Benjamin Bouvier
*****************************************************/

global $authentification, $utilisateur;
if ($authentification->isAuthentifie() == false ) {
	?>
	<div class="alert" style="text-align: center;">
		<p>Merci de prendre le temps de vous identifier en cliquant <a data-toggle="modal" href="#login_dialog">ici</a>.</p>
	</div>
	<?php
	die;
}
else if( $utilisateur->getPersonne()->getRole() != Personne::ETUDIANT && $utilisateur->getPersonne()->getRole() != Personne::ADMIN ) {
    inclure_fichier('', '401', 'php');
    die;
}

inclure_fichier('stages', 'stages', 'js');
?>

<div id="stages">

	<h1>Recherche de stages</h1>
	<div id="annuaire" class="row">
		<div id="description" class="columns">
			<div class="alert alert-block alert-info" style="text-align: justify;">
					<h4 class="alert-heading">Propositions de Stage - Formulaire Etudiant</h4>
					Vous pouvez rechercher par mots-clés dans le titre ou la description du sujet proposé, par
				année pour laquelle vous êtes intéressés, par lieu (en entrant un nom de ville ou un numéro
				de département) ou encore par nom d'entreprise directement si vous le souhaitez.<br/>
				Vous pouvez effectuer une recherche tronquée à l'aide de l'opérateur joker <strong>*</strong>. Par exemple,
				rechercher avec le mot-clé <i>"mobil*"</i> permettra de rechercher tout ce qui commence par <i>mobil</i>,
				donc renverra les résultats <i>mobile, mobiles, mobilité,...</i>
			</div>
		</div>

		<div class="columns">
			<form class="form alert" id="form_stages">
				<fieldset class="control-group form-search">
					<input id="mots_cles" type="text" class="input-medium search-query" placeholder="Mots-clés">
					<button  id="submit_recherche" type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i> Rechercher</button>
							<select id="annee">
								<option value="">Toutes années</option>
								<option value="3">3ème année</option>
								<option value="4">4ème année</option>
								<option value="5">5ème année (PFE)</option>
							</select>

						<label class="control-label" for="lieu">Lieu</label>
						<input type="text" id="lieu" placeholder="Lieu" />

						<label class="control-label" for="entreprise">Entreprise</label>
						<input type="text" id="entreprise" placeholder="Entreprise" />
				</fieldset>
			</form>
		</div>

		<div id="information" class="alert alert-info"> </div>
		
	</div>
			<!-- <ul class="unstyled" id="resultats">
			</ul>
			-->

	<table id="fenetre" class="table table-striped table-bordered table-condensed">
		<thead>
			<th>#</th>
			<th>Titre</th>
			<th>Entreprise</th>
			<th>Lieu</th>
			<th>Année(s)</th>
		</thead>
		<tbody id="resultats">
		</tbody>
	</table>

</div>
