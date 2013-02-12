<!--
	-----------------------------------------------------------
	ANNUAIRE - PAGE
	-----------------------------------------------------------
	Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
			 Contact - benjamin.planche@aldream.net
	---------------------
	Page affichant la liste des entreprises liées à l'AEDI, et offrant de consulter pour chacune le détail de leurs informations (description, contacts, relations, commentaires, ...)
!-->

<?php

global $authentification, $utilisateur;
if (($authentification->isAuthentifie() == false) || 
        (($utilisateur->getPersonne()->getRole() != Personne::AEDI) && ($utilisateur->getPersonne()->getRole() != Personne::ADMIN))) {
    inclure_fichier('', '401', 'php');
    die;
}

// Inclusion des fichiers nécessaires (beurk, des includes en plein milieu de page ...) :
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';

inclure_fichier('modele', 'entreprise.class', 'php');
inclure_fichier('modele', 'contact.class', 'php');
inclure_fichier('annuaire', 'annuaire.class', 'js');
inclure_fichier('commun', 'validate', 'js');
inclure_fichier('commun', 'dateFormat', 'js');
inclure_fichier('commun', 'handlebars-1.0.0.beta.6', 'js');
inclure_fichier('commun', 'jquery.highlight', 'js');
inclure_fichier('annuaire', 'templateInfoEntreprise', 'template');
inclure_fichier('annuaire', 'templateSearchContact', 'template');

// TEST :
$droitEdition = true;

// Récupération de la liste des noms d'entreprises :
$listeEntreprises = Entreprise::GetListeEntreprises();
$listeSecteurs = Entreprise::GetListeSecteurs();
$listePostes = Contact::GetListeFonctions();
?>



