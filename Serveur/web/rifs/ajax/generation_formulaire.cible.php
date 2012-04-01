<?php

	require('fpdf.php');
	$id=$_POST['nomEntreprise'];//Récupération de notre texte

	$date = date("d/m/y");//Ajout d'une date pour personnaliser notre document

	//Création du pdf
	$pdf=new FPDF();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',12);//définition de la police
	$pdf->SetXY(10,65);//Position de notre "traceur"
	$pdf->Cell(190,10,$id,1,1,'C');//Création d'une cellule de texte
	$pdf->Ln(50);//Saut de ligne
	$pdf->Cell(100,7,'crée le, '.$date,0,0,'L');//Horodatage.

	//sortie :
	$pdf->Output('id_feuille.pdf','D');
?>