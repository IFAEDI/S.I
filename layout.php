<?php
/***************************************************
* Script contenant le header et le footer et incluant les différentes pages
* en fonction de la demande et de l'analyse du script d'index.
***************************************************/

global $titre_page;
global $nom_module;
global $lien_module;
global $titre_module;
global $nom_page;
global $theme;
global $authentification;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
		<link rel="shortcut icon" href="./favicon.ico" >
        <?php
        inclure_fichier('commun', 'bootstrap.min', 'css');
        inclure_fichier('commun', 'bootstrap-responsive.min', 'css');
        inclure_fichier('commun', 'style', 'css');
        inclure_fichier('commun', 'ui-lightness/jquery-ui', 'css');

        inclure_fichier('commun', 'jquery.min', 'js');
        inclure_fichier('commun', 'jquery-ui.min', 'js');
        inclure_fichier('commun', 'bootstrap.min', 'js');
        inclure_fichier('commun', 'datepicker.fr', 'js');
        inclure_fichier('commun', 'json2', 'js');
        inclure_fichier('commun', 'utils', 'js');
	inclure_fichier('commun', 'jquery.tablesorter.min', 'js');
		
		
	inclure_fichier('commun', 'login', 'js');
	inclure_fichier('commun', 'SHA1', 'js');
        ?>

        <title>AEDI - <?php echo $titre_page; ?></title>
    </head>

    <body>
        <?php 
	inclure_fichier('', 'menu', 'php'); 
	inclure_fichier('', 'login', 'php' ); 

	/* Si on a un module, on affiche le breadcrumb, sinon ce n'est pas nécessaire */
        if ($nom_module != '') { ?>
            <ul class="breadcrumb" >
                <li>
                    <a href="index.php?page=Accueil"><i class="icon-home"></i></a> <span class="divider">/</span>
                </li>
				<?php
				if ($titre_module != '') { ?>
					<li>
						<?php echo $titre_module; ?> <span class="divider">/</span>
					</li>
				<?php } ?>
                <li class="active"><?php echo $titre_page; ?></li>
            </ul>
        <?php }
		if ($nom_module == 'accueil') { $nom_module = ''; } ?>

        <div class="container">
            <div class="module">   
                <?php inclure_fichier($nom_module, $nom_page, 'php'); ?>
            </div>
            <p id="layout" class="footer">&copy; AEDI - 2013</p>
        </div>

    </body>
</html>
