<?php

function inclure_fichier($_module, $_nom_fichier, $_type) {
    $module = trim(strtolower($_module));
    $nom_fichier = trim(strtolower($_nom_fichier));
    $type = trim(strtolower($_type));

    if ($type == 'php') {
        $path = dirname(__FILE__) . "/../../$module/$nom_fichier.$type";
        if (file_exists($path)) {
            require_once($path);
            return;
        }
    } else if ($type == 'css') {
        $path = dirname(__FILE__) . "/../../$module/css/$nom_fichier.$type";
        if (file_exists($path)) {
            echo  "<style type=\"text/css\">";
            echo "@import \"$module/css/$nom_fichier.$type\";";
            echo "</style>";
            return;
        }
    } else if ($type == 'js') {
        $path = dirname(__FILE__) . "/../../$module/js/$nom_fichier.$type";
        if (file_exists($path)) {
            echo "<script type=\"text/javascript\" src=\"$module/js/$nom_fichier.$type\"></script>";
            return;
        }
    }
}

?>
