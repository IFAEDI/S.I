/*---------------------------------------------------------------------------------
PARTIE JAVASCRIPT
---------------------------------------------------------------------------------*/
function changementTypeEntreprise(champ){
	if (champ.options[champ.selectedIndex].value == "autre" && $("#autreTypeEntreprise").css("display") == "none")
		$("#autreTypeEntreprise").show("slow");
	else
		$("#autreTypeEntreprise").hide("slow");
}

function ajouterIntervenant(){
	if ($('.nomPrenomResponsable').length < 8){
		$('#control_participants').append($('.nomPrenomIntervenant').last().clone());
		$('.nomPrenomIntervenant').last().children('.nomIntervenant').val('');
		$('.nomPrenomIntervenant').last().children('.prenomIntervenant').val('');
		if ($('.nomPrenomResponsable').length == 8){
		// TODO : Mettre un label pour indiquer que l'on ne peut plus ajouter d'intervenants
		}
	}
	return false;
}

function enleverIntervenant(object){
	object.parentElement.remove();
	return false;
}

	function valider(){
	var valide = true;
	//On test la valeur des champs du formulaire

	// NOM DE L ENTREPRISE
	if(document.formInscription.nomEntreprise.value == ""){
		var div = document.getElementById("control_nomEntreprise");
		div.className ="control-group error";
		valide = false;
	}

	// NOM DU RESP.
	if(document.formInscription.nomResponsable.value == ""){
		var div = document.getElementById("control_nomResponsable");
		div.className ="control-group error";
		valide = false;
	}

	// PRENOM DU RESP.
	if(document.formInscription.prenomResponsable.value == ""){
		var div = document.getElementById("control_nomResponsable");
		div.className ="control-group error";
		valide = false;
	}

	// TEL DU RESP.
	if(document.formInscription.telephone.value == ""){
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

	// TODO : vérifier les intervenants

	// DESCRIPTIF DE L ENTREPRISE
	if(document.formInscription.descriptionEntreprise.value != ""){
		var div = document.getElementById("control_descEntreprise");
		div.className ="control-group success";
	}else{
		var div = document.getElementById("control_descEntreprise");
		div.className ="control-group error";
		valide = false;
	}

	var retour = (valide == true ? true : false);
	return retour;
}


// Permet de verifier l'email
function verifMail(champ)
{
	var regex = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(!regex.test(champ.value) )
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
function soumettreFormulaire(){
	var result = valider();
	if (result == true){
		var nomIntervenants = new Array();
		$('.nomIntervenant').each(function(index) {
			if (this.value != '')
				nomIntervenants[index] = this.value;
		});
		var prenomIntervenants = new Array();
		$('.prenomIntervenant').each(function(index) {
			if (this.value != '')
				prenomIntervenants[index] = this.value;
		});
		var typeEntreprise = (document.formInscription.typeEntreprise.value != "autre")
			? document.formInscription.typeEntreprise.value:document.formInscription.typeEntrepriseAutre.value;

		for (var i=0; i < document.formInscription.momentPresence.length; i++)
		{
			if (document.formInscription.momentPresence[i].checked) {var momentPresenceVal = document.formInscription.momentPresence[i].value;}
		}

		var nbIntervRestaurant = 0;
		for (var i=0; i < document.formInscription.restaurant.length; i++)
		{
			if (document.formInscription.restaurant[i].checked)
			{
				var restaurantVal = document.formInscription.restaurant[i].value;
				if (restaurantVal == 'oui')
					nbIntervRestaurant = document.formInscription.nbPers_restaurant.value;
			}
		}

		for (var i=0; i < document.formInscription.TA.length; i++)
		{
			if (document.formInscription.TA[i].checked)
				{var TA = document.formInscription.TA[i].value;}
		}

		$.post("rifs/ajax/inscription_post.cible.php",
		{
			nomEntreprise : document.formInscription.nomEntreprise.value,
			nomResponsable : document.formInscription.nomResponsable.value,
			prenomResponsable : document.formInscription.prenomResponsable.value,
			telephone : document.formInscription.telephone.value,
			mail : document.formInscription.mail.value,
			typeEntreprise: typeEntreprise,
			nomIntervenants : nomIntervenants,
			prenomIntervenants : prenomIntervenants,
			momentPresence : momentPresenceVal,
			restaurant : restaurantVal,
			nbPers_restaurant : nbIntervRestaurant,
			TA : TA,
			infoMatosTechnique : document.formInscription.infoMatosTechnique.value,
			infoNbPrise : document.formInscription.infoNbPrise.value,
			attente : document.formInscription.attente.value,
			descriptionEntreprise : document.formInscription.descriptionEntreprise.value,
			autre : document.formInscription.autre.value,
		},
		function success(retour){
			$(".module").html(retour);
		});
	}
	window.scrollTo(0,0);
	return false;
}