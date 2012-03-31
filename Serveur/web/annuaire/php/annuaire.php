<!--
	-----------------------------------------------------------
	ANNUAIRE - PAGE
	-----------------------------------------------------------
	Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
			 Contact - benjamin.planche@aldream.net
	---------------------
	Page affichant la liste des entreprises liées à l'AEDI, et offrant de consulter pour chacune le détail de leurs informations (description, contacts, relations, commentaires, ...)
!-->

	<script type="text/javascript">
	<!-- TODO Add commun var here -->

	var donnees_Atos = {
		description: {
			nom: "Atos",
			description: "Société française recrutant des tonnes de 4IF.",
			secteur: "SSII",
			commentaire: "",
		},
		contacts: [
			{nom: "Chuck", prenom: "Noris", metier: "Dieu", email:"chuck@atos.com", tel:"06666666666", priorite:1, commentaire:""},
			{nom: "Chucky", prenom: "Norissette", metier: "Déesse", email:"chuckky@atos.com", tel:"06666666667", priorite:0, commentaire:"A vérifier"}
		],
		relation: {
			parrainage : [
				{annee: 2012, commentaire:"Ok", couleur:1},
				{annee: 2011, commentaire:"Bof", couleur:0}
			],
			rif : [
				{annee: 2012, commentaire:"Ok", couleur:1},
				{annee: 2011, commentaire:"Retard Paiement", couleur:0}
			],
			stages: [
				{annee: 2012, nbSujets:12},
				{annee: 2011, nbSujets:5}
			],
			entretiens: [
				{annee: 2012, nbSessions:3},
				{annee: 2011, nbSessions:1}
			]
		},
		commentaires: [
			{nom: "Le Roux", prenom: "Bill", poste: "SG", date:1332615354000 , categorie:0, commentaire:"A contacter pour un parteneriat"},
			{nom: "B", prenom: "Dan", poste: "Eq En", date:1332215354000, categorie:3, commentaire:"A contacter pour un calin"}
		]
	};
