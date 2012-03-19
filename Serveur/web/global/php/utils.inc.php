<?php


//Fonction qui gere l'inclusion de fichiers
function inclure_fichier($_module, $_nom_fichier, $_type) {
    $module = trim(strtolower($_module));
    $nom_fichier = trim(strtolower($_nom_fichier));
    $type = trim(strtolower($_type));

    if ($type == 'php') {
        if ($module == '' || $module == 'controleur') {
            $path = dirname(__FILE__) . "/../../$nom_fichier.$type";
        } else {
            $path = dirname(__FILE__) . "/../../php/$module/$nom_fichier.$type";
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
            $path = "$module/$nom_fichier.$type";
        } else {
            $path = "$module/js/$nom_fichier.$type";
        }

        if (file_exists(dirname(__FILE__) . "/../../" . $path)) {
            echo "<script type=\"text/javascript\" src=\"$path\"></script>";
            return;
        }
    }
}

?>
