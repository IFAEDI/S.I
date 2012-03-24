<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php
if (isset($_GET['id_etudiant']) && Utilisateur_connecter('entreprise')) {
    $id_etudiant = $_GET['id_etudiant'];
} elseif (Utilisateur_connecter('etudiant')) {
    $id_etudiant = $_SESSION['utilisateur']->getId();
} else {
    inclure_fichier('', '401', 'php');
    die();
}

inclure_fichier('cvtheque', 'cv', 'css');

$etudiant = new Etudiant();
$etudiant = Etudiant::GetEtudiantByID($_SESSION['utilisateur']->getId());


if ($etudiant == NULL) {
    $etudiant = new Etudiant();
}

$cv = $etudiant->getCV();
$liste_diplome_etudiant = $etudiant->getDiplome();
$liste_langue_etudiant = $etudiant->getLangue();
$liste_formation_etudiant = $etudiant->getFormation();
$liste_XP = $etudiant->getXP();

if ($cv == NULL) {
    $cv = new CV();
}
if ($liste_diplome_etudiant == NULL) {
    $liste_diplome_etudiant = new CV_Diplome();
}
if ($liste_langue_etudiant == NULL) {
    $liste_langue_etudiant = new CV_Langue();
}
if ($liste_formation_etudiant == NULL) {
    $liste_formation_etudiant = new CV_Formation();
}
if ($liste_XP == NULL) {
    $liste_XP = new CV_XP();
}
?>

<title><?php echo $etudiant->getPrenom() . ' ' . $etudiant->getNom(); ?> | | <?php echo $etudiant->getMail(); ?></title>