<div id="annuaire" class="row">
	<script type="text/javascript">
		<?php
			// Génération de la liste des noms d'entreprises :								
			/* int */ $nb_entreprises = count($listeEntreprises);	
			for (/* int */ $i = 0; $i < $nb_entreprises; $i++) {
				echo 'Annuaire.listeEntreprises['.$i.'] = ['.Protection_XSS($listeEntreprises[$i]->getId()).', "'.Protection_XSS($listeEntreprises[$i]->getNom()).'"];';
			}

			// On en profite pour passer au JS des info sur l'utilisateur :
			echo 'Annuaire.utilisateur = {personne:{prenom:"'.Protection_XSS($utilisateur->getPersonne()->getPrenom()).'", nom:"'.Protection_XSS($utilisateur->getPersonne()->getNom()).'", role:'.Protection_XSS($utilisateur->getPersonne()->getRole()).'}};';
							
			// Génération de la liste des secteurs déja en BDD :
			/* int */ $nb_secteurs = count($listeSecteurs);
			for (/* int */ $i = 0; $i < $nb_secteurs; $i++) {
				echo 'Annuaire.listeSecteurs['.$i.'] = "'.Protection_XSS($listeSecteurs[$i]).'";';
			}
			
			// Génération de la liste des fonctions déja en BDD :
			/* int */ $nb_postes = count($listePostes);
			for (/* int */ $i = 0; $i < $nb_postes; $i++) {
				echo 'Annuaire.listePostes['.$i.'] = "'.Protection_XSS($listePostes[$i]).'";';
			}
		?>
	</script>
	<div id="rechercheHelp" style="display:none;">
		<h5>Mots-clés</h5>
		<p>Plusieurs mots-clés (mots complets ou fragments, nombres, ...) peuvent être entrés lors de la recherche, séparés par des espaces et/ou virgules. La recherche retournera alors les contacts contenant l'ensemble des mots-clés donnés dans leurs attributs.<br/>
			<small><em>Ex : "SociétéX Jean,Mart , 0453"</em></small>
		</p>
		<h5>Champs</h5>
		<p>Il est possible de limiter la porter d'un mot-clé à un attribut précis, avec la syntaxe suivante : <em>attr:keyword</em> (pas d'espace avant ou après le point-virgule). Les attributs disponibles sont : <em>nom, prénom, entr, poste, email, tel, ville, cp, pays & rem.</em><br/>
			<small><em>Ex : "entr:SociétéX Jean,nom:Mart , 0453"</em></small>
		</p>
	</div>
	<input type="hidden" id="inputModif" name="inputModif" value=<?php echo ($droitEdition? 1:0); ?> />
	<div class="span2 columns">
		<div id="controleur">
			<div class="tabbable">
				<div id="boutonAjoutEntrepriseListe">
					<?php
						if ($droitEdition) {
							echo '<button title="Ajouter Entreprise" data-toggle="modal" href="#modalUpdateEntreprise" class="btn  btn-mini editionEntreprise" type=""><i class="icon-plus"></i></button>';
						}
					?>
				</div>
				<ul class="nav nav-tabs">
					  <li class="active"><a title="Liste" href="#liste" data-toggle="tab"><i class="icon-list-alt"></i></a></li>
					  <li><a id="recherche-tab" title="Recherche" href="#recherche" data-toggle="tab"><i class="icon-search"></i></a></li>
				</ul>
				<div class="tab-content">
						<div class="tab-pane active" id="liste">
							<table id="listeEntreprises" class="table table-stripped">
								<tbody>
								</tbody>
							</table>
						</div>
					<div class="tab-pane" id="recherche">
						<form id="formSearchContact">
							<input id="formSearchContactKeywords" type="text" class="span2 search-query" placeholder="Mots-clés">
							<div id="formSearchContactButton">
								<button id="formSearchContactSubmit" type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i> Rechercher</button>
								<button id="formSearchContactHelp" rel="popover" data-placement="right" data-original-title='<i class=\"icon-question-sign"></i> Recherche - Aide' class="btn btn-info"><i class="icon-question-sign icon-white"></i></button>
							  </div>
						</form>
					</div>
				</div>
			</div> <!-- /tabbable -->
		</div>
		
		<div style="" id="options">
			<form id="formOptions" class="form" target="ajoutEntreprise.cible.php">
				<fieldset class="control-group">
					<div class="control-group">
						<div class="controls">
							<label class="checkbox">
								<input name="formOptionsEdition" id="formOptionsEdition" value="0" type="checkbox"/>
								<span class="badge"><i class="icon-edit icon-white"></i></span>
							</label>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
		
	</div>
	
	<div class="span10 columns desc_entreprise">
		<div class="hero-unit">
			<h1>Annuaire <small>Entreprises</small></h1>
			<p>Sélectionnez une entreprise à gauche pour obtenir l'ensemble des informations associées, et la liste de nos contacts.</p>
				
		</div>
	</div>
	
	<div id="ensembleModal" style="display:hidden;">
		<div class="modal hide fade in" id="modalUpdateEntreprise">
			<form id="formUpdateEntreprise" name="formUpdateEntreprise" class="form-horizontal" target="#">
				<div class="modal-header">
					<a class="close reset" data-dismiss="modal">×</a>
					<h3><span class="type-action">Ajout d'une entreprise</span> - Description générale</h3>
				</div>
				<input id="formUpdateEntrepriseId" value=0 type="hidden"/>
				<div class="modal-body">
										
				<fieldset class="control-group">
					<div class="control-group">
						<label class="control-label" for="formUpdateEntrepriseNom">Nom <i class="icon-asterisk"></i> <span class="error"></span></label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on"><i class="icon-flag"></i></span><input class="input-large required" name="formUpdateEntrepriseNom" id="formUpdateEntrepriseNom" type="text" minlength="2" placeholder="Nom" autofocus />
							</div>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="formUpdateEntrepriseSecteur">Secteur <i class="icon-asterisk"></i> <span class="error"></span></label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on"><i class="icon-tag"></i></span><input class="input-large required" name="formUpdateEntrepriseSecteur" id="formUpdateEntrepriseSecteur" type="text" minlength="2" placeholder="Secteur" data-provide="typeahead" data-items="4" data-source="" autocomplete="off"/>
							</div>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="formUpdateEntrepriseDescription">Description <i class="icon-asterisk"></i> <span class="error"></span></label>
						<div class="controls">
							<textarea class="input-xlarge required" name="formUpdateEntrepriseDescription" id="formUpdateEntrepriseDescription" rows="3"  placeholder="Description" ></textarea>
						</div>
					</div>
				</fieldset>
		 
				</div>
				<div class="modal-footer form-actions">
					<a href="#" id="btnSupprimerEntreprise" class="btn btn-danger" data-dismiss="modal">Supprimer</button>
					<a href="#" class="btn reset" data-dismiss="modal">Annuler</a>
					<a class="btn reset">RAZ</a>
					<button type="submit" id="btnValiderUpdateEntreprise" class="btn btn-primary">Continuer</button>
				</div> 
			</form>
		</div>
		
		<div class="modal hide fade in" id="modalUpdateContact">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">×</a>
				<h3>Contact - Edition</h3>
			</div>
			<form id="formUpdateContact" class="form-horizontal" target="updateContact.cible.php">
				<input id="formUpdateContactId" value=0 type="hidden"/>
				<input id="formUpdateContactEntrepriseId" value=0 type="hidden"/>
				<input id="formUpdateContactPersonneId" value=0 type="hidden"/>
				<div class="modal-body">
										
				<fieldset class="control-group">
					<div class="control-group">
						
						<label class="control-label" for="formUpdateContactNom">Nom & Prénom <i class="icon-asterisk"></i> <span class="error"></span></label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on"><i class="icon-user"></i></span><input class="input-small required" name="formUpdateContactNom" id="formUpdateContactNom" type="text" placeholder="Nom" minlength="2" autofocus />
							</div>
							
							<input class="input-medium required" name="formUpdateContactPrenom" id="formUpdateContactPrenom" type="text" placeholder="Prénom" minlength="2" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="formUpdateContactPoste">Fonction <i class="icon-asterisk"></i> <span class="error"></span></label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on"><i class="icon-tag"></i></span><input class="input-xlarge required" name="formUpdateContactPoste" id="formUpdateContactPoste" type="text" minlength="2" data-provide="typeahead" data-items="4" data-source="" autocomplete="off"/>
							</div>
						</div>
					</div>
					<div id="formUpdateContactTelGroup" class="control-group">
						<label class="control-label" for="formUpdateContactTel">Téléphone <span class="error"></span></label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on">#</span><input class="input-medium required number" name="formUpdateContactTel" id="formUpdateContactTel" placeholder="N° Téléphone"type="text" minlength="8" />
							</div>
							 <select id="formUpdateContactTelLabel" name="formUpdateContactTelLabel" class="input-small">
								<option value="Bureau" >Bureau</option>
								<option value="Fixe" >Fixe</option>
								<option value="Mobile" >Mobile</option>
							</select>
							<span class="help-inline"><a title="Ajouter un autre numéro" id="formUpdateContactTelAjout" class="btn btn-small disabled ajoutTel"><i class="icon-plus"></i></a></span>
							<ul class="help-block"></ul>
						</div>
					</div>
					<div  id="formUpdateContactEmailGroup" class="control-group">
						<label class="control-label" for="formUpdateContactEmail">Email <span class="error"></span></label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on">@</span><input class="input-medium required email" name="formUpdateContactEmail" id="formUpdateContactEmail" placeholder="Email" type="text" minlength="6" />
							</div>
							<select id="formUpdateContactEmailLabel" name="formUpdateContactEmailLabel" class="input-small">
								<option value="Pro" >Pro</option>
								<option value="Perso" >Perso</option>
							</select>
							<span class="help-inline"><a title="Ajouter un autre email" id="formUpdateContactEmailAjout" class="btn btn-small ajoutEmail disabled"><i class="icon-plus"></i></a></span>
							<ul class="help-block"></ul>
						</div>
					</div>
					<div  id="formUpdateContactVilleGroup" class="control-group">
						<label class="control-label" for="formUpdateContactVille">Ville <span class="error"></span></label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on"><i class="icon-map-marker"></i></span><input class="input-medium required" name="formUpdateContactVilleLibelle" id="formUpdateContactVilleLibelle" placeholder="Ville" type="text" minlength="2" />
							</div>
							<input class="input-mini required" name="formUpdateContactVilleCodePostal" id="formUpdateContactVilleCodePostal" placeholder="Code" type="text" minlength="2" />
							<input class="input-small required" name="formUpdateContactVillePays" id="formUpdateContactVillePays" placeholder="Pays" type="text" minlength="3" />
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="formUpdateContactPriorite">Priorité & Commentaire <span class="error"></span></label>
						<div class="controls">
							<select id="formUpdateContactPriorite" name="formUpdateContactPriorite" class="input-medium">
								<option value=3 >3 - Prioritaire</option>
								<option id="formUpdateContactPrioriteDefaut" selected="selected" value=2 >2 - Normale</option>
								<option value=1 >1 - Faible</option>
								<option value=0 >0 - Inconnue</option>
								<option value=-1 >X - Déconseillée</option>
							</select>
							<div class="input-prepend">
								<span class="add-on"><i class="icon-comment"></i></span><input class="input-medium" name="formUpdateContactCom" id="formUpdateContactCom" type="text" placeholder="Commentaire"/>
							</div>
						</div>
					</div>
				</fieldset>
		 
				</div>
				<div class="modal-footer form-actions">
					<a href="#" class="btn" data-dismiss="modal">Annuler</a>
					<a type="reset" class="btn">RAZ</a>
					<button type="submit" id="btnValiderUpdateContact" class="btn btn-primary">Continuer</button>
				</div> 
			</form>
		</div>
		
		<div class="modal hide fade in" id="modalAjoutCommentaire">
			<form id="formAjoutCommentaire" class="form-horizontal" target="ajoutEntreprise.cible.php">
				<div class="modal-header">
					<a class="close reset" data-dismiss="modal">×</a>
					<h3>Ajout d'un commentaire</h3>
				</div>
				<input id="formAjoutCommentaireId" value=0 type="hidden"/>
				<div class="modal-body">
										
					<fieldset class="control-group">
						<div class="control-group">
							<label class="control-label">Catégorie</label>
							<div class="controls">
								<label class="radio inline">
									<input class="formAjoutCommentaireCateg" name="formAjoutCommentaireCategorie" id="formAjoutCommentaireCategorie1" value="0" checked="checked" type="radio"/>
									<span class="badge"><i class="icon-asterisk icon-white"></i></span>
								</label>
								<label class="radio inline">
									<input class="formAjoutCommentaireCateg" name="formAjoutCommentaireCategorie" id="formAjoutCommentaireCategorie2" value="-1" type="radio"/>
									<span class="badge badge-error"><i class="icon-remove icon-white"></i></span>
								</label>
								<label class="radio inline">
									<input class="formAjoutCommentaireCateg" name="formAjoutCommentaireCategorie" id="formAjoutCommentaireCategorie3" value="1" type="radio"/>
									<span class="badge badge-warning"><i class="icon-warning-sign icon-white"></i></span>
								</label>
								<label class="radio inline">
									<input class="formAjoutCommentaireCateg" name="formAjoutCommentaireCategorie" id="formAjoutCommentaireCategorie4" value="2" type="radio"/>
									<span class="badge badge-info"><i class="icon-info-sign icon-white"></i></span>
								</label>
								<label class="radio inline">
									<input class="formAjoutCommentaireCateg" name="formAjoutCommentaireCategorie" id="formAjoutCommentaireCategorie5" value="3" type="radio"/>
									<span class="badge badge-success"><i class="icon-ok icon-white"></i></span>
								</label>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="formAjoutCommentaireContenu">Commentaire <i class="icon-asterisk"></i> <span class="error"></span></label>
							<div class="controls">
								<textarea class="input-xlarge required"  name="formAjoutCommentaireContenu" id="formAjoutCommentaireContenu" rows="3"  placeholder="Commentaire" autofocus ></textarea>
							</div>
						</div>
					</fieldset>
		 
				</div>
				<div class="modal-footer form-actions">
					<a href="#" class="btn reset" data-dismiss="modal">Annuler</a>
					<a class="btn reset">RAZ</a>
					<button type="submit" id="btnValiderAjoutCommentaire" class="btn btn-primary">Continuer</button>
				</div> 
			</form>
		</div>
		
		<div class="modal hide fade in" id="modalConfirmation">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">×</a>
				<h3>Confirmation</h3>
			</div>
			<div class="modal-body">
				<p></p>					
			</div>
			<div class="modal-footer alert">
				<a href="#" class="btn" data-dismiss="modal">Non</a>
				<a id="btnModalConfirmer" href="#" class="btn btn-primary" data-dismiss="modal">Oui</a>
			</div> 
		</div>
		
		<div class="modal hide fade in" id="modalErreur">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">×</a>
				<h3>Erreur</h3>
			</div>
			<div class="modal-body">
				<p class="alert alert-error"></p>					
			</div>
			<div class="modal-footer alert">
				<a href="#" class="btn btn-primary" data-dismiss="modal">Ok</a>
			</div> 
		</div>
	</div>
	<?php
		inclure_fichier('annuaire', 'run', 'js');
	?>
</div>		
