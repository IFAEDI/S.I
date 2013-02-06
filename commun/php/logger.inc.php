<?php

/***************************************************
* Initialisation de log4php pour la journalisation *
* Date : 17.09.2012                                *
* Auteur : sebastien.meriot@gmail.com              *
***************************************************/

require_once dirname(__FILE__) . '/log4php/Logger.php';

/* TODO : Rendre ceci plus propre... */
Logger::configure( dirname(__FILE__) . '/../../config/log4php.xml' );

?>
