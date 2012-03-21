<?php

if (!Utilisateur_connecter('etudiant')) {
    inclure_fichier('', '401', 'php');
    die;
}

inclure_fichier('controleur', 'etudiant.class', 'php');

$etudiant = new Etudiant();
$etudiant = Etudiant::GetEtudiantByID(1);
?>

<h1>Bienvenue sur la page d'accueil de la CVthèque</h1><br/><br/>



<?php

if ($etudiant == null) {
    echo "Oooooooh mais tu n'as pas de CV comme c'est dommage<br/>";
    echo "<a class=\"btn\" href=\"index.php?page=edit_cv\">Creer mon CV</a><br/>";
} else {
    echo "<br/><a class=\"btn\" href=\"index.php?page=edit_cv\">Editer mon CV</a><br/>";
}
?>