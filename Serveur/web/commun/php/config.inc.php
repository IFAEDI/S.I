<?php

global $CONFIG;
$CONFIG = array(
	'bd' => array(
		'hote' => 'localhost',
		'port' => 3306,
		'bdnom' => 'aedi',
		'nom_utilisateur' => 'aedi',
		'mot_de_passe' => 'aedi',
	),
	'sso' => array (
		'server' => 'cas.insa-lyon.fr/cas/',
		'port' => 443,
		'root' => '',
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
