<?php

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
    
    $module = trim(strtolower($_module));
    $nom_fichier = trim(strtolower($_nom_fichier));
    $type = trim(strtolower($_type));

    if ($type == 'php') {
        if ($module == '' || $module == 'controleur') {
            $path = dirname(__FILE__) . "/../../$nom_fichier.$type";
        } else {
            $path = dirname(__FILE__) . "/../../$module/php/$nom_fichier.$type";
        }

        if (file_exists($path)) {
            require_once($path);
            return;
        }
    } else if ($type == 'css') {
        if ($module == '' || $module == 'controleur') {
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
        if ($module == '' || $module == 'controleur') {
            $path = "/$module/$nom_fichier.$type";
        } else {
            $path = "/$module/js/$nom_fichier.$type";
        }

        if (file_exists(dirname(__FILE__) . "/../../" . $path)) {
            echo "<script type=\"text/javascript\" src=\"/$racine$path\"></script>";
            return;
        }
    }

    echo "<br>Fichier non trouvé :  $path<br>";
    echo "Parametres :<br>";
    echo "Module : $module<br>";
    echo "Nom du fichier : $nom_fichier<br>";
    echo "Type : $type<br>";
}

//Fonction permettant de verifier que l'utilisateur est connecté et qu'il 
//appartient bien au groupe demandé
//$groupe = [etudiant,entreprise]
function Utilisateur_connecter($groupe) {
    if ($_SESSION['utilisateur'] != null) {
        if ($groupe == "etudiant") {
            if ($_SESSION['utilisateur']->estEtudiant()) {
                return true;
            }
            return false;
        }
        if ($groupe == "entreprise") {
            if ($_SESSION['utilisateur']->estEntreprise()) {
                return true;
            }
            return false;
        }
    }
    return false;
}

?>
