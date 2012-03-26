<?php

require_once dirname(__FILE__) . '/../commun/php/base.inc.php';

// Chemin vers le dump du département au format CSV.
$chemin = "./data.csv";

$limite_taille_titre = 40; // caractères
$connexion = BD::GetConnection();

// On compte d'abord le nombre de lignes pour être sûr de ne pas en oublier.
$nombre_lignes_total = 0;
if ($fichier = file($chemin)) {
	$nombre_lignes_total = count($fichier);	
} else {
	echo "Impossible d'ouvrir le fichier $chemin.";
	die();
}

if ($fichier = fopen($chemin, 'r')) {
	$num = 0; // numéro de ligne du fichier
	$numStage = 0; // nombre de stages ajoutés
                
	while ($num < $nombre_lignes_total) {
		$ligne = fgetcsv($fichier, 1000, ';', '"'); 
		// On ignore la première ligne, qui contient les
		// en-têtes.

		// Récupération des valeurs
		if ($num > 0 && $ligne) {
			$estDispo = $ligne[10] == 'O'; // ouvert
			if ($estDispo) {
				$entreprise = $ligne[2];
				$contact = $ligne[3];
				$lieu = $ligne[4] . ' ' . $ligne[5];
				$annee = $ligne[6]; // prend les valeurs 3, 4, 5, 
						    // 7 (3 et 4ème années), 9 (4 et 5ème années)
                            
				// Si le titre est plus grand que limite_taille_titre,
				// on stocke le titre entier dans description, et on coupe
				// le titre.
				$titre = $ligne[7];
				$aDescription = false;
				if (strlen($titre) > $limite_taille_titre) {
				       	$description = $titre;
					$aDescription = true;
					$titre = substr($titre, 0, $limite_taille_titre) . '...';
				}
                
				$lien_fichier = $ligne[9];

		// Préparation de la requête d'insertion.
                $requete = 'INSERT INTO `Stage` (`titre`, `annee`, 
                `description`, `lien_fichier`, `lieu`, `entreprise`, `contact`) VALUES ';
                $requete .= '(:titre, :annee, :description, :lien_fichier,
                :lieu, :entreprise, :contact)';
                
		// Tableau des attributs pour PDO
                $attributs['titre'] = $titre;
                $attributs['annee'] = $annee;
                $attributs['description'] = ($aDescription) ?
                    $description :
                    NULL;
                $attributs['lien_fichier'] = $lien_fichier;
                $attributs['lieu'] = $lieu;
                $attributs['entreprise'] = $entreprise;
                $attributs['contact'] = $contact;
                
                BD::Prepare($requete, $attributs);
		$numStage++;
			}
		}
		$num++;
	}
    echo "Au total, $numStage lignes ont été correctement insérées.";
} else {
	echo "Impossible d'ouvrir le fichier $chemin"; 
}

?>
