	var Annuaire = {};
	
	Annuaire.chercherInfoEntreprise = function chercherInfoEntreprise(idEntreprise) {
		// Requête Ajax :
		// TO DO
		
		return donnees_Atos;
	}
	
	Annuaire.traduirePrioriteContactTexte = function traduirePrioriteContactTexte(priorite) {
		if (priorite == 1) { return "Prioritaire" };
		if (priorite == 0) { return "Incertain" };
		if (priorite < 0) { return "A éviter" };
		return "Défaut";
	}
	
	Annuaire.traduireCouleur = function traduireCouleur(num) {
		if (num == 1) { return "success" };
		if (num == 0) { return "warning" };
		if (num < 0) { return "alert" };
		return "";
	}
	
	Annuaire.traduireCategorieCommentaire = function traduireCategorieCommentaire(num) {
		if (num == 0) { return '<span class="badge badge-error"><i class="icon-warning-sign icon-white"></i></span>' }; 	// Alerte
		if (num == 3) { return '<span class="badge badge-success"><i class="icon-heart icon-white"></i></span>' };			// Bonne nouvelle
		return '<span class="badge"><i class="icon-asterisk icon-white"></i></span>'; 										// Défaut
	}
	
	Annuaire.afficherInfoEntreprise = function afficherInfoEntreprise(idEntreprise) {
		// Récupération des données :
		var donnees = this.chercherInfoEntreprise(idEntreprise);
		
		// Génération des blocs intermédiaires (nécessitant des boucles) :
		var tableauContacts = '';
		for (var i in donnees.contacts) {
			tableauContacts += '			<tr>                                                             '+
'												<td>'+donnees.contacts[i].nom+'</td>                                               '+
'												<td>'+donnees.contacts[i].prenom+'</td>                                               '+
'												<td>'+donnees.contacts[i].metier+'</td>                                                '+
'												<td><a href="mailto:'+donnees.contacts[i].email+'">'+donnees.contacts[i].email+'</a></td>   '+
'												<td>'+donnees.contacts[i].tel+'</td>                                         '+
'												<td><span class="label label-'+Annuaire.traduireCouleur(donnees.contacts[i].priorite)+'">'+Annuaire.traduirePrioriteContactTexte(donnees.contacts[i].priorite)+'</span></td> '+
'											</tr>															 ';
		}
		
		var tableauParrainage = '';
		if (donnees.relation.parrainage.length == 0) { // Aucun parrainage avec
			tableauParrainage = '<tr><th>Parrainage</th><td>/</td><td><span class="label label-default">Jamais</span></td></tr> ';
		} else {
			tableauParrainage = '<tr><th rowspan='+donnees.relation.parrainage.length+'>Parrainage</th><td>Promo '+donnees.relation.parrainage[0].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(donnees.relation.parrainage[0].couleur)+'">'+donnees.relation.parrainage[0].commentaire+'</span></td></tr>';
			for (var i = 1; i < donnees.relation.parrainage.length; i++) {
				tableauParrainage += '<tr><td>Promo '+donnees.relation.parrainage[i].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(donnees.relation.parrainage[i].couleur)+'">'+donnees.relation.parrainage[i].commentaire+'</span></td></tr>';
			}
		}
		
		var tableauRIF = '';
		if (donnees.relation.rif.length == 0) {
			tableauRIF = '<tr><th>RIF</th><td>/</td><td><span class="label label-default">Jamais</span></td></tr> ';
		} else {
			tableauRIF = '<tr><th rowspan='+donnees.relation.rif.length+'>RIF</th><td>'+donnees.relation.rif[0].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(donnees.relation.rif[0].couleur)+'">'+donnees.relation.rif[0].commentaire+'</span></td></tr>';
			for (var i = 1; i < donnees.relation.rif.length; i++) {
				tableauRIF += '<tr><td>'+donnees.relation.rif[i].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(donnees.relation.rif[i].couleur)+'">'+donnees.relation.rif[i].commentaire+'</span></td></tr>';
			}
		}	

		var tableauStages = '';
		if (donnees.relation.stages.length == 0) {
			tableauStages = '<tr><th>Stages</th><td>/</td><td><span class="label label-default">Jamais</span></td></tr> ';
		} else {
			tableauStages = '<tr><th rowspan='+donnees.relation.stages.length+'>Stages</th><td>'+donnees.relation.stages[0].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(1)+'">'+donnees.relation.stages[0].nbSujets+' sujets</span></td></tr>';
			for (var i = 1; i < donnees.relation.stages.length; i++) {
				tableauStages += '<tr><td>'+donnees.relation.stages[i].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(1)+'">'+donnees.relation.stages[i].nbSujets+' sujets</span></td></tr>';
			}
		}

		var tableauEntretiens = '';
		if (donnees.relation.entretiens.length == 0) {
			tableauEntretiens = '<tr><th>Entretien</th><td>/</td><td><span class="label label-default">Jamais</span></td></tr> ';
		} else {
			tableauEntretiens = '<tr><th rowspan='+donnees.relation.entretiens.length+'>Entretiens</th><td>'+donnees.relation.entretiens[0].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(1)+'">'+donnees.relation.entretiens[0].nbSessions+' sessions</span></td></tr>';
			for (var i = 1; i < donnees.relation.entretiens.length; i++) {
				tableauEntretiens += '<tr><td>'+donnees.relation.entretiens[i].annee+'</td><td><span class="label label-'+Annuaire.traduireCouleur(1)+'">'+donnees.relation.entretiens[i].nbSessions+' sessions</span></td></tr>';
			}
		}

		var tableauCommentaires = '';
		if (donnees.relation.entretiens.length == 0) {
			tableauCommentaires = 'Aucun commentaire.';
		} else {
			tableauCommentaires = '<table class="table table-stripped">                                                                                              '+
'										<thead>                                                                                                                      '+
'											<tr>                                                                                                                     '+
'												<th class="first"></th>                                                                                              '+
'												<th>Auteur</th>                                                                                                      '+
'												<th class="first">Poste</th>                                                                                         '+
'												<th>Date</th>                                                                                                        '+
'												<th>Commentaires</th>                                                                                                '+
'										</thead>                                                                                                                     '+
'										<tbody>';

			for (var i in donnees.commentaires) {
				tableauCommentaires += '<tr>                                                                                                                         '+
'												<td>'+Annuaire.traduireCategorieCommentaire(donnees.commentaires[i].categorie)+'</td>                                '+
'												<td>'+donnees.commentaires[i].prenom +' '+donnees.commentaires[i].nom+'</td>                                         '+
'												<td><small>'+donnees.commentaires[i].poste +'</small></td>                                                           '+
'												<td>'+(new Date(donnees.commentaires[i].date)).toDateString() +'</td>                                                                           '+
'												<td>'+donnees.commentaires[i].commentaire +'</td>                                                                    '+
'											</tr>';
			}

			tableauEntretiens = '</tbody></table>';
		}
		
		// Génération du bloc entier :
		$(".module .hero-unit").html('<h1>'+donnees.description.nom+' <small>'+donnees.description.secteur+'</small></h1>'+
'							<p>'+donnees.description.description+'</p>                                                                                    '+
'							                                                                                                                                         '+
'							<div class="accordion" id="accordion2">                                                                                                  '+
'								<div class="accordion-group">                                                                                                        '+
'								  <div class="accordion-heading">                                                                                                    '+
'								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#contacts">                                       '+
'								  <h2>Contacts</h2>                                                                                                                  '+
'								</a>                                                                                                                                 '+
'							  </div>                                                                                                                                 '+
'							  <div id="contacts" class="accordion-body collapse in">                                                                                 '+
'								<div class="accordion-inner">                                                                                                        '+
'									 <table class="table table-stripped">                                                                                            '+
'										<thead>                                                                                                                      '+
'											<tr>                                                                                                                     '+
'												<th>Nom</th>                                                                                                         '+
'												<th>Prénom</th>                                                                                                      '+
'												<th>Poste</th>                                                                                                       '+
'												<th>Email</th>                                                                                                       '+
'												<th>Tel</th>                                                                                                         '+
'												<th>Rem.</th>                                                                                                        '+
'										</thead>                                                                                                                     '+
'										<tbody>                                                                                                                      '+
'											'+tableauContacts+'                                                                                                      '+
'										</tbody>                                                                                                                     '+
'								</table>                                                                                                                             '+
'								</div>                                                                                                                               '+
'							  </div>                                                                                                                                 '+
'							</div>                                                                                                                                   '+
'							<div class="accordion-group">                                                                                                            '+
'							  <div class="accordion-heading">                                                                                                        '+
'								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#relations">                                      '+
'								  <h2>Relations</h2>                                                                                                                 '+
'								</a>                                                                                                                                 '+
'							  </div>                                                                                                                                 '+
'							  <div id="relations" class="accordion-body collapse">                                                                                   '+
'								<div class="accordion-inner">                                                                                                        '+
'									 <table class="table table-stripped">                                                                                            '+
'										<tbody>                                                                                                                      '+
'											'+tableauParrainage+'                                                                                                    '+
'											'+tableauRIF+'                                                                                                           '+
'											'+tableauStages+'                                                                                                        '+
'											'+tableauEntretiens+'                                                                                                    '+
'										</tbody>                                                                                                                     '+
'								</table>                                                                                                                             '+
'								</div>                                                                                                                               '+
'							  </div>                                                                                                                                 '+
'							</div>                                                                                                                                   '+
'							<div class="accordion-group">                                                                                                            '+
'							  <div class="accordion-heading">                                                                                                        '+
'								<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#remarques">                                      '+
'								  <h2>Remarques</h2>                                                                                                                 '+
'								</a>                                                                                                                                 '+
'							  </div>                                                                                                                                 '+
'							  <div id="remarques" class="accordion-body collapse">                                                                                   '+
'								<div class="accordion-inner">                                                                                                        '+
'									'+tableauCommentaires+'                                                                                                          '+
'								</div>                                                                                                                               '+
'							  </div>                                                                                                                                 '+
'							</div>                                                                                                                                   '+
'						  </div>                                                                                                                                     ');
	};