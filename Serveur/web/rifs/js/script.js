/*---------------------------------------------------------------------------------
					PARTIE JAVASCRIPT
---------------------------------------------------------------------------------*/		
function changementTypeEntreprise(champ){
	if (champ.options[champ.selectedIndex].value == "autre" && $("#autreTypeEntreprise").css("display") == "none")
		$("#autreTypeEntreprise").show("slow");
	else
		$("#autreTypeEntreprise").hide("slow");
}

function valider(){
	  var valide = true;
	  //On test la valeur des champs du formulaire

	  // NOM DE L ENTREPRISE
	  if(document.formInscription.nomEntreprise.value != ""){
		var div = document.getElementById("control_nomEntreprise");
		div.className ="control-group success";
	  }else{
		  var div = document.getElementById("control_nomEntreprise");
		  div.className ="control-group error";
		  valide = false;
	  }

	// NOM DU RESP.
	  if(document.formInscription.nomResponsable.value != ""){
		var div = document.getElementById("control_nomResponsable");
		div.className ="control-group success";
	  }else{
		  var div = document.getElementById("control_nomResponsable");
		  div.className ="control-group error";
		  valide = false;
	  }
	  
	  // PRENOM DU RESP.
	  if(document.formInscription.prenomResponsable.value != ""){
		var div = document.getElementById("control_prenomResponsable");
		div.className ="control-group success";
	  }else{
		var div = document.getElementById("control_prenomResponsable");
		div.className ="control-group error";
		valide = false;
	  }
	  
	  // TEL DU RESP.
	  if(document.formInscription.telephone.value != ""){
		var div = document.getElementById("control_tel");
		div.className ="control-group success";
	  }else{
		var div = document.getElementById("control_tel");
		div.className ="control-group error";
		valide = false;
	  }
	  
	  // EMAIL
	  if(document.formInscription.mail.value != ""
	  && verifMail(document.formInscription.mail) ){
		var div = document.getElementById("control_mail");
		div.className ="control-group success";
	  }else{
		var div = document.getElementById("control_mail");
		div.className ="control-group error";
		valide = false;
	  }
	  
	  var retour = (valide == true ? true : false);	  
	  return retour;
	}

/*---------------------------------------------------------------------------------
					PARTIE AJAX
---------------------------------------------------------------------------------*/
function soumettreFormulaire(){
	var result = valider();
	alert(result);
	if (result == true){
		var nomEntreprise = document.formInscription.nomEntreprise.value;
		$.ajax({
			type :"POST",
			url :"generation_formulaire.cible.php",
			data : ({nomEntreprise : nomEntreprise}),
			success: function(msg){ // si l'appel a bien fonctionné
				if(msg==1) // si la connexion en php a fonctionnée
				{
					$("div.module").html("Succès.");
					// on désactive l'affichage du formulaire et on affiche un message de bienvenue à la place
				}
				else // si la connexion en php n'a pas fonctionnée
				{
					alert("Erreur dans la génération du pdf")
					// on affiche un message d'erreur dans le span prévu à cet effet
				}
		   }
		});
		return false; // permet de rester sur la même page à la soumission du formulaire
	}else
	{
		alert("MERDE...");
		return false;
	}
	
}
