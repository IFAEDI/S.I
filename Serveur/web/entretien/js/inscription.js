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
	

function valider_intervenant(){
	  var valide = true;
	  //On test la valeur des champs du formulaire
	  
	  // NOM INTERVENANT
	  var div = document.getElementById("control_nom_intervenant");
	  if( $("#nom_intervenant").val() != ""){
		div.className ="control-group success";
	  }else{
		  div.className ="control-group error";
		  valide = false;
	  }
	  // PRENOM INTERVENANT
	  var div = document.getElementById("control_prenom_intervenant");
	  if( $("#prenom_intervenant").val() != ""){
		div.className ="control-group success";
	  }else{
		div.className ="control-group error";
		valide = false;
	  }
	  
	  // MAIL INTERVENANT
	  var div = document.getElementById("control_mail_intervenant");
	  if( $("#mail_intervenant").val() != "" && verifMail( $("#mail_intervenant").val() ) ){
		div.className ="control-group success";
	  }else{
		div.className ="control-group error";
		valide = false;
	  }
	  
	  var retour = (valide == true ? true : false);
	  return retour;
}
	
	
	
/*---------------------------------------------------------------------------------
					PARTIE AJAX
---------------------------------------------------------------------------------*/
	$("#formInscription").submit( function() {
	// Si les controles sont bons on post
	if( valider() != false){
		$.post("entretien/ajax/inscription_post.cible.php",
		{ prenom : "prenom"},
		function success(retour){
				if(retour == "1" ){
					alert('coucou');
				}
			});
	}else{
		return false;
	}
	});

	
	$("#form_intervenant").submit( function() {
		  var nom = $('#nom_intervenant').val();
		  var prenom = $('#prenom_intervenant').val();
		  var email = $('#mail_intervenant').val();
		  if( valider_intervenant() == false ) return false;
		  //On ajout le participant au tableau
		  $("#tableParticipant").append('<tr><td>'+nom+'</td><td>'+prenom+'</td><td>'+email+'</td></tr>');
		  return false;
	});
		
 
	