<div id="body">
    <div id="doc2" class="yui-t7">
        <div id="inner">

            <div id="hd">
                <div class="yui-gc">
                    <div class="yui-u first">
                        <h1><?php echo $etudiant->getPrenom() . ' ' . $etudiant->getNom(); ?></h1>
                        <h2><?php echo $cv->getTitre();?></h2>
                    </div>

                    <div class="yui-u">
                        <div class="contact-info">
                            <h3 style="line-height: 24px;"><a href="mailto:<?php echo $etudiant->getMail(); ?>"><?php echo $etudiant->getMail(); ?></a></h3>
                            <h3 style="line-height: 24px;"><?php echo $etudiant->getTel(); ?></h3>
                            <h3 style="line-height: 16px;"><?php echo $etudiant->getAdresse1(); ?></h3>
                            <?php 
                                if ($etudiant->getAdresse2() != ''){
                                    echo '<h3 style="line-height: 16px;">'.$etudiant->getAdresse2().'</h3>';
                                }
                            ?>
                            <h3 style="line-height: 24px;"><?php echo $etudiant->getCPVille() . ' ' . $etudiant->getNomVille() . ' ' . $etudiant->getPaysVille(); ?></h3>
                            <h3 style="line-height: 24px;">Née le <?php echo $etudiant->getAnniv(); ?></h3>
                            <?php 
                                if ($cv->getIDMobilite()>2){
                                    echo '<h3 style="line-height: 24px;">Mobilité '.$cv->getNomMobilite().'</h3>';
                                }
                                if($etudiant->getIdPermis()<3){
                                     echo '<h3 style="line-height: 24px;">'.$etudiant->getNomPermis().'</h3>'; 
                                }
                            ?>
                            
                        </div><!--// .contact-info -->
                    </div>
                </div><!--// .yui-gc -->
            </div><!--// hd -->

            <div id="bd">
                <div id="yui-main">
                    <div class="yui-b">
                        <div class="yui-gf">

                            <div class="yui-u first">
                                <h2>Expérience(s)</h2>
                            </div><!--// .yui-u -->

                            <div class="yui-u">
                                <?php
                                $nb_xp = count($liste_XP);
                                for ($i = 0; $i < $nb_xp; $i++) {
                                    $XP = $liste_XP[$i];
                                    if ($i != ($nb_xp - 1)) {
                                        echo '<div class="job">';
                                    } else {
                                        echo '<div class="job last">';
                                    }

                                    echo '<h3 style="font-style:italic;line-height: 38px;">' . $XP->getEntreprise() . ' (' . $XP->getNomVille() . ')</h2>';
                                    echo '<h2 style="font-size : 110%; width : 505px; line-height: 18px;"><strong>' . $XP->getTitre() . '</strong></h2>';
                                    echo '<h4>' . $XP->getDebut() . '-' . $XP->getFin() . '</h4>';
                                    echo '<p style="font-family: Georgia;">' . $XP->getDescription() . '</p>';
                                    echo '</div>';
                                }
                                ?>                            
                            </div><!--// .yui-u -->
                        </div><!--// .yui-gf -->


                        <div class="yui-gf last">
                            <div class="yui-u first">
                                <h2>Diplôme(s)</h2>
                            </div>
                            <?php
                            foreach ($liste_diplome_etudiant as $diplome_etudiant) {
                                echo '<div class="yui-u">';
                                echo '<h3>' . $diplome_etudiant->getAnnee() . ' ' . $diplome_etudiant->getLibelle() .'</h3>';
                                if ($diplome_etudiant->getIdMention() != 1){
                                   echo ' mention '. $diplome_etudiant->getNomMention();
                                }
                                echo '</h3>';
                                echo '<h4>' . $diplome_etudiant->getInstitut() . ' ' . $diplome_etudiant->getNomVille() . '</h4>';
                                echo '</div>';
                            }
                            ?>
                        </div><!--// .yui-gf -->

                        <div class="yui-gf last">
                            <div class="yui-u first">
                                <h2>Formation</h2>
                            </div>

                            <?php
                            foreach ($liste_formation_etudiant as $formation_etudiant) {
                                echo '<div class="yui-u">';
                                echo '<h3>' . $formation_etudiant->getDebut() . ' ' . $formation_etudiant->getFin() . ' - ' . $formation_etudiant->getInstitut() . ' - ' . $formation_etudiant->getNomVille() . '</h3>';
                                echo '<h4>' . $formation_etudiant->getAnnee() . '</h4><br>';
                                echo '</div>';
                            }
                            ?>
                        </div><!--// .yui-gf -->
                        <div class="yui-gf ">
                            <div class="yui-u first">
                                <h2>Langue(s)</h2>
                            </div>
                            <?php
                            foreach ($liste_langue_etudiant as $langue_etudiant) {
                                echo '<div class="yui-u">';
                                echo '<h3><strong>' . $langue_etudiant->getNomLangue() . '</strong> ' . $langue_etudiant->getNomNiveau();
                                if ($langue_etudiant->getIdCertif() != 1) {
                                    echo ' ' . $langue_etudiant->getNomCertif();
                                    if ($langue_etudiant->getMaxScoreCertif() != NULL && $langue_etudiant->getScoreCertif() != '') {
                                        echo ' ' . $langue_etudiant->getScoreCertif() . '/' . $langue_etudiant->getMaxScoreCertif();
                                    }
                                }
                                echo '</h3>';
                                echo '</div>';
                            }
                            ?>
                        </div><!--// .yui-gf -->

                        <div class="yui-gf last">
                            <div class="yui-u first">
                                <h2>Loisir(s)</h2>
                            </div>
                            <?php
                            echo '<div class="yui-u">';
                            echo '<h3>'.$cv->getLoisir().'</h3>';
                            echo '</div>';
                            ?>
                        </div><!--// .yui-gf -->


                    </div><!--// .yui-b -->
                </div><!--// yui-main -->
            </div><!--// bd -->



            <div id="ft">
                <p><?php echo $etudiant->getPrenom() . ' ' . $etudiant->getNom(); ?> &mdash; <a href="mailto:<?php echo $etudiant->getMail(); ?>"><?php echo $etudiant->getMail(); ?></a> &mdash; <?php echo $etudiant->getTel(); ?></p>
            </div><!--// footer -->

        </div><!-- // inner -->


    </div><!--// doc -->

</div>
