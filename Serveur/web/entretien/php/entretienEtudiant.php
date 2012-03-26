<?php
/*if (!Utilisateur_connecter('entreprise')) {
    inclure_fichier('', '401', 'php');
    die;
}
*/
?>
			
<div class="accordion" id="accordion2">
	<div class="accordion-group">
	  <div class="accordion-heading">
		<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
		  Altran
   	    </a>
      </div>
        <div id="collapseOne" class="accordion-body collapse in">
           <div class="accordion-inner">
            <table class="table table-striped">
			<thead>
			  <tr>
				<th>Debut</th>
				<th>Fin</th>	
				<th>Etat</th>
				<th></th>
			  </tr>
			</thead>
			<tbody>
			  <tr>
				<td>14h00</td>
				<td>14h45</td>
				<td>Disponible</td>
				<td>
				<a class="reservation btn btn-inverse" entreprise="Altran" heureDeb="14h00" heureFin="14h45" data-toggle="modal" href="#myModal">
				S'inscrire
				</a>
				</td>
			  </tr>
			  <tr>
				<td>14h45</td>
				<td>15h30</td>
				<td>Reserve</td>
				<td><a class="reservation btn btn-inverse" data-toggle="modal" href="#myModal">
				S'inscrire
				</a></td>
			  </tr>
			  <tr>
				<td>15h30</td>
				<td>16h15</td>
				<td>Disponible</td>
				<td><a class="reservation btn btn-inverse" data-toggle="modal" href="#myModal">
				S'inscrire
				</a></td>
			  </tr>
			</tbody>
			</table>
        </div>
	  </div>
	</div>
	<div class="accordion-group">
	  <div class="accordion-heading">
		<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
		  Thales
   	    </a>
      </div>
        <div id="collapseTwo" class="accordion-body collapse in">
           <div class="accordion-inner">
            <table class="table table-striped">
			<thead>
			  <tr>
				<th>Debut</th>
				<th>Fin</th>
				<th>Etat</th>
				<th></th>
			  </tr>
			</thead>
			<tbody>
			  <tr>
				<td>15h30</td>
				<td>16h15</td>
				<td>Disponible</td>
				<td><a class="reservation btn btn-inverse" data-toggle="modal" href="#myModal">
				S'inscrire
				</a></td>
			  </tr>
			  <tr>
				<td>15h30</td>
				<td>16h15</td>
				<td>Reserve</td>
				<td><a class="reservation btn btn-inverse" data-toggle="modal" href="#myModal">
				S'inscrire
				</a></td>
			  </tr>
			  <tr>
				<td>15h30</td>
				<td>16h15</td>
				<td>Disponible</td>
				<td><a class="reservation btn btn-inverse" data-toggle="modal" href="#myModal">
				S'inscrire
				</a></td>
			  </tr>
			</tbody>
			</table>
        </div>
	  </div>
	</div>
	<div class="accordion-group">
	  <div class="accordion-heading">
		<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
		  Areva
   	    </a>
      </div>
        <div id="collapseThree" class="accordion-body collapse in">
           <div class="accordion-inner">
            <table class="table table-striped">
			<thead>
			  <tr>
				<th>Debut</th>
				<th>Fin</th>
				<th>Etat</th>
				<th></th>
			  </tr>
			</thead>
			<tbody>
			  <tr>
				<td>15h30</td>
				<td>16h15</td>
				<td>Disponible</td>
				<td><a class="reservation btn btn-inverse" data-toggle="modal" href="#myModal">
				S'inscrire
				</a></td>
			  </tr>
			  <tr>
				<td>15h30</td>
				<td>16h15</td>
				<td>Reserve</td>
				<td><a class="reservation btn btn-inverse" data-toggle="modal" href="#myModal">
				S'inscrire
				</a></td>
			  </tr>
			  <tr>
				<td>15h30</td>
				<td>16h15</td>
				<td>Disponible</td>
				<td><a class="reservation btn btn-inverse" data-toggle="modal" href="#myModal">
				S'inscrire
				</a></td>
			  </tr>
			</tbody>
			</table>
        </div>
	  </div>
</div>


<div class="modal hide fade" id="myModal">
	<div class="modal-header">
	<a class="close" data-dismiss="modal">×</a>
	<h3>Entretien</h3>
	</div>
	<div class="modal-body">
	   <form class="form-horizontal" name="formReservation" method="post" >
			<input type="hidden" name="heureDeb"/>
			<input type="hidden" name="heureFin"/>
			<input type="hidden" name="entreprise"/>
			<p>Etes-vous sur de vouloir vous inscrire a cette session ?</p>
			<div class="modal-footer">
			<input type="submit" class="btn btn-primary" value="Valider"/>
			<a href="#" class="btn" data-dismiss="modal">Annuler</a>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript" charset="utf-8">

// Permet de retrouver le créneau concerne
$(".reservation").click(function() {
		document.formReservation.heureDeb.value = $(this).attr("heureDeb");
		document.formReservation.heureFin.value = $(this).attr("heureFin");
		document.formReservation.entreprise.value = $(this).attr("entreprise");
});

</script>


<?php

?>