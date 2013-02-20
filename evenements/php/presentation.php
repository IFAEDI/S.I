<?php
/**
 * -----------------------------------------------------------
 * Vue - EVENEMENTS ETUDIANTS, PRESENTATION
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Contact - benjamin.planche@aldream.net
 * ---------------------
 * Page présentant les évènements et services de l'AEDI aux étudiants.
 */
 
 // In order to handle tabs, page reloads & browser forward/back history :
 inclure_fichier('commun', 'jquery.ba-hashchange.min', 'js');
?>

<div id="evenements">
	<div class="row" style="margin-top: 20px;">
		<div class="span2">
			<div class="tabbable tabs-left">
				<ul class="nav nav-pills nav-stacked">
					<li class="active"><a href="#divertissements" data-toggle="tab">Divertissements</a></li>
					<li><a href="#entreprises" data-toggle="tab">Entreprises</a></li>
					<li><a href="#salle-detente" data-toggle="tab">Salle Détente</a></li>
				</ul>
			</div>
		</div>
		<div class="span10">
			<div class="tab-content">
				<div class="tab-pane active" id="divertissements">
					<div class="hero-unit">
						<h1>Divertissements</h1>
						<p>Convivialité, camaraderie, épanouissement, ... Nous jugeons ces notions indissociables d'une scolarité réussie. C'est pourquoi l'AEDI prend à cœur de tisser des liens au sein des promotions et avec l'extérieur, en multipliant les évènements récréatifs, qu'ils soient de petite ou grosse envergure. Tu trouveras ci-dessous les exemples les plus emblématiques.</p>
					</div>
					<div class="row">
						<p class="span4"><img src="evenements/img/inte3.jpg" title="En IF, les musiciens sont à l'honneur !" /></p>
						<div class="span6 presentation">
							<h2>L'Intégration<i class="icon icon-flag icon-white"></i></h2>
							<p>Afin de faire découvrir leur nouvel environnement aux nouveaux IFs, et d'aider à tisser les premiers liens, des volontaires organisent à la rentrée, avec l'aide du parrain, une semaine d'évènements variés, s'achevant sur l'incontournable <strong>Week-End d'Intégration</strong>.<br/>
							<small>Date : Semaine de la rentrée</small></p>
						</div>
					</div>
					<div class="row">
						<div class="span6">
							<div class="presentation" style="margin-bottom: 10px;">
								<h2><i class="icon icon-music icon-white"></i> Le Concert IF</h2>
								<p>Les IFs ont du talent ! Prouve-le en participant à ce concert, avec ton groupe ou d'autres musiciens du Département ! <small>Date : Début Décembre</small></p>
							</div>
							<div class="presentation">
								<h2><i class="icon icon-asterisk icon-white"></i> Le Week-End Ski</h2>
								<p>Un week-end de folie où les étudiants IF se retrouvent pour profiter de la montagne et de la fondue ! Bonne ambiance assurée ! <small>Date : Fin Janvier</small></p>
							</div>
						</div>
						<p class="span4"><img src="evenements/img/inte.jpg" title="Sortie ensoleillée lors de l'Intégration." /></p>
					</div>
					<div class="row">
						<p class="span4" style="position:relative;top:-100px; margin-bottom:-90px;"><img src="evenements/img/inte2.jpg" title="Le Combat de Sumo, discipline phare du Département." /></p>
						<div>
							<div class="span6 presentation" style="margin-bottom: 10px;">
								<h2>La Soirée Post-Partiels <i class="icon icon-glass icon-white"></i></h2>
								<p><em>Après l'effort, le réconfort !</em> Organisée en fin d’année, cette soirée rassemble plusieurs centaines de participants de plusieurs campus ... <small>Date : Fin Avril</small></p>
							</div>
							<div class="span6 presentation">
								<h2>Le Barbecue de Fin d'Année <i class="icon icon-fire icon-white"></i></h2>
								<p>Soirée au coin du feu avec les camarades de promo pour célébrer l'arrivée de l'été, avant le début des stages. <small>Date : Fin Mai</small></p>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="span6">
							<div class="presentation" style="margin-bottom: 10px;">
								<h2><i class="icon icon-star icon-white"></i> La Remise des Diplômes</h2>
								<p>Véritable cérémonie durant laquelle sera remis ton diplôme tant mérité, avec le reste de ta promo et ta famille. <small>Date : Début Février</small></p>
							</div>
							<div class="presentation">
								<h2><i class="icon icon-plane icon-white"></i> Le Voyage de Fin d'Etude</h2>
								<p>Dernière aventure de la promo, l’AEDI organise ce voyage qui aura lieu pendant votre PFE (dernier stage en 5ème année). <small>Date : Juin</small></p>
							</div>
						</div>
						<p class="span4"><img src="evenements/img/rdd.jpg" title="La Remise des Diplômes, plein d'émotions !" width=350/></p>
					</div>
					
				</div>
				<div class="tab-pane" id="entreprises">
					<div class="hero-unit">
						<h1>Entreprises</h1>
						<p>
							L'AEDI est un acteur de premier plan dans le démarche professionnalisante menée par le Département Informatique durant la formation. En proposant diverses occasions d'échanger de maniere privilegiée avec des ingenieurs et acteurs du monde informatique, nous espérons ainsi t'aider à acquérir les ressources professionnelles nécessaires à l'épanouissement de ta carrière.
						</p>
					</div>
					<div class="row">
						<p class="span4"><img src="evenements/img/rif.jpg" title="Les RIFs sont une occasion unique pour rencontrer de grandes entreprises." /></p>
						<div class="span6 presentation">
							<h2>Les Rencontres IF<i class="icon icon-retweet icon-white"></i></h2>
							<p>Plus d'une vingtaine d'entreprises sont invitées au sein du Département afin de venir à la rencontre des étudiants ! La journée, banalisée pour l'évènement, te permettra de venir te renseigner, poser des questions, prendre des contacts, et peut-être obtenir ton futur stage ou emploi !<br/>
							Il s'agit d'une chance unique pour cotoyer de grandes entreprises de l'informatique, alors prépare dès à présent tes CV et ton costume !<br/>
							<small>Date : Mi Janvier</small></p>
						</div>
					</div>
					<div class="row">
						<div class="span6 presentation">
							<h2><i class="icon icon-volume-up icon-white"></i> Les Conférences</h2>
							<p>Durant une heure ou deux, des entreprises viennent présenter leurs métiers, ou des concepts qu'elles ont à cœur. C'est l'opportunité de découvrir les tendances actuelles qui agitent le monde professionnel, et peut-être d'échanger de manière conviviale, autour d'un toast, avec des experts dans leur domaine !<br/>
							<small>Date : Les Jeudis après-midi</small></p>
						</div>
						<p class="span4"><img src="evenements/img/conf.jpg" title="Nouvelles technologies ou orientations professionnelles ... Les conférences proposent des sujets au goût de chacun." /></p>
					</div>
					<div class="row">
						<p class="span4"><img src="evenements/img/simulation.jpg" title="Grâce au Département et à l'AEDI, vous aurez la chance de rencontrer de nombreux professionnels et bénéficier de leurs conseils." /></p>
						<div class="span6 presentation">
							<h2>Les Simulations d'entretiens <i class="icon icon-comment icon-white"></i></h2>
							<p>Proposées par des sociétés partenaires, ces simulations te permettent de mettre ton argumentaire et ton charisme à l'épreuve de recruteurs expérimentés, qui te prodigueront remarques et conseils, et t'aideront peut-être même à mieux cibler ton propre profil.<br/>
							<small>Date : Les Jeudis après-midi</small></p>
						</div>
					</div>
					
				</div>
				<div class="tab-pane" id="salle-detente">
					<div class="hero-unit">
						<h1>Salle Détente</h1>
						<p>Sanctuaire au cœur du Département (2ème étage), tu y passeras ton temps libre <small>(peut-être tes nuits)</small>. Outre les canapés, l'AEDI met à ta disposition des jeux <small>(une asso renégate de coincheurs hanterait les lieux ...)</small> et du café <small>(ton futur meilleur ami, si ce n'est pas déja le cas).</small></p>
					</div>
					<div class="row">
						<div class="span10">
							<p class="thumbnail"><img src="evenements/img/salle_detente.jpg" title="Aperçu de la salle détente" /></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(document).ready(function() {

			// Gist from chainer and lili262 : https://github.com/twitter/bootstrap/pull/581#issuecomment-4966967
			// Allow to save the navigation with the tabs (reloads, back, fav, ...)
			// -------
			$(function(){
				// Function to activate the tab
				function activateTab() {
					var activeTab = $('[href=' + window.location.hash.replace('/', '') + ']'); // The antislash added before the anchor's name prevents to scroll until the anchor's element. 
					activeTab && activeTab.tab('show');
				}

				// Trigger when the page loads
				activateTab();

				// Trigger when the hash changes (forward / back)
				$(window).hashchange(function(e) {
					activateTab();
				});

				// Change hash when a tab changes
				$('a[data-toggle="tab"], a[data-toggle="pill"]').on('shown', function () {
					window.location.hash = '/' + $(this).attr('href').replace('#', '');
				}); 
			});
			// -------

			
			// Caroussels :
			$('#photoCarousel').carousel({
				interval: 10000
			})
			

		});

	</script>
</div>
