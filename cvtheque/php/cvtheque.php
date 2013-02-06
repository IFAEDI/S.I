<?php
/*
 * @author Loïc Gevrey
 *
 *
 */
global $authentification;
global $utilisateur;


if ($authentification->isAuthentifie() == false ||
        $utilisateur->getPersonne()->getRole() != Personne::ADMIN &&
        $utilisateur->getPersonne()->getRole() != Personne::ENTREPRISE) {
    inclure_fichier('', '401', 'php');
    die;
}

inclure_fichier('controleur', 'etudiant.class', 'php');

if ($utilisateur->getPersonne()->getRole() == Personne::ENTREPRISE && Etudiant::AccesCVtheque($utilisateur->getPersonne()->getId()) != 1) {
    inclure_fichier('', '401', 'php');
    die;
}


inclure_fichier('cvtheque', 'cvtheque', 'js');
?>

<div id="module_cv">
	<h1>CVthèque - Consultation</h1>
	<div class="row">
		<div class="span5 columns">
			<form class="form-horizontal well">
				<fieldset class="control-group">
					<div class="control-group">
						<label class="control-label" for="annee_voulu">Année</label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on"><i class="icon-list-alt"></i></span><select id="annee_voulu" class="input-small">
									<option value="">Toute</option>
									<option value="3">3ème année</option>
									<option value="4">4ème année</option>
									<option value="5">5ème année</option>
									<option value="0">Ingénieur</option>
									<option value="-1">Favoris</option>
								</select> 
							</div>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="mot_clef_voulu">Mot-clé</label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on"><i class="icon-tag"></i></span><input class="input-medium" id="mot_clef_voulu" type="text"  placeholder="Mot-clé" />
							</div>
						</div>
					</div>
					<a class="btn btn-primary offset1" href="javascript:Rechercher();"><i class="icon-search icon-white"></i> Rechercher</a>
				</fieldset>
			</form>
		</div>
		<div class="span7 columns">
			<div class="alert alert-block alert-info">
			<h4 class="alert-heading">Formulaire de sélection</h4>
			Le formulaire ci-contre a pour but d'affiner vos consultations et recherches au sein de notre CVthèque.<br/>
			Il vous est ainsi possible de sélectionner l'année d'étude désirée, ou d'entrer un mot-clé.
			</div>
		</div>
	</div>
	<table style="width: 100%;">
		<tr valign="top">
			<td id="div_liste_cv" style="width: 220px; max-height: 500px; overflow: auto; display: inline-block;"></td>
			<td id="div_cv"></td>
		</tr>
	</table>

</div>
