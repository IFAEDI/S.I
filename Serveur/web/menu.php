<div class="navbar"  style="margin-bottom: -18px;">
    <div class="navbar-inner navbar-fixed-top" role="banner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </a>
            <a class="brand" href="#"><img src="commun/img/logo_bin.png" alt="AEDI" height="20px" width="18px"/> AEDI</a>

            <div class="nav-collapse">
                <ul class="nav">
                    <li><a href="index.php?page=accueil"><i class="icon-home icon-white"></i> Accueil</a></li>

                    <li class="divider-vertical"></li>
                    <li class="dropdown">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-user icon-white"></i> Etudiants <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="nav-header"><i class="icon-road"></i> Espace Pro.</li>
                            <li><a href="#">Simulations d'entretiens</a></li>
                            <li><a href="#">Stages</a></li>
                            <li><a href="index.php?page=accueil_cv">CV</a></li>

                            <li class="divider"></li>

                            <li class="nav-header"><i class="icon-glass"></i> Espace Détente</li>
                            <li><a href="#">Evénements</a></li>
                            <li><a href="#">Souvenirs</a></li>
                        </ul>
                    </li>

                    <li class="divider-vertical"></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-flag icon-white"></i> Entreprises <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li class="nav-header"><i class="icon-search"></i> Informations</li>
                            <li><a href="#">Nos offres</a></li>
                            <li><a href="#">XXXX</a></li>

                            <li class="divider"></li>

                            <li class="nav-header"><i class="icon-star"></i> Evénements</li>
                            <li><a href="#">Rencontres IF</a></li>
                            <li><a href="#">Simulations d'entretiens</a></li>
                            <li><a href="#">Stages</a></li>	
                        </ul>
                    </li>

                    <li class="divider-vertical"></li>
                    <li><a href="#"><i class="icon-envelope icon-white"></i> Contact</a></li>
                    <li class="divider-vertical"></li>
                    <li><a href="#"><i class="icon-info-sign icon-white"></i> A propos</a></li>
                    <li class="divider-vertical"></li>
                </ul>



                <form class="navbar-search pull-left" action="">
                    <input type="text" class="search-query span2" placeholder="Recherche">
                </form>

                <ul class="nav pull-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-flag icon-white"></i> Thèmes <b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <?php
                            global $THEME;
                            for ($i = 0; $i < count($THEME); $i++) {
                                echo "<li><a href=\"javascript:change_theme($i)\">$THEME[$i]</a></li>";
                            }
                            ?>

                        </ul>
                    </li>
                </ul>

                <script>
                    //Fonction permettant de changer le theme par le numéro
                    // du theme passé en paramètre
                    function change_theme(theme){
                        $.ajax({ // fonction permettant de faire de l'ajax
                            type: "POST", // methode de transmission des données au fichier php
                            url: "index.php", // url du fichier php
                            data: {theme : theme},
                            success: function(retour){ // si l'appel a bien fonctionné
                                location.reload() ; 
                            }
                        });
                    } 
                </script>

                <ul class="nav pull-right">
                    <li><a href="#">Se connecter</a></li>
                    <ul class="dropdown-menu">
                    <li>Login :</li>
                    </ul>
                    <li class="divider-vertical"></li>
                </ul>
            </div><!-- /.nav-collapse -->
        </div>
    </div><!-- /navbar-inner -->
</div><!-- /navbar -->
