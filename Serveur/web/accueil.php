<?php
/**
 * -----------------------------------------------------------
 * Vue - ACCUEIL
 * -----------------------------------------------------------
 * Auteur : Benjamin (Bill) Planche - Aldream (4IF 2011/12)
 *          Contact - benjamin.planche@aldream.net
 * ---------------------
 * Page d'accueil avec une description générale, les principaux sponsors, ...
 */
?>

<div id="accueil">
	<div class="row" style="margin-top: 20px;">
		<div class="span4">
			<div class="row">
				<p class="span4">
					<img id="logo" style="float:left; margin-left:10%;" src="commun/img/logo_aedi.png" width="120px" alt="AEDI" />
					<img style="float:right; margin-right:10%;" id="nom_aedi" src="commun/img/nom_aedi.png" alt="AEDI" class="adapt-width" span=2 scale=0.85 max=150 />
				</p>
			</div>
			<div class="well">
				<p style="text-align:justify;"><span class="hero_motcle">AEDI</span> <small>(nf)</small> : <em>Association de l'INSA-Lyon créée pour renforcer la cohésion entre les étudiants du Département Informatique, les aider dans leur cursus, et établir des contacts privilégiés avec les entreprises.</em></p>
				<p style="text-align:justify;">Cela fait plus de vingt-neuf ans que notre association étoffe son éventails d'évènements, de la Semaine d'Intégration des nouveaux élèves aux Rencontres IF, forum ouvert aux entreprises, en passant par le Week-End Ski, le Concert IF, le Voyage de fin d'étude, ...</p>
				<p style="text-align:justify;">Avec à sa tête une équipe dynamique, l'AEDI s'épanouit et grandit, riche des liens qu'elle tisse avec les entreprises et anciens étudiants.</p>
				<p style="text-align:justify; margin-bottom:20px;"><strong>Soyez le ou la bienvenu(e) sur notre site !</strong>
				</p>
				<div style="width:50%; float:left; display:inline-block;" >
					<div style="position:relative; top:-3px; display:inline-block;" class="fb-like" data-href="http://www.facebook.com/AEDI.INSA.Lyon" data-send="false" data-layout="button_count" data-width="0" data-show-faces="false"></div>
					<p style="margin-top:10px; margin-left: 5%; display:inline-block;"><a href="https://twitter.com/AEDInsa" class="twitter-follow-button" data-show-count="false" data-lang="fr" data-show-screen-name="false">Suivre @AEDInsa</a></p>
				</div>
				<p style="width:50%; text-align:right; display:inline-block;">
					<a href="index.php?page=A_Propos" class="btn btn-primary btn-large"><i class="icon-search icon-white"></i> En savoir plus</a>
				</p>
			</div>
		</div>
		<div class="span8 columns">
		  <div id="photoCarousel" class="carousel slide">
			<div class="carousel-inner">
			   <div class="item active">
				<img class="adapt-width" span=8 src="./commun/img/photo_caroussel/conference.jpg" width="770px" alt="Conférence AEDI">
				<div class="carousel-caption">
				  <h4>Une association qui relie Etudiants et Entreprises</h4>
				  <p>A travers les évènements qu'elle organise, l'AEDI a pour but de renforcer la cohésion entre les étudiants du Département, tout en leur offrant des contacts privilégiés avec les entreprises.</p>
				</div>
			  </div>
			  <div class="item">
				<img class="adapt-width" span=8 src="./commun/img/photo_caroussel/equipe.jpg" width="770px" alt="Equipe AEDI">
				<div class="carousel-caption">
				  <h4>Une équipe dynamique prête à vous accueillir</h4>
				  <p>L'AEDI est gérée par une équipe d'étudiants bénévoles et actifs, aussi à l'aise pour les évènements étudiants que pour le dialogue avec les entreprises.</p>
				</div>
			  </div>
			  <div class="item">
				<img class="adapt-width" span=8 src="./commun/img/photo_caroussel/rif.jpg" width="770px" alt="Rencontres IF">
				<div class="carousel-caption">
				  <h4>De multiples évènements professionnels</h4>
				  <p>Tout au long de l'année, l'AEDI convie des entreprises à des conférences, déjeunes-métiers, etc, le but étant de créer des liens forts entre étudiants et sociétés. Le point fort de cette politique est la Journée de Rencontres IF, durant laquelle sont conviées une trentaine de grandes entreprises.</p>
				</div>
			  </div>
			  <div class="item">
				<img class="adapt-width" span=8 src="./commun/img/photo_caroussel/integration.jpg" width="770px" alt="Rencontres IF">
				<div class="carousel-caption">
				  <h4>Détente et Cohésion</h4>
				  <p>Car il n'est pas facile de débarquer dans l'univers insalien, l'AEDI prévoit chaque année de multiples évènements pour intégrer les nouveaux venus et instaurer une ambiance chaleureuse.</p>
				</div>
			  </div>
			  <div class="item">
				<img class="adapt-width" span=8 src="./commun/img/photo_caroussel/integration2.jpg" width="770px" alt="Equipe AEDI">
				<div class="carousel-caption">
				  <h4>... alors rejoignez-nous !</h4>
				  <p>N'hésitez pas à nous contacter, au local, par mails, par téléphone, etc ... Nous nous ferons un plaisir de discuter avec vous !</p>
				</div>
			  </div>
			</div>

			<a class="left carousel-control" href="#photoCarousel" data-slide="prev">&lsaquo;</a>
			<a class="right carousel-control" href="#photoCarousel" data-slide="next">&rsaquo;</a>
		  </div>
		</div>
	</div>
	<div class="row" style="text-align:center;">
		<p class="span2">
			<a href="http://www.insa-lyon.fr/" title="INSA-Lyon"><img class="adapt-width" span=2 scale=0.75 max=200 src="./commun/img/insa_logo.png" alt="INSA-Lyon"></a>
		</p>
		<p class="span2">
			<a href="http://if.insa-lyon.fr/" title="Département Informatique"><img class="adapt-width" span=2 scale=0.75 max=200 src="./commun/img/if_logo.png" alt="Département Informatique"></a>
		</p>
		<p class="span3">
			<a href="http://www.altran.fr/" title="Altran - Parrain Promo 2013"><img class="adapt-width" span=3 scale=0.75 max=200 src="./commun/img/parrains_caroussel/altran.png" alt="Altran"></a>
		</p>
		<p class="span2">
			<a href="http://www.axa.fr/" title="Axa - Parrain Promo 2015"><img class="adapt-height adapt-width" span=2 scale=0.35 max=75 src="./commun/img/parrains_caroussel/axa2.png" alt="Axa"></a>
		</p>
		<p class="span3">
			<a href="http://www.thalesgroup.com/" title="Thalès - Parrain Promo 2014"><img class="adapt-width" span=3 scale=0.75 max=200 src="./commun/img/parrains_caroussel/thales.png" alt="Thalès"></a>
		</p>
	</div>

</div>
	<div id="fb-root"></div>
	<script>
		$(document).ready(function() {
			adaptWidthToSpan();
			adaptHeightToSpan();
		});
		
		// Facebook :
		(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
		
		// Tweeter :
		!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");

		// Caroussels :
		$('#photoCarousel').carousel({
			interval: 10000
		})
		
		$('#parrainsCarousel').carousel({
			interval: 5000
		})
	</script>
