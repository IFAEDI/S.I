function valider(){
	  var valide = true;
	  //On test la valeur des champs du formulaire
	  
	  // NOM
	  if(document.formInscription.nom.value != ""){
		var div = document.getElementById("control_nom");
		div.className ="control-group success";
	  }else{
		  var div = document.getElementById("control_nom");
		  div.className ="control-group error";
		  valide = false;
	  }
	  // PRENOM
	  if(document.formInscription.prenom.value != ""){
		var div = document.getElementById("control_prenom");
		div.className ="control-group success";
	  }else{
		var div = document.getElementById("control_prenom");
		div.className ="control-group error";
		valide = false;
	  }
	  // EMAIL
	  if(document.formInscription.email.value != ""
	  && verifMail(document.formInscription.email) ){
		var div = document.getElementById("control_email");
		div.className ="control-group success";
	  }else{
		var div = document.getElementById("control_email");
		div.className ="control-group error";
		valide = false;
	  }
	  // ENTREPRISE
	  if(document.formInscription.nomEntreprise.value != ""){
		var div = document.getElementById("control_nomEntreprise");
		div.className ="control-group success";
	  }else{
		var div = document.getElementById("control_nomEntreprise");
		div.className ="control-group error";
		valide = false;
	  }
	  //DATE
	  if(document.formInscription.date.value != ""){
		var div = document.getElementById("control_date");
		div.className ="control-group success";
	  }else{
		var div = document.getElementById("control_date");
		div.className ="control-group error";
		valide = false;
	  }
	  //HEURE DEBUT
	  if(document.formInscription.heureDebut.value != "choix"){
		var div = document.getElementById("control_heureDebut");
		div.className ="control-group success";
	  }else{
		var div = document.getElementById("control_heureDebut");
		div.className ="control-group error";
		valide = false;
	  }
	  //HEURE FIN
	  if(document.formInscription.heureFin.value != "choix"){
		var div = document.getElementById("control_heureFin");
		div.className ="control-group success";
	  }else{
		var div = document.getElementById("control_heureFin");
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
	   if(!regex.test(champ.value))
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

	$("#formInscription").submit( function() {	// à la soumission du formulaire						 
		$.ajax({ // fonction permettant de faire de l'ajax
		   type: "POST", // methode de transmission des données au fichier php
		   url: "entretien/ajax/inscription_post.php", // url du fichier php
		   data: {prenom : prenom}, // données à transmettre
		   success: function(msg){ // si l'appel a bien fonctionné
				if(msg=="1") // si la connexion en php a fonctionnée
				{
					alert("coucou");
					// on désactive l'affichage du formulaire et on affiche un message de bienvenue à la place
				}
				else // si la connexion en php n'a pas fonctionnée
				{
					alert("plop");
					// on affiche un message d'erreur dans le span prévu à cet effet
				}
		   }
		});	// permet de rester sur la même page à la soumission du formulaire
	});
	
	