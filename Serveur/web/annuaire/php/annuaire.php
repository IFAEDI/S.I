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
// Inclusion des fichiers nécessaires (beurk, des includes en plein milieu de page ...) :
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';

inclure_fichier('controleur', 'entreprise.class', 'php');
inclure_fichier('annuaire', 'annuaire', 'css');
inclure_fichier('annuaire', 'annuaire.class', 'js');
inclure_fichier('annuaire', 'run', 'js');
inclure_fichier('commun', 'jquery.validate.min', 'js');

// TEST :
$droitEdition = true;

// Récupération de la liste des noms d'entreprises :
$listeEntreprises = Entreprise::GetListeEntreprises();
?>

<div id="annuaire" class="row">
	<input type="hidden" id="inputModif" name="inputModif" value=<?php echo ($droitEdition? 1:0); ?> />
	<div class="span2 columns liste_entreprises">
		<div class="tabbable">
			<?php
				if ($droitEdition) {
					echo '<button title="Ajouter Entreprise" data-toggle="modal" href="#modalUpdateEntreprise" class="btn  btn-mini editionEntreprise" type=""><i class="icon-plus"></i></button>';
				}
			?>
			<ul class="nav nav-tabs">
				  <li class="active"><a href="#liste" data-toggle="tab"><i class="icon-list-alt"></i></a></li>
				  <li><a href="#recherche" data-toggle="tab"><i class="icon-search"></i></a></li>
			</ul>
			<div class="tab-content">
					<div class="tab-pane active" id="liste">
						<table id="listeEntreprises" class="table table-stripped">
							<tbody>
								<?php
									// Génération de la liste des noms d'entreprises :
									
									/* int */ $nb_entreprises = count($listeEntreprises);
									
									echo '<script type="text/javascript">';
									for (/* int */ $i = 0; $i < $nb_entreprises; $i++) {
										echo 'Annuaire.listeEntreprises['.$listeEntreprises[$i]->getId().'] = "'.$listeEntreprises[$i]->getNom().'";';
									}
									echo 'Annuaire.afficherListeEntreprises();</script>';
								?>
							</tbody>
						</table>
					</div>
				<div class="tab-pane" id="recherche">
					<p>Non-implémenté</p>
				</div>
			</div>
		</div> <!-- /tabbable -->
		
	</div>
	
	<div class="span10 columns desc_entreprise">
		<div class="hero-unit">
			<h1>Annuaire <small>Entreprises</small></h1>
			<p>Sélectionnez une entreprise à gauche pour obtenir l'ensemble des informations associées, et la liste de nos contacts.</p>
				
		</div>
	</div>
	
	<div id="ensembleModal" style="display:hidden;">
		<div class="modal hide fade in" id="modalUpdateEntreprise">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">×</a>
				<h3>Ajout d'une entreprise - Description générale</h3>
			</div>
			<form id="formUpdateEntreprise" class="form-horizontal" target="ajoutEntreprise.cible.php">
				<input id="formUpdateEntrepriseId" value=0 type="hidden"/>
				<div class="modal-body">
										
				<fieldset class="control-group">
					<div class="control-group">
						<label class="control-label" for="formUpdateEntrepriseNom">Nom <i class="icon-asterisk"></i></label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on"><i class="icon-flag"></i></span><input class="input-large required" id="formUpdateEntrepriseNom" type="text" minlength="2" placeholder="Nom" />
							</div>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="formUpdateEntrepriseSecteur">Secteur <i class="icon-asterisk"></i></label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on"><i class="icon-tag"></i></span><input class="input-large required" id="formUpdateEntrepriseSecteur" type="text" minlength="2" placeholder="Secteur" />
							</div>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="formUpdateEntrepriseDescription">Description <i class="icon-asterisk"></i></label>
						<div class="controls">
							<textarea class="input-xlarge required" id="formUpdateEntrepriseDescription" rows="3"  placeholder="Description" ></textarea>
						</div>
					</div>
				</fieldset>
		 
				</div>
				<div class="modal-footer form-actions">
					<a href="#" class="btn" data-dismiss="modal">Annuler</a>
					<a type="reset" class="btn">RAZ</a>
					<a id="btnValiderUpdateEntreprise" href="#" class="btn btn-primary">Continuer</a>
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
						
						<label class="control-label" for="formUpdateContactNom">Nom & Prénom <i class="icon-asterisk"></i></label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on"><i class="icon-user"></i></span><input class="input-small required" id="formUpdateContactNom" type="text" placeholder="Nom" minlength="2" />
							</div>
							
							<input class="input-medium required" id="formUpdateContactPrenom" type="text" placeholder="Prénom" minlength="2" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="formUpdateContactPoste">Fonction <i class="icon-asterisk"></i></label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on"><i class="icon-tag"></i></span><input class="input-xlarge required" id="formUpdateContactPoste" type="text" minlength="2" />
							</div>
						</div>
					</div>
					<div id="formUpdateContactTelGroup" class="control-group">
						<label class="control-label" for="formUpdateContactTel">Téléphone <i class="icon-asterisk"></i></label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on">#</span><input class="input-medium required number" id="formUpdateContactTel" placeholder="N° Téléphone"type="text" minlength="8" />
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
						<label class="control-label" for="formUpdateContactEmail">Email <i class="icon-asterisk"></i></label>
						<div class="controls">
							<div class="input-prepend">
								<span class="add-on">@</span><input class="input-medium required email" id="formUpdateContactEmail" placeholder="Email" type="text" minlength="6" />
							</div>
							<select id="formUpdateContactEmailLabel" name="formUpdateContactEmailLabel" class="input-small">
								<option value="Pro" >Pro</option>
								<option value="Perso" >Perso</option>
							</select>
							<span class="help-inline"><a title="Ajouter un autre email" id="formUpdateContactEmailAjout" class="btn btn-small ajoutEmail disabled"><i class="icon-plus"></i></a></span>
							<ul class="help-block"></ul>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="formUpdateContactPriorite">Priorité & Commentaire</label>
						<div class="controls">
							<select id="formUpdateContactPriorite" name="formUpdateContactPriorite" class="input-medium">
								<option value=3 >3 - Prioritaire</option>
								<option selected="selected" value=2 >2 - Normale</option>
								<option value=1 >1 - Faible</option>
								<option value=0 >0 - Inconnue</option>
								<option value=-1 >X - Déconseillée</option>
							</select>
							<div class="input-prepend">
								<span class="add-on"><i class="icon-comment"></i></span><input class="input-medium" id="formUpdateContactCom" type="text" placeholder="Commentaire"/>
							</div>
						</div>
					</div>
				</fieldset>
		 
				</div>
				<div class="modal-footer form-actions">
					<a href="#" class="btn" data-dismiss="modal">Annuler</a>
					<a type="reset" class="btn">RAZ</a>
					<a id="btnValiderUpdateContact" href="#" class="btn btn-primary">Continuer</a>
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
	</div>
</div>		