</script>

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
	<div class="span3 columns liste_entreprises">
		<div class="tabbable">
			<?php
				if ($droitEdition) {
					echo '<button data-toggle="modal" href="#modalAjoutEntreprise" class="btn  btn-mini editionEntreprise" type=""><i class="icon-plus"></i></button>';
				}
			?>
			<ul class="nav nav-tabs">
				  <li class="active"><a href="#liste" data-toggle="tab"><i class="icon-list-alt"></i></a></li>
				  <li><a href="#recherche" data-toggle="tab"><i class="icon-search"></i></a></li>
			</ul>
			<div class="tab-content">
					<div class="tab-pane active" id="liste">
						<table class="table table-stripped">
							<tbody>
								<?php
									// Génération de la liste des noms d'entreprises :
									
									/* int */ $nb_entreprises = count($listeEntreprises);
									/* char */ $premiere_lettrePrec = substr($listeEntreprises[0]["NOM"], 0, 1);
									/* char */ $premiere_lettreSuiv = $premiere_lettrePrec;
									/* int */ $compteur = 0;
									/* string */ $lignes = '';
									
									for (/* int */ $i = 0; $i < $nb_entreprises; $i++) {
										$premiere_lettreSuiv = substr($listeEntreprises[$i]["NOM"], 0, 1);
										if ($premiere_lettrePrec != $premiere_lettreSuiv) { // On passe à la lettre suivante dans l'alphabet :
											// On ajoute la colonne affichant la lettre, et on affiche le tout :
											$lignes = '<tr><td  class="first" rowspan="'.$compteur.'">'.$premiere_lettrePrec.'</td>'.$lignes;
											echo $lignes;
											$compteur = 0;
											$lignes = '';
											$premiere_lettrePrec = $premiere_lettreSuiv;
										}
										
										// On génère les lignes :
										$compteur++;
										if (!empty($lignes)) {
											$lignes .= '<tr>';
										}
										$lignes .= '<td class="entreprise" id-entreprise='.$listeEntreprises[$i]["ID"].' ><a href="#'.$listeEntreprises[$i]["NOM"].'">'.$listeEntreprises[$i]["NOM"].'</a></td>';

										$lignes .=  '</tr>';
									}
									
									// On affiche le dernier contenu générer :
									$lignes = '<tr><td  class="first" rowspan="'.$compteur.'">'.$premiere_lettrePrec.'</td>'.$lignes;
									echo $lignes;
								?>
									
								<tr>
									<td  class="first" rowspan="3">A</td>
									<td class="entreprise" id-entreprise=1 ><a href="#Atos">Atos</a></td>
								</tr>
								<tr>
									<td class="entreprise" id-entreprise=2 >Axentis</td>
								</tr>
								<tr>
									<td class="entreprise" id-entreprise=3 >Alias</td>
								</tr>
								<tr>
									<td rowspan="2">B</td>
									<td>Bazoom</td>
								</tr>
								<tr>
									<td>Boxon</td>
								</tr>
																<tr>
									<td rowspan="3">A</td>
									<td>Atos</td>
								</tr>
								<tr>
									<td>Axentis</td>
								</tr>
								<tr>
									<td>Alias</td>
								</tr>
								<tr>
									<td rowspan="2">B</td>
									<td>Bazoom</td>
								</tr>
								<tr>
									<td>Boxon</td>
								</tr>
																<tr>
									<td rowspan="3">A</td>
									<td>Atos</td>
								</tr>
								<tr>
									<td>Axentis</td>
								</tr>
								<tr>
									<td>Alias</td>
								</tr>
								<tr>
									<td rowspan="2">B</td>
									<td>Bazoom</td>
								</tr>
								<tr>
									<td>Boxon</td>
								</tr>
																<tr>
									<td rowspan="3">A</td>
									<td>Atos</td>
								</tr>
								<tr>
									<td>Axentis</td>
								</tr>
								<tr>
									<td>Alias</td>
								</tr>
								<tr>
									<td rowspan="2">B</td>
									<td>Bazoom</td>
								</tr>
								<tr>
									<td>Boxon</td>
								</tr>
							</tbody>
						</table>
					</div>
				<div class="tab-pane" id="recherche">
					<p>Non-implémenté</p>
				</div>
			</div>
		</div> <!-- /tabbable -->
		
	</div>
	
	<div class="span9 columns desc_entreprise">
		<div class="hero-unit">
			<h1>Annuaire <small>Entreprises</small></h1>
			<p>Sélectionnez une entreprise à gauche pour obtenir l'ensemble des informations associées, et la liste de nos contacts.</p>
				
		</div>
	</div>
	
	<div id="ensembleModal" style="display:hidden;">
		<div class="modal hide fade in" id="modalAjoutEntreprise">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">×</a>
				<h3>Ajout d'une entreprise - Description générale</h3>
			</div>
			<form id="formAjoutEntreprise" class="form-horizontal" target="ajoutEntreprise.cible.php">
				<div class="modal-body">
										
				<fieldset class="control-group">
					<div class="control-group">
						<label class="control-label" for="formAjoutEntrepriseNom">Nom</label>
						<div class="controls">
							<input class="input-xlarge required" id="formAjoutEntrepriseNom" type="text" minlength="2" placeholder="Nom" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="formAjoutEntrepriseSecteur">Secteur</label>
						<div class="controls">
							<input class="input-xlarge required" id="formAjoutEntrepriseSecteur" type="text" minlength="2" placeholder="Secteur" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="formAjoutEntrepriseDescription">Description</label>
						<div class="controls">
							<textarea class="input-xlarge required" id="formAjoutEntrepriseDescription" rows="3"></textarea>
						</div>
					</div>
				</fieldset>
		 
				</div>
				<div class="modal-footer form-actions">
					<button href="#" class="btn" data-dismiss="modal">Annuler</button>
					<button type="reset" class="btn">RAZ</button>
					<button id="btnValiderAjoutEntreprise" href="#" class="btn btn-primary">Continuer</button>
				</div> 
			</form>
		</div>
		
		<div class="modal hide fade in" id="modalModifContact">
			<div class="modal-header">
				<a class="close" data-dismiss="modal">×</a>
				<h3>Contact - Edition</h3>
			</div>
			<form id="formModifContact" class="form-horizontal" target="updateContact.cible.php">
				<input id="formModifContactId" value=0 type="hidden"/>
				<div class="modal-body">
										
				<fieldset class="control-group">
					<div class="control-group">
						<label class="control-label" for="formModifContactNom">Nom & Prénom <i class="icon-asterisk"></i></label>
						<div class="controls">
							<input class="input-small required" id="formModifContactNom" type="text" placeholder="Nom" minlength="2" />
							<input class="input-medium required" id="formModifContactPrenom" type="text" placeholder="Prénom" minlength="2" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="formModifContactPoste">Poste <i class="icon-asterisk"></i></label>
						<div class="controls">
							<input class="input-xlarge required" id="formModifContactPoste" type="text" minlength="2" />
						</div>
					</div>
					<div id="formModifContactTelGroup" class="control-group">
						<label class="control-label" for="formModifContactTel">Téléphone <i class="icon-asterisk"></i></label>
						<div class="controls">
							<input class="input-medium required" id="formModifContactTel" placeholder="N° Téléphone"type="text" minlength="8" />
							 <select id="formModifContactTelLabel" name="formModifContactTelLabel" class="input-small">
								<option value="Bureau" >Bureau</option>
								<option value="Fixe" >Fixe</option>
								<option value="Mobile" >Mobile</option>
							</select>
							<span class="help-inline"><a title="Ajouter un autre numéro" id="formModifContactTelAjout" class="btn btn-small disabled ajoutTel"><i class="icon-plus"></i></a></span>
							<ul class="help-block"></ul>
						</div>
					</div>
					<div  id="formModifContactEmailGroup" class="control-group">
						<label class="control-label" for="formModifContactEmail">Email <i class="icon-asterisk"></i></label>
						<div class="controls">
							<input class="input-medium required" id="formModifContactEmail" placeholder="Email" type="text" minlength="6" />
							<select id="formModifContactEmailLabel" name="formModifContactEmailLabel" class="input-small">
								<option value="Bureau" >Pro</option>
								<option value="Fixe" >Perso</option>
							</select>
							<span class="help-inline"><a title="Ajouter un autre email" id="formModifContactEmailAjout" class="btn btn-small ajoutEmail disabled"><i class="icon-plus"></i></a></span>
							<ul class="help-block"></ul>
						</div>
					</div>
					
					<div class="control-group">
						<label class="control-label" for="formModifContactPriorite">Priorité & Commentaire</label>
						<div class="controls">
							<select id="formModifContactPriorite" name="formModifContactPriorite" class="input-medium">
								<option value=3 >3 - Prioritaire</option>
								<option selected="selected" value=2 >2 - Normale</option>
								<option value=1 >1 - Faible</option>
								<option value=0 >0 - Inconnue</option>
								<option value=-1 >X - Déconseillée</option>
							</select>
							<input class="input-medium" id="formModifContactCom" type="text" placeholder="Commentaire"/>
						</div>
					</div>
				</fieldset>
		 
				</div>
				<div class="modal-footer form-actions">
					<button href="#" class="btn" data-dismiss="modal">Annuler</button>
					<button type="reset" class="btn">RAZ</button>
					<button id="btnValiderAjoutEntreprise" href="#" class="btn btn-primary">Continuer</button>
				</div> 
			</form>
		</div>
	</div>
</div>		