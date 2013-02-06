<?php
	
	// TODO : Vérifier taille du logo
	
	require('../js/fpdf/fpdf.php');	

	class PDF extends FPDF
	{
		// En-tête
		function Header()
		{
			// Logo
			$this->Image('../../commun/img/logo_aedi.png',10,8,0,33);

			// Police Arial 12
			$this->SetFont('Arial','U',11);
			// Titre
			$this->Cell(0,0,'Association des Etudiants du Departement Informatique',0,0,'C');
			$this->Ln(10);
			// Police Arial gras 30
			$this->SetFont('Arial','B',30);			
			// Titre
			$this->Cell(0,0,'Rencontres IF',0,0,'C');
			// Saut de ligne
			$this->Ln(10);
			// Police Arial gras 24
			$this->SetFont('Arial','B',24);
			// Sous-titre
			$this->Cell(0,0,'Inscription 2012-2013',0,0,'C');
			// Saut de ligne
			$this->Ln(20);
		}

		// Section du formulaire
		function Section($nomSection)
		{
			$this->Ln(15);
			$this->SetFont('Arial','B',20);
			$this->Cell(0,0,$nomSection, 0, 0);
			$this->Ln(10);
		}

		// Champ texte
		// Le cadre de texte pour $nomChamp ne sera pas tracé s'il n'existe pas
		// Le cadre de texte pour $donnee ne sera pas tracé s'il n'existe pas
		function ChampTxt($nomChamp, $donnee,$largeurCadreG=50, $largeurCadreD=50)
		{
			if ($nomChamp != null)
			{
				$this->SetFont('Arial','B',12);
				$this->Cell($largeurCadreG,0,$nomChamp, 0, 0);
			}
			if ($donnee != null)
			{
				$this->SetFont('Arial','',12);
				$this->Cell($largeurCadreD,0,$donnee, 0, 0);
			}
		}

		function MultiCell($w, $h, $txt, $border=0, $align='J', $fill=false, $maxline=0)
		{
			//Output text with automatic or explicit line breaks, at most $maxline lines
			$cw=&$this->CurrentFont['cw'];
			if($w==0)
			$w=$this->w-$this->rMargin-$this->x;
			$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
			$s=str_replace("\r",'',$txt);
			$nb=strlen($s);
			if($nb>0 && $s[$nb-1]=="\n")
			$nb--;
			$b=0;
			if($border)
			{
			if($border==1)
			{
				$border='LTRB';
				$b='LRT';
				$b2='LR';
			}
			else
			{
				$b2='';
				if(is_int(strpos($border,'L')))
				$b2.='L';
				if(is_int(strpos($border,'R')))
				$b2.='R';
				$b=is_int(strpos($border,'T')) ? $b2.'T' : $b2;
			}
			}
			$sep=-1;
			$i=0;
			$j=0;
			$l=0;
			$ns=0;
			$nl=1;
			while($i<$nb)
			{
			//Get next character
			$c=$s[$i];
			if($c=="\n")
			{
				//Explicit line break
				if($this->ws>0)
				{
				$this->ws=0;
				$this->_out('0 Tw');
				}
				$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
				$i++;
				$sep=-1;
				$j=$i;
				$l=0;
				$ns=0;
				$nl++;
				if($border && $nl==2)
				$b=$b2;
				if($maxline && $nl>$maxline)
				return substr($s,$i);
				continue;
			}
			if($c==' ')
			{
				$sep=$i;
				$ls=$l;
				$ns++;
			}
			$l+=$cw[$c];
			if($l>$wmax)
			{
				//Automatic line break
				if($sep==-1)
				{
				if($i==$j)
					$i++;
				if($this->ws>0)
				{
					$this->ws=0;
					$this->_out('0 Tw');
				}
				$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
				}
				else
				{
				if($align=='J')
				{
					$this->ws=($ns>1) ? ($wmax-$ls)/1000*$this->FontSize/($ns-1) : 0;
					$this->_out(sprintf('%.3F Tw',$this->ws*$this->k));
				}
				$this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
				$i=$sep+1;
				}
				$sep=-1;
				$j=$i;
				$l=0;
				$ns=0;
				$nl++;
				if($border && $nl==2)
				$b=$b2;
				if($maxline && $nl>$maxline)
				{
				if($this->ws>0)
				{
					$this->ws=0;
					$this->_out('0 Tw');
				}
				return substr($s,$i);
				}
			}
			else
				$i++;
			}
			//Last chunk
			if($this->ws>0)
			{
			$this->ws=0;
			$this->_out('0 Tw');
			}
			if($border && is_int(strpos($border,'B')))
			$b.='B';
			$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
			$this->x=$this->lMargin;
			return '';
		}
	}

	$pdf = new PDF();
	$pdf->AddPage();
	$valide = true;

	// SECTION : Informations sur l'entreprise
	$pdf->section('Informations sur l\'entreprise');

	// Nom entreprise
	if (!isset($_POST['nomEntreprise']) || !is_string($_POST['nomEntreprise'])) {
		$valide=false;
	}else{		
		$pdf->ChampTxt('Nom de l\'entreprise : ',$_POST["nomEntreprise"]);
		$pdf->Ln(6);
	}
	
	// Nom du responsable
	if (!$valide || !( isset($_POST['nomResponsable']) && isset($_POST['prenomResponsable'])
		&& is_string($_POST['nomResponsable']) && is_string($_POST['prenomResponsable']) )) {
		$valide=false;
	}else{
		$pdf->ChampTxt('Nom du responsable : ',$_POST["nomResponsable"].' '.$_POST["prenomResponsable"]);
		$pdf->Ln(6);
	}

	// Téléphone et mail du responsable
	if (!$valide || !(isset($_POST['telephone']) && is_numeric($_POST['telephone']) && isset($_POST['mail']))) {
		$valide=false;
	}else{
		$pdf->ChampTxt('Num. de tel : ',$_POST["telephone"]);
		$pdf->ChampTxt('Adresse mail : ',$_POST["mail"]);
		$pdf->Ln(6);
	}

	// Type d'entreprise
	if (!$valide || !(isset($_POST['typeEntreprise']) && is_string($_POST['typeEntreprise']) ) ) {
		$valide=false;
	}else{
		$pdf->ChampTxt('Type d\'entreprise : ',$_POST["typeEntreprise"]);
		$pdf->Ln(6);
	}

	// Description de l'entreprise
	if (!$valide || !isset($_POST['descriptionEntreprise']) || !is_string($_POST['descriptionEntreprise'])) {
		$valide=false;
	}else{
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(65,0,'Description de votre entreprise ',0,0);
		$pdf->SetFont('Arial','I',12);
		$pdf->Cell(0,0,'(description qui apparaitra sur la brochure de l\'evenement)',0,0);

		$pdf->Ln(5);
		$pdf->SetFont('Arial','',12);
		$pdf->MultiCell(0,5,$_POST['descriptionEntreprise'],0,'G',0,4);		
	}
	
	// SECTION : Informations générales
	$pdf->section('Informations generales');

	// Intervenants
	// On parcourt la liste des intervenants
	if ($valide && isset($_POST['nomIntervenants'])){
		$pdf->ChampTxt('Intervenants : ','');
		$i = 0;
		foreach ($_POST['nomIntervenants'] as $valeur) {
			if (is_string($valeur))
			{
				// Si on affiche le premier intervenant, on met '' pour ne pas tracer le cadre texte du champ "Intervenants"
				if ($i==0){
					$pdf->ChampTxt('',strtoupper($valeur).' '.$_POST['prenomIntervenants'][$i]);
					$pdf->ChampTxt('Nb d\'intervenants : ',sizeof($_POST['nomIntervenants']) );
				}else
					$pdf->ChampTxt(' ',strtoupper($valeur).' '.$_POST['prenomIntervenants'][$i]);
				$pdf->Ln(6);
			}
			$i++;
		}
	}

	// Présence
	if (!$valide || !isset($_POST['momentPresence']) || !is_string($_POST['momentPresence'])) {
		$valide=false;
	}else{
		$pdf->ChampTxt('Presence : ',$_POST["momentPresence"]);
	}

	// Restaurant
	if (!$valide || !isset($_POST['restaurant']) || !is_string($_POST['restaurant'])) {
		$valide=false;
	}else{
		$donnee = $_POST['restaurant'];
		if ($donnee == 'oui')
			$donnee = $donnee.', pour '.$_POST['nbPers_restaurant'].' personnes.';
		$pdf->ChampTxt('Restaurant : ',$donnee);
		$pdf->Ln(10);
	}

	// Taxe d'apprentissage
	if (!$valide || !isset($_POST['TA']) || !is_string($_POST['TA'])) {
		$valide=false;
	}else{
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(70,0,'Taxe d\'apprentissage a l\'INSA : ',0,0);
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(0,0,$_POST["TA"],0,0);
	}

	// SECTION : Informations techniques
	$pdf->section('Informations techniques');

	// Description du matériel apporté
	if (!$valide || !isset($_POST['infoMatosTechnique']) || !is_string($_POST['infoMatosTechnique'])) {
		$valide=false;
	}else{
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(80,0,'Description du materiel apporte : ',0,0);
		$pdf->Ln(6);
		$pdf->SetFont('Arial','',12);
		$pdf->MultiCell(0,5,$_POST['infoMatosTechnique'],0,'G',0,4);
		$pdf->Ln(6);
	}

	// Description du matériel apporté
	if (!$valide || !isset($_POST['infoNbPrise']) || !is_numeric($_POST['infoNbPrise'])) {
		$valide=false;
	}else{
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(100,0,'Nombre de prises electriques necessaires : ',0,0);
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(0,0,$_POST['infoNbPrise']);
	}

	// SECTION : Informations Complémentaires
	$pdf->section('Informations Complementaires');

	// Attentes
	if (!$valide || !isset($_POST['attente']) || !is_string($_POST['attente'])) {
		$valide=false;
	}else{
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(0,0,'Quelles sont vos attentes concernant votre participation aux rencontres IF?',0,0);
		$pdf->Ln(5);
		$pdf->SetFont('Arial','',12);
		$pdf->MultiCell(0,5,$_POST['attente'],0,'G',0,4);
		$pdf->Ln(6);
	}

	// Commentaire
	if (!$valide || !isset($_POST['autre']) || !is_string($_POST['autre'])) {
		$valide=false;
	}else{
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(0,0,'Autres (commentaires, remarques)',0,0);
		$pdf->Ln(5);
		$pdf->SetFont('Arial','',12);
		$pdf->MultiCell(0,5,$_POST['autre'],0,'G',0,4);
		$pdf->Ln(6);
	}

	if ($valide)
	{
		// Contact AEDI
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(116,0,'Pour toutes informations supplementaires, veuillez contacter l\'',0,0);
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(36,0,'equipe Entreprise',0,0);
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(0,0,' de l\'AEDI.',0,0);
		$pdf->Ln(6);
		$pdf->ChampTxt('Mail : ','aedi.entreprises@gmail.com',20);
		$pdf->Ln(6);
		$pdf->ChampTxt('Tel : ','',20);

		$pdf->Ln(6);
		// Signature
		$pdf->ChampTxt(' ','Le ........... a ................',80,70);
		$pdf->ChampTxt('Signature','');

		$fileName = trim($_POST['nomEntreprise']);
		$fileName = str_replace(' ','_', $fileName).'-'.date('Y');

		$pdf->Output('../formulaireInscription/'.$fileName.'.pdf');

		//forcerTelechargement($fileName.'.pdf', '../formulaireInscription/'.$fileName.'.pdf', 10000);


	}

	/* TODO
		Enregistrer en BDD
	*/
	
?>

	<p>Un formulaire d'inscription pour les Rencontres IFs a été généré.</p>
	<p>Veuillez l'imprimer pour nous le renvoyer une fois signé.</p>
	<p>En espérant avoir l'occasion de partager les Rencontres IFs, nous vous remercions d'avoir pris le temps de compléter ce formulaire.</p>
	<div class="centre" style="text-align:center;"><a href="rifs/php/dl.php?fileName=<?php echo $fileName?>.pdf" action="_blank" class="btn btn-large btn-primary"><strong>Télécharger le formulaire d'Inscription</strong></a></div>
