<?php

//Fonction qui prends une array de se type : 
//Array ( [0] => Array ( [ID_LANGUE] => 1 [LIBELLE_LANGUE] => Français ) [1] => Array ( [ID_LANGUE] => 2 [LIBELLE_LANGUE] => Anglais )
//et la transforme en : 
//Array ( [0] => Array ( [id] => 1 [label] => Français ) [1] => Array ( [id] => 2 [label] => Anglais )
function Adaptation_tableau($_array) {
    $return_array = array();
    $i = 0;
    foreach ($_array as $array_value) {
        $return_array[$i] = array();
        $id = true;
        foreach ($array_value as $value) {
            if ($id) {
                $return_array[$i]['id'] = $value;
                $id = false;
            } else {
                $return_array[$i]['label'] = $value;
                $id = true;
            }
        }
        $i++;
    }
    return $return_array;
}



function Racine_site() {
    $all_dir = explode("/", $_SERVER['PHP_SELF']);
    $nbrel = count($all_dir) - 1;

    $nb = 1;
    $dir = "";

    while ($nb < $nbrel) {
        if (!empty($dir)) {
            $dir .= "/";
        }
        $dir .= $all_dir[$nb];
        $nb++;
    }
    return $dir;
}

//Fonction qui gere l'inclusion de fichiers
function inclure_fichier($_module, $_nom_fichier, $_type) {
    $racine = Racine_site();

    $module = trim( $_module );
    $nom_fichier = trim( $_nom_fichier );
    $type = trim( $_type );
	$path = "";

    if ($type == 'php') {
        if ($module == '') {
            $path = dirname(__FILE__) . "/../../$nom_fichier.$type";
        } elseif ($module == 'controleur'){
            $path = dirname(__FILE__) . "/../../$module/$nom_fichier.$type";
	}
	elseif( $module === 'modele' ) {
		$path = dirname( __FILE__ ) . "/../../$module/$nom_fichier.$type";
        }else{
            $path = dirname(__FILE__) . "/../../$module/php/$nom_fichier.$type";
        }

        if (file_exists($path)) {
            require_once $path;
            return;
        }
    } else if ($type == 'css') {
        if ($module == '') {
            $path = "$module/$nom_fichier.$type";
        } else {
            $path = "$module/css/$nom_fichier.$type";
        }

        if (file_exists(dirname(__FILE__) . "/../../" . $path)) {
            echo "<style type=\"text/css\">";
            echo "@import \"$path\";";
            echo "</style>";
            return;
        }
    } else if ($type == 'js') {
        if ($module == '') {
            $path = "$module/$nom_fichier.$type";
        } else {
            $path = "$module/js/$nom_fichier.$type";
        }

        if (file_exists(dirname(__FILE__) . "/../../" . $path)) {
            echo "<script type=\"text/javascript\" src=\"$path\"></script>";
            return;
        }
    } else if ($type == 'template') {
        if ($module == '') {
            $path = "$module/$nom_fichier.html";
        } else {
            $path = "$module/template/$nom_fichier.html";
        }

        if (file_exists(dirname(__FILE__) . "/../../" . $path)) {
            require_once($path);
            return;
        }
    }

	/* TODO : WTF */
    echo "<br>Fichier non trouvé :  $path<br>";
    echo "Parametres :<br>";
    echo "Module : $module<br>";
    echo "Nom du fichier : $nom_fichier<br>";
    echo "Type : $type<br>";
}

//Fonction permettant de verifier que l'utilisateur est connecté et qu'il 
//appartient bien au groupe demandé
//$groupe = [etudiant,entreprise,'']
function Utilisateur_connecter($_groupe) {
    return true;
    if ($_SESSION['utilisateur'] != null) {
        if ($_groupe == "etudiant") {
            return $_SESSION['utilisateur']->estEtudiant();
        }
        if ($_groupe == "entreprise") {
            return $_SESSION['utilisateur']->estEntreprise();
        }
        if ($_groupe == "") {
            return true;
        }
        return false;
    }
    return false;
}

//Fonction de protection contre les attaques xss
//à utiliser avant chaque inclusion de texte que l'utilisateur a tapé
function Protection_XSS($_chaine) {
    return utf8_encode(htmlentities(utf8_decode($_chaine)));
}


/**
 * Fonction qui vérifie si un item donné est présent dans le 
 * tableau global $_POST, et s'il est non vide.
 * Auteur : benjamin.bouvier@gmail.com
 */
function verifierPresent($index) {
	if (!isset($_POST[$index])) {
		return false;
	}

	$sansBlanc = trim($_POST[$index]);
	return !empty($sansBlanc);
}
function verifierPresentObjet($index) {
	if (!isset($_POST[$index])) {
		return false;
	}
	return true;
}



/**
 * Fonction qui vérifie si une adresse email donnée est valide grace a une regex
 * Auteur : daniel.baudry2@gmail.com
 */
function verifierMail($adresse) {
	$syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#'; 
	if(preg_match($syntaxe,$adresse)){ 
		return true; 
	}else{
		return false;
	}
}


/**
 * Fonction qui vérifie si un numero telephone donné est valide grace a une regex
 * Auteur : daniel.baudry2@gmail.com
 */
function verifierTelephone($tel) {
	$syntaxe='#^0[0-9]([ .-]?[0-9]{2}){4}$#'; 
	if(preg_match($syntaxe,$tel)){ 
		return true; 
	}else{
		return false;
	}
}


/**
 * Fonction qui vérifie si une date donnée est valide grace a une regex
 * Auteur : daniel.baudry2@gmail.com
 */
function verifierDate($date) {
	$syntaxe='(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)[0-9]{2}'; 
	if(preg_match($syntaxe,$date)){ 
		return true; 
	}else{
		return false;
	}
}


/**
 * Fonction qui vérifie si un horaire donné est valide grace a une regex
 * exemple: 12h34
 * Auteur : daniel.baudry2@gmail.com
 */
function verifierHoraire($horaire) {
	$syntaxe='^[0-9]{2}h[0-9]{2}$'; 
	if(preg_match($syntaxe,$horaire)){ 
		return true; 
	}else{
		return false;
	}
}

<<<<<<< HEAD
=======
/**
 * Vérification d'un utilisateur est authentifié et autorisé à accèder à une ressource
 * @param logger Instance log4php
 * @param roles	Tableau des roles devant être vérifiés
 * @return L'utilisateur actuellement authentifié
 */
inclure_fichier('commun', 'authentification.class', 'php');
function controlerAuthentificationJSON($logger, $roles) {

	$authentification = new Authentification();
	/* Vérification que l'utilisateur est authentifié */
	if( $authentification->isAuthentifie() == false ) {
		$logger->error( "Utilisateur non authentifié." );
		die( json_encode( array( 'code' => 'fail', 'mesg' => 'Vous n\'êtes pas authentifié.' ) ) );
	}

	/* Vérification des accréditations */
	$user_role = $authentification->getUtilisateur()->getPersonne()->getRole();

	/* bool */ $authorized = false;
	foreach ($roles as $r) {
		if( $user_role == $r ) {
			$authorized = true;
			break;
		}
	}

	/* Non authorisé, on die */
	if( !$authorized ) {
		$logger->fatal( "Utilisateur non autorisé. [Login: ".$authentification->getUtilisateur()->getLogin()."]" );
		die( json_encode( array( 'code' => 'critical', 'mesg' => 'Vous n\'êtes pas autorisé à effectuer cette action.' ) ) );
	}

	// Retour de l'utilisateur courant
	return $authentification->getUtilisateur();
}

>>>>>>> upstream/master
?>
