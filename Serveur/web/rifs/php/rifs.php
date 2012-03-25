<div class=row-fluid>
	<div class="span12">
		<div id="presentationRif">
			<h1>Présentation</h1>
			<p>
				Les "Rencontres IF" (ou "RIFs") sont une manifestation propre au Département Informatique de l'INSA de Lyon. Elles permettent, le temps d'une journée, d'élaborer un lien privilégié entre les entreprises et les étudiants.<br />Fort de son succès depuis plus de <strong>10 ans</strong> cet évènement se renouvelle chaque année en recherchant toujours plus de qualité dans sa prestation.
			</p>
			<div id="conceptRif">
				<h2>Concept</h2>
				<p>
					Une <strong>vingtaine d'entreprises</strong> est invitée au sein du département afin de venir à la <strong>rencontre des étudiants</strong>. Celles-ci y sont accueillies par une équipe composée exclusivement d'étudiants, et la journée, banalisée pour l'évènement, permettra à chacun de venir se renseigner, poser des questions, prendre des contacts, ...<br /> En fin de matinée, l'équipe organisatrice et les intervenants de chaque entreprise seront conviés à un déjeuner de manière à ponctuer l'évènement par une pause conviviale.
				</p>
				<p>
				Les Rencontres IF sont aussi une occasion idéale pour les étudiants de <strong>trouver un stage ou un éventuel futur premier emploi</strong>. Il est ainsi conseillé aux entreprises de préparer, si elles le désirent, plusieurs propositions adaptées aux différentes promotions.
				</p>
				<p>
				Encore une fois, l'objectif de ce type d'évènement est évidemment d'entretenir de <strong>bonnes relations entre les étudiants et leur futur employeur.</strong>
				</p>
			</div>
		</div>
		<div id="infoRif">
			<h1>Informations</h1>
			<div class="Date">
				<h2>Date</h2>
				<p>
					La date précise des prochaines Rencontres IF n'est pas encore décidée, mais elle a généralement lieu au mois de Janvier.<br />
				</p>
			</div>

			<div class="tarif">
				<h2>Tarifs</h2>
				<div class="row-fluid">
					<div class="span6"> 
						<ul>
							<li>Entreprise parrainant l'une des trois promotions en cours : </li>
							<li>Entreprise payant la taxe d'apprentissage à l'INSA : </li>
							<li>Autres entreprises : </li>
						</ul>
					</div>
					<div class="span4">
						<ul class="unstyled">
							<li>Invitée</li>
							<li>900€</li>
							<li>1150€</li>
						</ul>
					</div>
				</div>
				<div class="comment">
					Les tarifs sont succeptibles d'être <u>modifiés</u> jusqu'au mois de Septembre.
				</div>
			</div>
			<p>
				Pour plus d'informations concernant cet évènement, veuillez nous contacter à l'adresse suivante :<a href="mailto:aedi.entreprises@gmail.com" title="Equipe Entreprise"> aedi.entreprises@gmail.com</a>.
			</p>
		</div>
	</div>
	<div id="inscriptionRif">
		<h1>Inscription</h1>
		<p>
			Pour vous inscrire aux Rencontres IF du prochain mois de Janvier veuillez remplir le formulaire ci-dessous. Suite à celui-ci l'équipe Entreprise de l'AEDI se chargera de vous répondre et de vous informer sur la suite des démarches.
		</p>

		<!-- Formulaire pour l'inscription au RIFs -->
		<form class="form-horizontal" onsubmit="return valider()" name="formInscription" method="post">
			<legend><h1>Formulaire d'inscription</h1></legend>
			<fieldset id="infoEntreprise">
				<div class="control-group" id="control_nomEntreprise">
					<label class="control-label" for="nomEntreprise">Nom de l'entreprise</label>
					<div class="controls">
						<input class="input-medium" type="text" id="nomEntreprise" placeholder="Nom de l'entreprise..." />
					</div>
				</div>
				<div class="control-group row-fluid" id="control_nomResponsable">
					<div class="span4">
						<label class="control-label" for="nomResponsable">Nom du responsable</label>
						<div class="controls">
							<input class="input-large" type="text" id="nomResponsable" placeholder="Nom du responsable..." required />
						</div>
					</div>

					<div class="span6">
						<label class="control-label" for="telephone">Tél</label>
						<div class="controls">
							<input class="input-medium" type="tel" id="telephone" placeholder="N° de tél..." />
						</div>
				</div>
			</fieldset>
			<fieldset id="infoGenerale">
				<legend>Informations Générales</legend>
				<div class=row-fluid>
					<div class="control-group span4" id="control_nbPersonne">
						<label class="control-label" for="nbPersonne">Nombre de personnes présentes</label>
						<div class="controls">
							<input class="input-medium" type="number" min="1" max="8" value="1" step="1" id="nbPersonne" placeholder="Nom de l'entreprise..." />
						</div>
					</div>
					<div class="control-group span8" id="control_intervenant">
						<label class="control-label">Nom - Prénom</label>
						<div class="controls">
							<input class="input-medium span" type="text" id="nom_1" placeholder="Nom" />
							<input class="input-medium span" type="text" id="prenom_1" placeholder="Prénom" />
						</div>
					</div>
				</div>
				<div class="control-group" id="control_momentPresence">
					<label class="control-label">Présence</label>
					<div class="controls">
						<label class="radio inline">
							<input id="momentPresence_matin" type="radio" value="matin" name="momentPresence" />
							Matin
						</label>
						<label class="radio inline">
							<input id="momentPresence_apresMidi" type="radio" value="apresMidi" name="momentPresence" />
							Après-Midi
						</label>
						<label class="radio inline">
							<input id="momentPresence_journee" type="radio" checked="" value="journee" name="momentPresence" />
							Journée entière
						</label>
					</div>
				</div>
				<div class="control-group" id="control_momentPresence">
					<label class="control-label">Présence</label>
					<div class="controls">
						<label class="radio inline">
							<input id="restaurant_non" type="radio" checked="" value="non" name="restaurant" />
							Non
						</label>
						<label class="radio inline">
							<input id="restaurant_oui" type="radio" value="oui" name="restaurant" />
							Oui - Nombre de personnes :
							<input class="input-medium" type="number" min="1" max="8" value="1" step="1" id="nbPers_restaurant" disabled />
						</label>
					</div>
				</div>
				<div class="control-group" id="control_taxeApprentissage">
					<label class="control-label">Paiement de la taxe d'apprentissage à l'INSA</label>
					<div class="controls">
						<label class="radio inline">
							<input id="TA_non" type="radio" checked="" value="non" name="TA" />
							Non
						</label>
						<label class="radio inline">
							<input id="TA_oui" type="radio" value="oui" name="TA" />
							Oui
						</label>
					</div>
				</div>
			</fieldset>
			<fieldset id="infoTechnique">
				<legend>Informations Techniques</legend>
				<div class="control-group row-fluid">
					<div class="span8">
						<label class="control-label" for="infoMatosTechnique">Description du matériel apporté</label>
						<div class="controls">
							<textarea class="input-xxlarge" rows="3" id="infoMatosTechnique">
							</textarea>
						</div>
					</div>
					<div class="span4">
						<label class="control-label" for="infoNbPrise">Nombre de prises électriques nécessaires</label>
						<div class="controls">
							<input class="input-medium" type="number" min="0" max="20" value="0" step="1" id="infoNbPrise" />
						</div>
					</div>
				</div>
			</fieldset>
			<fieldset id="infoComplementaire">
				<legend>Informations Complémentaires</legend>
				<div class="control-group" id="control_attente">
					<label>Quelles sont vos attentes concernant votre participation aux rencontres IF?</label>
					<div class="controls">
						<textarea class="input-xxlarge" rows="3" id="attente">
						</textarea>
					</div>
				</div>
				<div class="control-group" id="control_descEntreprise">
					<label>Description de votre entreprise <em>(description qui apparaîtra sur la broche de l'évènement)</em></label>
					<div class="controls">
						<textarea class="input-xxlarge" rows="3" id="description">
						</textarea>
					</div>
				</div>
				<div class="control-group" id="control_autre">
					<label>Autres (commentaires, remarques, ...)</label>
					<div class="controls">
						<textarea class="input-xxlarge" rows="3" id="autre">
						</textarea>
					</div>
				</div>
			</fieldset>
			<button class="btn btn-large offset9">Envoyer</button>
		</form>
	</div>
</div>
