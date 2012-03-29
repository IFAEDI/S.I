<?php

global $CONFIG;
$CONFIG = array(
	'bd' => array(
		'hote' => 'localhost',
		'port' => 3306,
		'bdnom' => 'aedi',
		'nom_utilisateur' => 'root',
		'mot_de_passe' => 'pioupiou',
	),
	'sso' => array (
		'server' => 'cas.insa-lyon.fr',
		'port' => 443,
		'root' => '/cas',
	),
	'debug' => array (
		'auth' => true,
	),
);


global $SALT;
$SALT = "mHlAponNPd";

global $THEME;
$THEME = array(
    '0' => 'Defaut',
    '1' => 'Bleu',
);
?>
