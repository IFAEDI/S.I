<?php
/*****************************************
* Author : Sébastien Mériot		 *
* Date : 25.03.2012			 *
* Description : Cible des requêtes ajax  *
* concernant l'authentification          *
*****************************************/

if( @isset( $_GET['action'] ) )
{
	if( $_GET['action'] == "regular_auth" )
	{
		$val = array( "code" => "error", "mesg" => "Not implemented yet. [".$_GET['username']."/".$_GET['password']."]" );

		echo json_encode( $val );	
	}
}


?>
