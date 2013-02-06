<?php
	$url = ('../formulaireInscription/'.$_GET['fileName']);
	header('Content-Description: File Transfer');
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="'. basename($url) .'";');
	@readfile($url) OR die();
?>