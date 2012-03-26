<?php
global $titre_page;
global $nom_module;
global $lien_module;
global $titre_module;
global $nom_page;
global $theme;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <?php
        //Application du numéro de thème choisi par l'utilisateur
        if ($theme == 1) {
            inclure_fichier('commun', 'bootstrap.min', 'css');
            inclure_fichier('commun', 'bootstrap-responsive.min', 'css');
            inclure_fichier('commun', 'style', 'css');
            inclure_fichier('commun', 'ui-lightness/jquery-ui', 'css');
        } else {
            //Si le numero est invalide application du thème par defaut
            inclure_fichier('commun', 'bootstrap.min', 'css');
            inclure_fichier('commun', 'bootstrap-responsive.min', 'css');
            inclure_fichier('commun', 'style', 'css');
            inclure_fichier('commun', 'ui-lightness/jquery-ui', 'css');
        }
        
        inclure_fichier('commun', 'jquery.min', 'js');
        inclure_fichier('commun', 'jquery-ui.min', 'js');
        inclure_fichier('commun', 'bootstrap.min', 'js');
        inclure_fichier('commun', 'datepicker.fr', 'js');
        inclure_fichier('commun', 'json2', 'js');
        inclure_fichier('commun', 'utils', 'js');

	inclure_fichier('commun', 'login', 'js');
        ?>
        <title>AEDI - <?php echo $titre_page ?></title>
    </head>

    <body>
        <?php inclure_fichier('', 'menu', 'php'); ?>
        <?php if ($nom_module != '') { ?>
            <ul class="breadcrumb" >
                <li>
                    <a href="index.php?page=accueil"><i class="icon-home"></i></a> <span class="divider">/</span>
                </li>
                <li>
                    <a href="<?php echo $lien_module ?>" ><?php echo $titre_module ?></a> <span class="divider">/</span>
                </li>
                <li class="active"><?php echo $titre_page ?></li>
            </ul>
        <?php } ?>

        <div class="container">
			<div class="titre" style="margin-top: 20px;">
				<?php echo $titre_page ?>
			</div>
            <div class="module">   
                <?php inclure_fichier($nom_module, $nom_page, 'php'); ?>
            </div>
            <p id="footer">&copy; AEDI - 2012</p>
        </div>

    </body>
</html>
