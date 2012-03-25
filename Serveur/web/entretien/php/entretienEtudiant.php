<?php
if (!Utilisateur_connecter('entreprise')) {
    inclure_fichier('', '401', 'php');
    die;
}

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
				<th>Horaire</th>
				<th>Etat</th>
				<th></th>
			  </tr>
			</thead>
			<tbody>
			  <tr>
				<td>13h30 - 14h</td>
				<td>Disponible</td>
				<td>
				<a class="actuator btn btn-inverse" data-toggle="modal" href="#myModal">
				S'inscrire
				</a>
				</td>
			  </tr>
			  <tr>
				<td>14h30 - 15h</td>
				<td>Reserve</td>
				<td><a class="actuator btn btn-inverse" data-toggle="modal" href="#myModal">
				S'inscrire
				</a></td>
			  </tr>
			  <tr>
				<td>15h30 - 16h</td>
				<td>Disponible</td>
				<td><a class="actuator btn btn-inverse" data-toggle="modal" href="#myModal">
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
				<th>Horaire</th>
				<th>Etat</th>
				<th></th>
			  </tr>
			</thead>
			<tbody>
			  <tr>
				<td>13h30 - 14h</td>
				<td>Disponible</td>
				<td><a class="actuator btn btn-inverse" data-toggle="modal" href="#myModal">
				S'inscrire
				</a></td>
			  </tr>
			  <tr>
				<td>14h30 - 15h</td>
				<td>Reserve</td>
				<td><a class="actuator btn btn-inverse" data-toggle="modal" href="#myModal">
				S'inscrire
				</a></td>
			  </tr>
			  <tr>
				<td>15h30 - 16h</td>
				<td>Disponible</td>
				<td><a class="actuator btn btn-inverse" data-toggle="modal" href="#myModal">
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
				<th>Horaire</th>
				<th>Etat</th>
				<th></th>
			  </tr>
			</thead>
			<tbody>
			  <tr>
				<td>13h30 - 14h</td>
				<td>Disponible</td>
				<td><a class="actuator btn btn-inverse" data-toggle="modal" href="#myModal">
				S'inscrire
				</a></td>
			  </tr>
			  <tr>
				<td>14h30 - 15h</td>
				<td>Reserve</td>
				<td><a class="actuator btn btn-inverse" data-toggle="modal" href="#myModal">
				S'inscrire
				</a></td>
			  </tr>
			  <tr>
				<td>15h30 - 16h</td>
				<td>Disponible</td>
				<td><a class="actuator btn btn-inverse" data-toggle="modal" href="#myModal">
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
	   <form class="form-horizontal" method="post" action="reserverCreneau">
			<p>Etes-vous sur de vouloir vous inscrire a cette session ?</p>
			<div class="modal-footer">
			<input type="submit" class="btn btn-primary" value="Valider"/>
			<a href="#" class="btn" data-dismiss="modal">Annuler</a>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript" charset="utf-8">

</script>


<?php

?>