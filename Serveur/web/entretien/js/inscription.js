function valider(){
	  var valide = true;
	  //On test la valeur des champs du formulaire
	  
	  // NOM CONTACT
	  var div = document.getElementById("control_nom");
	  if( $("#nom_contact").val() != ""){
		div.className ="control-group success";
	  }else{
		  div.className ="control-group error";
		  valide = false;
	  }
	  // PRENOM CONTACT
	  var div = document.getElementById("control_prenom");
	  if( $("#prenom_contact").val() != ""){
		div.className ="control-group success";
	  }else{
		div.className ="control-group error";
		valide = false;
	  }
	  
	  // MAIL CONTACT
	  var div = document.getElementById("control_mail");
	  if( $("#mail_contact").val() != "" && verifMail( $("#mail_contact").val() ) ){
		div.className ="control-group success";
	  }else{
		div.className ="control-group error";
		valide = false;
	  }
	  // ENTREPRISE
	  var div = document.getElementById("control_nomEntreprise");
	  if( $("#nomEntreprise").val() != ""){
		div.className ="control-group success";
	  }else{
		div.className ="control-group error";
		valide = false;
	  }
	  // VILLE
	  var div = document.getElementById("control_villeEntreprise");
	  if( $("#villeEntreprise").val() != ""){
		div.className ="control-group success";
	  }else{
		div.className ="control-group error";
		valide = false;
	  }
	  //DATE
	  var div = document.getElementById("control_date");
	  if( $("#date").val() != ""){
		div.className ="control-group success";
	  }else{
		div.className ="control-group error";
		valide = false;
	  }
	  //HEURE DEBUT
	  var div = document.getElementById("control_heureDebut");
	  if( $("#heureDebut").val() != "choix"){
		div.className ="control-group success";
	  }else{
		div.className ="control-group error";
		valide = false;
	  }
	  //HEURE FIN
	  var div = document.getElementById("control_heureFin");
	  if( $("#heureFin").val() != "choix"){
		div.className ="control-group success";
	  }else{
		div.className ="control-group error";
		valide = false;
	  }
	  
	  var retour = (valide == true ? true : false);
	  return retour;
}

	// Permet de verifier l'email
	function verifMail(champ)
	{
	   var regex = /^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$/;
	   if(!regex.test(champ) )
	   {
		  return false;
	   }
	   else
	   {
		  return true;
	   }
	}

	
/*---------------------------------------------------------------------------------
					PARTIE AJAX
---------------------------------------------------------------------------------*/
// Requet inscription entreprise
$('document').ready(function() {
	$("#formInscription").submit( function() {
	// Si les controles sont bons on post
	if( valider() != false){
		var obj = {
			nom_entreprise: $('#nomEntreprise').val(),
			ville_entreprise: $('#villeEntreprise').val(),
			nom_contact: $('#nom_contact').val(),
			prenom_contact: $('#prenom_contact').val(),
			mail_contact: $('#mail_contact').val(),
			tel_contact: $('#tel_contact').val(),
			heureDebut: $('#heureDebut').val()+$('#minuteDebut').val(),
			heureFin: $('#heureFin').val()+$('#minuteFin').val(),
			date: $('#date').val(),
			//tableau intervenant
			//table_intervenant: $('#table_intervenant')
		};
		
		//TODO: changer url par : /entretien/ajax/inscription_post.cible.php
		$.post('/S.I/Serveur/web/entretien/ajax/inscription_post.cible.php', obj, function() {
				alert('succes');
				//TODO: gèrer le retour de l'insert
			});
	}
		return false;
	});
});

// Recupere la liste des contact associes a une entreprise
$('document').ready(function() {
	$("#nomContact").focus( function() {
		var obj = {
			nom_entreprise: $('#entreprise').val()
		};
		//TODO: changer url par : /entretien/ajax/inscription_etudiant.cible.php
		$.post('/S.I/Serveur/web/entretien/ajax/liste_contacts.cible.php', obj, function(liste_contacts) {
				var jsonContact = eval('(' + liste_contacts + ')');
				majListeContacts(jsonContact);
				$('.typeahead').typeahead();
			});
	});
});

/*
 * Methode qui permet de maj la liste servant à l'autocompletion des contacs
*/
function majListeContacts(jsonContact){
	jsonContact
	var liste_contacts = "[";
	for (var /* int */ i in jsonContact.contact){
		liste_contacts += "\""+ jsonContact.contact[i].prenom +" "+jsonContact.contact[i].nom+"\"";
		if( jsonContact.contact[i++] == "undefined" ){
			liste_contacts += "\",";
		}
	}
	liste_contacts += "]";
	$("#nomContact").attr("data-source",liste_contacts);
} 


