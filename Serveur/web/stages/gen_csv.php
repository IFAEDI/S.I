<?php

	$content = file( "stage.html" );	

	/* Parsing à la mano */
	for( $i = 0; $i < count( $content ); $i++ )
	{
		/* On prend les <h3> */
		if( strstr( $content[$i], "h3" ) )
		{
			/* <h3>4-5IF n° 1&nbsp;&nbsp; <i>Entreprise</i> : THALES&nbsp;&nbsp;&nbsp;NEUILLY SUR SEINE Hauts de Seine</h3> */

			/* On enlève <h3> et on va tomber sur les années concernées */
			$off = 0;

			$annee = array();
			$id    = "";
			$enterprise = "";
			$where = "";
			$contact = "";
			$sujet = "";
			$desc = "";

			$cur = substr( $content[$i], 4 );
			$annee[0] = $cur[$off];

			$off++;

			/* 4-5 par exemple */
			if( $cur[$off] == '-' )
			{
				$off++;
				$annee[1] = $cur[$off];
			}

			/* IF n° 1 */
			$off += 7;

			$c = 0;
			while( $cur[$off+$c] != '&' ) {
	
				$id .= $cur[$off+$c];

				$c++;
			}

			$off += $c;

			/* &nbsp;&nbsp; <i>Entreprise</i> : */
			$off += 33;

			$c = 0;
			while( $cur[$off+$c] != '&' ) {

				$enterprise .= $cur[$off+$c];

				$c++;
			}	

			$off += $c;

			/* &nbsp;&nbsp;&nbsp; */
			$off += 18;

			$c = 0;
			while( $cur[$off+$c] != '<' ) {

				$where .= $cur[$off+$c];

				$c++;
			}	
			
	
			/* Parsing du contact */
			/*<i>Contact</i> : Céline CHARAVAY<br>*/
			$i++; // Ligne suivante
			$off = 0;

			/* On échappe le <i>Contact</i> :  */			
			$off = 17;
			$cur = $content[$i];

			$c = 0;
			while( $cur[$off+$c] != '<' ) {

				$contact .= $cur[$off+$c];

				$c++;
			}

			/* Parsing du sujet */
			/*<i>Sujet</i> :<br>Développement d'une application Java basée sur Netbeans Platform dans un contexte recherche<br> */
			$i++;

                        /* On échappe le <i>Sujet</i> :<br>  */
                        $cur = substr( $content[$i], 18 );

			$sujet = substr( $cur, 0, strlen( $cur ) - 5 );
			$sujet = preg_replace( "/<br>/", "\n", $sujet );


			/* Parsing de la description */
			/*<i>Description</i> : <a href="http://intranet-if.insa-lyon.fr/stages/descriptif/CEA_2012_FlexibleMeccano_Court-1.doc">CEA_2012_FlexibleMeccano_Court-1.doc</a><br> */

			$i++;
			$off = 0;

			$cur = substr( $content[$i], 30 );
			$desc = substr( $cur, 0, strpos( $cur, '>' ) - 1 );

			echo "$id;$enterprise;$where;$annee[0]";
			if( count( $annee ) > 1 ) echo "-$annee[1]";

			echo "$contact;$sujet;$desc\n";
		}
	}

?>
