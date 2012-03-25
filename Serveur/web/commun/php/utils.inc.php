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

    $module = trim(strtolower($_module));
    $nom_fichier = trim(strtolower($_nom_fichier));
    $type = trim(strtolower($_type));

    if ($type == 'php') {
        if ($module == '') {
            $path = dirname(__FILE__) . "/../../$nom_fichier.$type";
        } elseif ($module == 'controleur'){
            $path = dirname(__FILE__) . "/../../$module/$nom_fichier.$type";
        }else{
            $path = dirname(__FILE__) . "/../../$module/php/$nom_fichier.$type";
        }

        if (file_exists($path)) {
            require_once($path);
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
    }

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

?>
