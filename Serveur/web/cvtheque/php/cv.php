<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<?php
require_once dirname(__FILE__) . '/../../commun/php/base.inc.php';
session_start();
inclure_fichier('controleur', 'etudiant.class', 'php');
if (isset($_GET['id_etudiant']) && Utilisateur_connecter('entreprise')) {
    $id_etudiant = $_GET['id_etudiant'];
    Etudiant::MettreEnVu($id_etudiant, $_SESSION['utilisateur']->getId(), 2);
} elseif (Utilisateur_connecter('etudiant')) {
    $id_etudiant = $_SESSION['utilisateur']->getId();
} else {
    inclure_fichier('', '401', 'php');
    die();
}

//inclure_fichier('cvtheque', 'cv', 'css');


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
<link rel="stylesheet" media="screen"  type="text/css" href="/cvtheque/css/cv_screen.css"/>
<link rel="stylesheet" media="print" type="text/css" href="/cvtheque/css/cv_print.css"/>

<!DOCTYPE html>
<meta charset="utf-8" />

<div id="body">
    <div id="doc2" class="yui-t7">
        <div id="inner">

            <div id="hd">
                <div class="yui-gc">
                    <div class="yui-u first">
                        <h1><?php echo Protection_XSS($etudiant->getPrenom()) . ' ' . Protection_XSS($etudiant->getNom()); ?></h1>
                        <h2><?php echo $cv->getTitre(); ?></h2>
                    </div>

                    <div class="yui-u">
                        <div class="contact-info">
                            <h3 style="line-height: 23px;"><a href="mailto:<?php echo Protection_XSS($etudiant->getMail()); ?>"><?php echo Protection_XSS($etudiant->getMail()); ?></a></h3>
                            <h3 style="line-height: 23px;"><?php echo Protection_XSS($etudiant->getTel()); ?></h3>
                            <h3 style="line-height: 16px;"><?php echo Protection_XSS($etudiant->getAdresse1()); ?></h3>
                            <?php
                            if ($etudiant->getAdresse2() != '') {
                                echo '<h3 style="line-height: 16px;">' . Protection_XSS($etudiant->getAdresse2()) . '</h3>';
                            }
                            ?>
                            <h3 style="line-height: 23px;"><?php echo Protection_XSS($etudiant->getCPVille()) . ' ' . Protection_XSS($etudiant->getNomVille()) . ' ' . Protection_XSS($etudiant->getPaysVille()); ?></h3>
                            <?php
                            if ($etudiant->getSexe() == 0) {
                                $ne = "Né le ";
                            } else {
                                $ne = "Née le ";
                            }
                            ?>
                            <h3 style="line-height: 23px;"><?php echo $ne . Protection_XSS($etudiant->getAnniv()); ?></h3>
                            <?php
                            if ($cv->getIDMobilite() > 2) {
                                echo '<h3 style="line-height: 23px;">Mobilité ' . Protection_XSS($cv->getNomMobilite()) . '</h3>';
                            }
                            if ($etudiant->getIdPermis() < 3) {
                                echo '<h3 style="line-height: 23px;">' . Protection_XSS($etudiant->getNomPermis()) . '</h3>';
                            }
                            ?>
                            <h3 style="line-height: 23px;"><?php echo Protection_XSS($etudiant->getNomMarital()); ?></h3>
                        </div><!--// .contact-info -->
                    </div>
                </div><!--// .yui-gc -->
            </div><!--// hd -->

            <div id="bd">
                <div id="yui-main">
                    <div class="yui-b">


                        <?php if (count($liste_XP) > 0) { ?>  
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

                                        echo '<h3 style="font-style:italic;line-height: 38px;">' . Protection_XSS($XP->getEntreprise()) . ' (' . Protection_XSS($XP->getNomVille()) . ')</h3>';
                                        echo '<h2 style="font-size : 110%; width : 505px; line-height: 18px;"><strong>' . Protection_XSS($XP->getTitre()) . '</strong></h2>';
                                        echo '<h4>' . Protection_XSS($XP->getDebut()) . '-' . Protection_XSS($XP->getFin()) . '</h4>';
                                        echo '<p style="font-family: Georgia;">' . nl2br(Protection_XSS($XP->getDescription())) . '</p>';
                                        echo '</div>';
                                    }
                                    ?>                            
                                </div><!--// .yui-u -->
                            </div><!--// .yui-gf -->
                        <?php } ?>


                        <?php if (count($liste_diplome_etudiant) > 0) { ?>       
                            <div class="yui-gf last">
                                <div class="yui-u first">
                                    <h2>Diplôme(s)</h2>
                                </div>
                                <?php
                                foreach ($liste_diplome_etudiant as $diplome_etudiant) {
                                    echo '<div class="yui-u">';
                                    echo '<h3>' . Protection_XSS($diplome_etudiant->getAnnee()) . ' ' . Protection_XSS($diplome_etudiant->getLibelle());
                                    if ($diplome_etudiant->getIdMention() != 1) {
                                        echo ' mention ' . Protection_XSS($diplome_etudiant->getNomMention());
                                    }
                                    echo '</h3>';
                                    echo '<h4>' . Protection_XSS($diplome_etudiant->getInstitut()) . ' ' . Protection_XSS($diplome_etudiant->getNomVille()) . '</h4>';
                                    echo '</div>';
                                }
                                ?>
                            </div><!--// .yui-gf -->
                        <?php } ?>

                        <?php if (count($liste_formation_etudiant) > 0) { ?> 
                            <div class="yui-gf last">
                                <div class="yui-u first">
                                    <h2>Formation</h2>
                                </div>

                                <?php
                                foreach ($liste_formation_etudiant as $formation_etudiant) {
                                    echo '<div class="yui-u">';
                                    echo '<h3>' . Protection_XSS($formation_etudiant->getDebut()) . ' ' . Protection_XSS($formation_etudiant->getFin()) . ' - ' . Protection_XSS($formation_etudiant->getInstitut()) . ' - ' . Protection_XSS($formation_etudiant->getNomVille()) . '</h3>';
                                    echo '<h4>' . Protection_XSS($formation_etudiant->getAnnee()) . '</h4><br>';
                                    echo '</div>';
                                }
                                ?>
                            </div><!--// .yui-gf -->
                        <?php } ?>


                        <?php if (count($liste_langue_etudiant) > 0) { ?> 
                            <div class="yui-gf ">
                                <div class="yui-u first">
                                    <h2>Langue(s)</h2>
                                </div>
                                <?php
                                foreach ($liste_langue_etudiant as $langue_etudiant) {
                                    echo '<div class="yui-u">';
                                    echo '<h3><strong>' . Protection_XSS($langue_etudiant->getNomLangue()) . '</strong> ' . Protection_XSS($langue_etudiant->getNomNiveau());
                                    if ($langue_etudiant->getIdCertif() != 1) {
                                        echo ' ' . Protection_XSS($langue_etudiant->getNomCertif());
                                        if ($langue_etudiant->getMaxScoreCertif() != NULL && $langue_etudiant->getScoreCertif() != '') {
                                            echo ' ' . Protection_XSS($langue_etudiant->getScoreCertif()) . '/' . Protection_XSS($langue_etudiant->getMaxScoreCertif());
                                        }
                                    }
                                    echo '</h3>';
                                    echo '</div>';
                                }
                                ?>
                                <br/><br/><br/><br/><br/>
                            </div><!--// .yui-gf -->
                        <?php } ?>

                        <?php if ($cv->getLoisir() != '') { ?> 
                            <div class="yui-gf last">
                                <div class="yui-u first">
                                    <h2>Loisir(s)</h2>
                                </div>
                                <?php
                                echo '<div class="yui-u">';
                                echo '<h3>' . Protection_XSS($cv->getLoisir()) . '</h3>';
                                echo '</div>';
                                ?>
                            </div><!--// .yui-gf -->
                        <?php } ?>

                    </div><!--// .yui-b -->
                </div><!--// yui-main -->
            </div><!--// bd -->



            <div id="ft">
                <p><?php echo Protection_XSS($etudiant->getPrenom()) . ' ' . Protection_XSS($etudiant->getNom()); ?> &mdash; <a href="mailto:<?php echo Protection_XSS($etudiant->getMail()); ?>"><?php echo Protection_XSS($etudiant->getMail()); ?></a> &mdash; <?php echo Protection_XSS($etudiant->getTel()); ?></p>
            </div><!--// footer -->

        </div><!-- // inner -->


    </div><!--// doc -->

</div>
