<?php
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
		<label for="duree">Duree:</label>
			<select id="duree">
				<option value="">Toutes durées</option>
				<?php // Afficher les douze mois à la suite
				for($i = 1; $i < 12; $i++) {
					echo '<option value="' . $i . '">' . $i . ' mois</option>';
				}
				?>
				<option value="12">12 mois et plus</option>
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
