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

<div id="menu">
<h5>Recherche</h5>
		<form id="form_stages">	
		<label for="mots_cles">Mots Cles:</label><input type="text" id="mots_cles" /><br />
		<label for="annee">Annee:</label>
			<select id="annee">
				<option value="">Toutes années</option>
				<option value="3">3ème année</option>
				<option value="4">4ème année</option>
				<option value="5">5ème année (PFE)</option>
			</select>
		<br/>
		<label for="lieu">Lieu:</label><input type="text" id="lieu" /><br />
		<label for="entreprise">Entreprise:</label><input type="text" id="entreprise" /><br />
		<input type="submit" value="Rechercher" id="submit_recherche" /><br />
		</form>
</div>

<div id="fenetre">

<div id="information"> </div>

<ol id="resultats">
</ol>

</div>

</div>
