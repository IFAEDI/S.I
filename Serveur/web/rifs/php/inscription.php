<!-- Formulaire pour l'inscription au RIFs -->
<form class="form-horizontal" onsubmit="return soumettreFormulaire()" id="formInscription" name="formInscription" method="post" enctype="multipart/form-data">
	<legend><h1>Formulaire d'inscription</h1></legend>
	<fieldset id="infoEntreprise">
		<legend>Informations sur l'entreprise</legend>
		<span class="comment"><em>* : Champ obligatoire</em></span>
		<div class="control-group" id="control_nomEntreprise">
			<label class="control-label" for="nomEntreprise">Nom de l'entreprise *</label>
			<div class="controls">
				<input class="input-medium" type="text" id="nomEntreprise" placeholder="Nom de l'entreprise..." />
			</div>
		</div>
		<div class="control-group" id="control_nomResponsable">
			<label class="control-label" for="nomResponsable">Nom du responsable *</label>
			<div class="controls">
				<input class="input-medium span" type="text" id="nomResponsable" placeholder="Nom" />
				<input class="input-medium span" type="text" id="prenomResponsable" placeholder="Prénom" />
			</div>
		</div>
		<div class="row-fluid">
			<div class="span3" id="control_tel">
				<label class="control-label" for="telephone">Téléphone *</label>
				<div class="controls">
					<input class="input-medium" type="tel" id="telephone" placeholder="N° de tél..." />
				</div>
			</div>
			<div class="span3" id="control_mail">
				<label class="control-label" for="mail">Adresse mail *</label>
				<div class="controls">
					<input class="input-medium" type="email" id="mail" />
				</div>
			</div>
		</div>
		<div class="control-group row-fluid" id="control_typeEntreprise">
			<div class="span3">
				<label class="control-label" for="typeEntreprise">Type d'entreprise *</label>
				<div class="controls">
					<select id="typeEntreprise" onchange="changementTypeEntreprise(this);">
						<option value="ssii" selected="selected">SSII</option>
						<option value="editeurLogiciel">Editeur Logiciel</option>
						<option value="constructeur">Constructeur</option>
						<option value="industrie">Industrie</option>
						<option value="telecom">Opérateur Télécom</option>
						<option value="banque">Banque</option>
						<option value="conseil">Cabinet de Conseil</option>
						<option value="autre">Autre</option>
						</select>
				</div>
			</div>
			<div id="autreTypeEntreprise" class="span3" style="display:none">
				<label class="control-label" for="typeEntrepriseAutre"><strong>Autre</strong></label>
				<div class="controls">
					<input type="text" class="input-medium" id="typeEntrepriseAutre" placeholder="Autre..." />
				</div>
			</div>
		</div>
		<div class="control-group" id="control_logoEntreprise">
			<label class="control-label" for="logoEntreprise">Logo de l'entreprise *</label>
			<div class="controls">
				<input type="hidden" name="MAX_FILE_SIZE" value="5000" />
				<input class="input-medium" type="file" accept="image/*" id="logoEntreprise" />
				<span class="help-inline">N'accepte que des fichiers images de taille inférieure à 5 Mo</span>
			</div>
		</div>
		<div class="control-group" id="control_descEntreprise">
			<label>Description de votre entreprise <em>(description qui apparaîtra sur la brochure de l'évènement) *</em></label>
			<div class="controls">
				<textarea class="input-xxlarge controleNomEntreprise" rows="3" id="descriptionEntreprise"></textarea>
			</div>
		</div>
	</fieldset>
	<fieldset id="infoGenerale">
		<legend>Informations Générales</legend>
		<span class="comment"><em>* : Champ obligatoire</em></span>
		<div class="control-group" id="control_intervenant">
			<label class="control-label">Nom - Prénom</label>
			<div class="controls" id="control_participants">
				<div class="nomPrenomIntervenant">
					<input class="input-medium span nomIntervenant" type="text" id="nomIntervenant[]" placeholder="Nom" />
					<input class="input-medium span prenomIntervenant" type="text" id="prenomIntervenant[]" placeholder="Prénom" />
					<i alt="Retirer l'intervenant" class="icon-remove link" onclick="enleverIntervenant(this)"></i>
				</div>
			</div>
			<span onclick="ajouterIntervenant()" class="link" style="margin-left:360px;" id="ajoutIntervenant">Ajouter un intervenant</span>
		</div>
		<div class="control-group" id="control_momentPresence">
			<label class="control-label">Présence *</label>
			<div class="controls">
				<label class="radio inline">
					<input id="momentPresence_matin" type="radio" value="Matin" name="momentPresence" />
					Matin
				</label>
				<label class="radio inline">
					<input id="momentPresence_apresMidi" type="radio" value="Apres-Midi" name="momentPresence" />
					Après-Midi
				</label>
				<label class="radio inline">
					<input id="momentPresence_journee" type="radio" checked="" value="Journee" name="momentPresence" />
					Journée entière
				</label>
			</div>
		</div>
		<div class="control-group" id="control_restaurant">
			<label class="control-label">Participation au restaurant *</label>
			<div class="controls">
				<label class="radio inline">
					<input id="restaurant_non" type="radio" checked="" value="non" name="restaurant"  onclick="document.getElementById('nbPers_restaurant').disabled = true;" />
					Non
				</label>
				<label class="radio inline">
					<input id="restaurant_oui" type="radio" value="oui" name="restaurant" onclick="document.getElementById('nbPers_restaurant').disabled = false;"/>
					Oui - Nombre de personnes :
					<input class="input-medium" type="number" min="1" max="8" value="1" step="1" id="nbPers_restaurant" disabled />
				</label>
			</div>
		</div>
		<div class="control-group" id="control_taxeApprentissage">
			<label class="control-label">Paiement de la taxe d'apprentissage à l'INSA *</label>
			<div class="controls">
				<label class="radio inline">
					<input id="TA_non" type="radio" checked="" value="non" name="TA" />
					Non
				</label>
				<label class="radio inline">
					<input id="TA_oui" type="radio" value="oui" name="TA" />
					Oui
				</label>
			</div>
		</div>
	</fieldset>
	<fieldset id="infoTechnique">
		<legend>Informations Techniques</legend>
		<div class="control-group row-fluid">
			<div class="span8">
				<label class="control-label" for="infoMatosTechnique">Description du matériel apporté</label>
				<div class="controls">
					<textarea class="input-xxlarge" rows="3" id="infoMatosTechnique"></textarea>
				</div>
			</div>
			<div class="span3">
				<label class="control-label" for="infoNbPrise">Nombre de prises électriques nécessaires</label>
				<div class="controls">
					<input class="input-medium" type="number" min="0" max="20" value="0" step="1" id="infoNbPrise" />
				</div>
			</div>
		</div>
	</fieldset>
	<fieldset id="infoComplementaire">
		<legend>Informations Complémentaires</legend>
		<div class="control-group" id="control_attente">
			<label>Quelles sont vos attentes concernant votre participation aux rencontres IF?</label>
			<div class="controls">
				<textarea class="input-xxlarge" rows="3" id="attente"></textarea>
			</div>
		</div>
		<div class="control-group" id="control_autre">
			<label>Autres (commentaires, remarques, ...)</label>
			<div class="controls">
				<textarea class="input-xxlarge" rows="3" id="autre"></textarea>
			</div>
		</div>
	</fieldset>
	<button class="btn btn-large offset9">Envoyer</button>
</form>

<?php
	inclure_fichier('rifs', 'script', 'js');
	inclure_fichier('rifs', 'rifs', 'css');

	// Chargement de la librairie plupload et des librairies nécessaires à son exécution
	/*inclure_fichier('rifs', 'plupload.full.js', 'js');
	inclure_fichier('rifs', 'jquery.ui.plupload.js', 'js');*/
?>