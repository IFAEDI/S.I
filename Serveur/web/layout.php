<?php
global $titre_page;
global $nom_module;
global $lien_module;
global $nom_page;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <?php
            inclure_fichier('global', 'bootstrap.min', 'css');
            inclure_fichier('global', 'bootstrap-responsive.min', 'css');
            inclure_fichier('global', 'style', 'css');
            inclure_fichier('global', 'jquery.min', 'js');
        ?>
        <title>AEDI - <?php echo $titre_page ?></title>
    </head>

    <body>
        <?php inclure_fichier('', 'menu', 'php'); ?>
		<?php if($nom_module != '') {?>
        <ul class="breadcrumb" style="margin-top: 0px;">
            <li>
                <a href="#"><i class="icon-home"></i></a> <span class="divider">/</span>
            </li>
            <li>
                <a href="<?php echo $lien_module ?>" ><?php echo $nom_module ?></a> <span class="divider">/</span>
            </li>
            
            <li class="active">Page <?php echo $nom_page ?></li>
        </ul>
		<?php } ?>
		
        <div class="container">
            <div class="module">
                <?php inclure_fichier($nom_module, $nom_page, 'php'); ?>
            </div>
            <p id="footer">&copy; AEDI - 2012</p>
        </div>
        <?php inclure_fichier('global', 'bootstrap.min', 'js'); ?>
    </body>
</html>