// Requete inscription etudiant a un entretien
$('document').ready(function() {
	$("#formReservation").submit( function() {
		var obj = {
			id_creneau: $('#id_creneau').val()
		};
		//TODO: changer url par : /entretien/ajax/inscription_etudiant.cible.php
		$.post('/S.I/Serveur/web/entretien/ajax/inscription_etudiant.cible.php', obj, function() {
			// Ajouter message ici
		});
	});
});
 
 
// Requete recuperation simulations d'un jour
$('document').ready(function() {
	$("#formChoixDate").submit( function() {
		var obj = {
			date: $('#date_creneaux').val()
		};
		
		//TODO: changer url par : /entretien/ajax/inscription_etudiant.cible.php
		$.post('/S.I/Serveur/web/entretien/ajax/liste_creneaux.cible.php', obj, function(creneau_list) {
				var jsonCreneau = eval('(' + creneau_list + ')');
				afficherCreneaux(jsonCreneau);
				$('.reservation').click(function(){
					$("#id_creneau").val($(this).attr("id_creneau"));
				});
			});
		return false;
	});
});



function afficherCreneaux(jsonCreneau){
	
	var /* string */ text = "";
	for (var /* int */ i in jsonCreneau.creneau){
		var /*string */ nom = jsonCreneau.creneau[i].nom;
		text += "<div class=\"accordion-group\"><div class=\"accordion-heading\"><a class=\"accordion-toggle\" data-toggle=\"collapse\""
		+ "data-parent=\"#accordion_creneau\" href=\"#collapse"+i+"\">"+ jsonCreneau.creneau[i].nom +"</a>"
		+ "</div>"
		+	"<div id=\"#collapse"+i+"\" class=\"accordion-body collapse in\">"
		+	   "<div class=\"accordion-inner\">"
		+		"<table class=\"table table-striped\">"
		+		"<thead><tr><th>Debut</th><th>Fin</th><th>Etat</th><th></th></tr></thead>"
		+		"<tbody><tr>"
		+			"<td>"+jsonCreneau.creneau[i].debut+"</td>"
		+			"<td>"+jsonCreneau.creneau[i].fin+"</td>"
		+			"<td>"+disponible(jsonCreneau.creneau[i].id_etudiant)+"</td>";
		if( disponible(jsonCreneau.creneau[i].id_etudiant) != "Disponible"){
			// On ne met pas de boutton
		}else{
		text +=	"<td><a class=\"reservation btn btn-inverse\" id_creneau="+jsonCreneau.creneau[i].id_creneau+" data-toggle=\"modal\" href=\"#myModal\">S'inscrire</a></td>"
		}
		text +=	  "</tr>"
		+		  "</tbody></table></div></div></div>";
	}
	$('#accordion_creneau').html(text);
}


function disponible(id_etudiant){
	//TODO: changer le test ??????????????????
	if(id_etudiant != "0"){
		return "Reserve";
	}else{
		return "Disponible";
	}
}


/*
 * Partie Administration
*/

$('document').ready(function() {
	$.post('/S.I/Serveur/web/entretien/ajax/liste_entretiens.cible.php', function(entretien_list) {
		var jsonEntretien = eval('(' + entretien_list + ')');
		afficherEntretiens(jsonEntretien);
		$('.validation').click(function(){
			$("#id_entretien").val($(this).attr("id_entretien"));
		});
	});
	return false;
});


// Requete validation d'un entretien
$('document').ready(function() {
	$("#formValiderEntretien").submit( function() {
		var obj = {
			id_entretien: $('#id_entretien').val()
		};
		//TODO: changer url par : /entretien/ajax/inscription_etudiant.cible.php
		$.post('/S.I/Serveur/web/entretien/ajax/valider_entretien.cible.php', obj, function() {
			// Ajouter message ici
		});
	});
});
 

function afficherEntretiens(jsonEntretien){
	
	var /* string */ text = "<thead><tr><th>Jour</th><th>Entreprise</th><th>Etat</th><th></th></tr></thead><tbody>";
	for (var /* int */ i in jsonEntretien.entretien){
		var /*string */ nom = jsonEntretien.entretien[i].nom;
		text += "<tr><td>"+jsonEntretien.entretien[i].date+"</td>"
		+			"<td>"+jsonEntretien.entretien[i].nom+"</td>"
		+			"<td>"+valide(jsonEntretien.entretien[i].etat)+"</td>";
		if( jsonEntretien.entretien[i].etat != 0){
			// On ne met pas de boutton
			text += "<td></td>";
		}else{
			text +=	"<td><a class=\"validation btn btn-success\" id_entretien="+jsonEntretien.entretien[i].id_entretien+" data-toggle=\"modal\" href=\"#myModal\">Valider</a>"
				+ "<a class=\"validation btn btn-danger\" id_entretien="+jsonEntretien.entretien[i].id_entretien+" data-toggle=\"modal\" href=\"#myModal\">Refuser</a></td>";
		}
		text +=	  "</tr>"
	}
	text += "</tbody>";
	$('.table').html(text);
}

// Fonction qui analyse l'etat de l'entretien en fonction de l'etat
function valide(etat){
	if( etat == 0){
		return "En attente";
	}else{
		return "Valide";
	}
}












