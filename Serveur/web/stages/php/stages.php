<?php

global $authentification, $utilisateur;
if ($authentification->isAuthentifie() == false || 
        ($utilisateur->getPersonne()->getRole() != Personne::ETUDIANT &&
        $utilisateur->getPersonne()->getRole() != Personne::ADMIN)) {
    inclure_fichier('', '401', 'php');
    die;
}

inclure_fichier('stages', 'stages', 'js');
?>

<div id="stages">

	<h1>Recherche de stages</h1>
	<div id="annuaire" class="row">
		<div class="span4 columns">
			<form class="well form" id="form_stages">
				<fieldset class="control-group form-search">
					<input type="text" class="input-medium search-query" placeholder="Mots-clés">
					<button  id="submit_recherche" type="submit" class="btn btn-primary">Rechercher <i class="icon-search"></i></button>
				</fieldset>
				<fieldset class="control-group">
					<div class="control-group">
						<label class="control-label" for="annee">Année</label>
						<div class="controls">
							<select id="annee">
								<option value="">Toutes années</option>
								<option value="3">3ème année</option>
								<option value="4">4ème année</option>
								<option value="5">5ème année (PFE)</option>
							</select></br>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="lieu">Lieu</label>
						<div class="controls">
							<input type="text" id="lieu" placeholder="Lieu" /><br/>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="entreprise">Entreprise</label>
						<div class="controls">
							<input type="text" id="entreprise" placeholder="Entreprise" /><br/>
						</div>
					</div>
				</fieldset>

			</form>
		</div>
		
		<div class="span8 columns">
			<div class="alert alert-block alert-info">
					<h4 class="alert-heading">Propositions de Stage - Formulaire Etudiant</h4>
					Vous pouvez rechercher par mots-clés dans le titre ou la description du sujet proposé, par
				année pour laquelle vous êtes intéressés, par lieu (en entrant un nom de ville ou un numéro
				de département) ou encore par nom d'entreprise directement si vous le souhaitez.<br/>
				Vous pouvez effectuer une recherche tronquée à l'aide de l'opérateur joker <strong>*</strong>. Par exemple,
				rechercher avec le mot-clé <i>"mobil*"</i> permettra de rechercher tout ce qui commence par <i>mobil</i>,
				donc renverra les résultats <i>mobile, mobiles, mobilité,...</i>
			</div>
			<div id="information" class="alert alert-info"> </div>
		</div>
	</div>
	<div class="well" id="fenetre">
			<ul class="unstyled" id="resultats">
			</ul>
	</div>

</div>
