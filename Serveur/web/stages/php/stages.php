<?php

global $authentification, $utilisateur;
if ($authentification->isAuthentifie() == false || 
        $utilisateur->getPersonne()->getRole() != Personne::ETUDIANT) {
    inclure_fichier('', '401', 'php');
    die;
}

inclure_fichier('stages', 'stages', 'css');
?>

<script src='/stages/js/stages.js'></script>

<div id="stages">

<h1>Recherche de stages</h1>
<div class="well">
Ce formulaire vous permet de rechercher une proposition de stage, en tant qu'étudiant.<br/>
Vous pouvez rechercher par mots-clés dans le titre ou la description du sujet proposé, par
année pour laquelle vous êtes intéressés, par lieu (en entrant un nom de ville ou un numéro
de département) ou encore par nom d'entreprise directement si vous le souhaitez.<br/>
Vous pouvez effectuer une recherche tronquée à l'aide de l'opérateur joker *. Par exemple,
rechercher avec le mot-clé "mobil*" permettra de rechercher tout ce qui commence par mobil,
donc renverra les résultats mobile, mobiles, mobilité,...
</div>

<form class="well form-vertical" id="form_stages">	
	<input type="text" id="mots_cles" placeholder="Mots clés" /><br/>
	<select id="annee">
		<option value="">Toutes années</option>
		<option value="3">3ème année</option>
		<option value="4">4ème année</option>
		<option value="5">5ème année (PFE)</option>
	</select></br>
	<input type="text" id="lieu" placeholder="Lieu" /><br/>
	<input type="text" id="entreprise" placeholder="Entreprise" /><br/>
	<input type="submit" value="Rechercher" id="submit_recherche" class="btn submit" />
</form>

<div id="information" class="alert alert-info"> </div>

<div class="well" id="fenetre">

<ul class="unstyled" id="resultats">
</ul>

</div>

</div>
