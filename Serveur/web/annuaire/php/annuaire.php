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
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';

inclure_fichier('controleur', 'entreprise.class', 'php');
inclure_fichier('annuaire', 'annuaire', 'css');
inclure_fichier('annuaire', 'annuaire.class', 'js');
inclure_fichier('annuaire', 'run', 'js');


$listeEntreprises = Entreprise::GetListeEntreprises();
print_r($listeEntreprises);
?>

<div id="annuaire" class="row" style="margin-top: 20px;">
	<div class="span3 columns liste_entreprises">
		<div class="tabbable">
			<ul class="nav nav-tabs">
				  <li class="active"><a href="#liste" data-toggle="tab"><i class="icon-list-alt"></i></a></li>
				  <li><a href="#recherche" data-toggle="tab"><i class="icon-search"></i></a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="liste">
					<table class="table table-stripped">
						<tbody>
							<?php
								$nb_entreprises = count($listeEntreprises);
								$premiere_lettrePrec = substr($listeEntreprises[0]["NOM"], 0, 1);
								$premiere_lettreSuiv = $premiere_lettrePrec;
								$compteur = 0;
								$lignes = '';
								
                                for ($i = 0; $i < $nb_entreprises; $i++) {
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
									$lignes .= '<td class="entreprise" id-entreprise='.$listeEntreprises[$i]["ID"].' ><a href="#'.$listeEntreprises[$i]["NOM"].'">'.$listeEntreprises[$i]["NOM"].'</a></td></tr>';
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
</div>		