<?php
if (!Utilisateur_connecter('etudiant')) {
    inclure_fichier('', '401', 'php');
    die;
}

inclure_fichier('controleur', 'etudiant.class', 'php');


//Récuperation complete du CV de l'étudiant
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


//Récupération des données pour les differente boite de sélection
$liste_permis = Etudiant::GetListePermis();
$liste_statut_marital = Etudiant::GetListeStatutMarital();
$liste_mobilite = CV::GetListeMobilite();
$liste_langue = CV_Langue::GetListeLangue();
$liste_niveau = CV_Langue::GetListeNiveau();
$liste_certif = CV_Langue::GetListeCertif();
$liste_mention = CV_Diplome::GetListeMention();

//Passage des données pour les boites de sélection au js
echo '<script> var liste_langue=$.parseJSON(\'' . json_encode(Adaptation_tableau($liste_langue)) . '\');</script>';
echo '<script> var liste_niveau=$.parseJSON(\'' . json_encode(Adaptation_tableau($liste_niveau)) . '\');</script>';

//Mise en forme de la liste des certifications pour conserver les score max possible
$temp = Array();
for ($i = 0; $i < count($liste_certif); $i++) {
    $temp[$i] = Array();
    $temp[$i]['id'] = $liste_certif[$i]['ID_CERTIF'];
    $temp[$i]['label'] = $liste_certif[$i]['LIBELLE_CERTIF'];
    $temp[$i]['score_max'] = $liste_certif[$i]['MAX_SCORE_CERTIF'];
}
$liste_certif = $temp;

echo '<script> var liste_certif=$.parseJSON(\'' . json_encode($liste_certif) . '\');</script>';
echo '<script> var liste_mention=$.parseJSON(\'' . json_encode(Adaptation_tableau($liste_mention)) . '\');</script>';
echo '<script> var id_etudiant=\'' . $_SESSION['utilisateur']->getId() . '\';</script>';
?> 
<div class="alert " id="div_info">
    <table style="width: 100%;"><tr><td id="text_info"></td><td style="text-align: right;">
    <a href="javascript:Sauvegarder();" class="btn">Sauvegarder</a>
    </td></tr></table>
</div>

<div id="accordion"  class="form-horizontal" style="min-height: 500px;">
    <div class="group">
        <h3><a href="#">Informations personnelles</a></h3>
        <div>
            <legend>Informations personnelles décrivant votre état civil</legend>

            <div class="control-group">
                <label class="control-label">Nom et prenom*</label>
                <div class="controls">
                    <input type="text" id="nom_etudiant" class="span3" placeholder="Nom" value="<?php echo $etudiant->getNom(); ?>">
                    <input type="text" id="prenom_etudiant" class="span3" placeholder="Prenom" value="<?php echo $etudiant->getPrenom(); ?>">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Téléphone</label>
                <div class="controls">
                    <input type="text" id="telephone_etudiant" class="span3" placeholder="Téléphone" value="<?php echo $etudiant->getTel(); ?>">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Permis</label>
                <div class="controls">
                    <select id="sel_permis" name="sel_permis">
                        <?php
                        foreach ($liste_permis as $permis) {
                            if ($permis['ID_PERMIS'] == $etudiant->getIdPermis()) {
                                echo "<option value='" . $permis['ID_PERMIS'] . "' SELECTED>" . $permis['LIBELLE_PERMIS'] . "</option> ";
                            } else {
                                echo "<option value='" . $permis['ID_PERMIS'] . "'>" . $permis['LIBELLE_PERMIS'] . "</option> ";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Statut Marital</label>
                <div class="controls">
                    <select id="sel_statut_marital" >
                        <?php
                        foreach ($liste_statut_marital as $statut_marital) {
                            if ($statut_marital['ID_MARITAL'] == $etudiant->getIdMarital()) {
                                echo "<option value='" . $statut_marital['ID_MARITAL'] . "' SELECTED>" . $statut_marital['LIBELLE_MARITAL'] . "</option> ";
                            } else {
                                echo "<option value='" . $statut_marital['ID_MARITAL'] . "'>" . $statut_marital['LIBELLE_MARITAL'] . "</option> ";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Sexe</label>
                <div class="controls">
                    <select id="sel_sexe">
                        <?php
                        if ($etudiant->getSexe() == 0) {
                            echo "<option value='0' SELECTED>Homme</option> ";
                            echo "<option value='1' >Femme</option> ";
                        } else {
                            echo "<option value='0' >Homme</option> ";
                            echo "<option value='1' SELECTED>Femme</option> ";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Adresse*</label>
                <div class="controls">
                    <input type="text" id="adresse1_etudiant" class="span3" placeholder="Adresse 1" value="<?php echo $etudiant->getAdresse1(); ?>">
                    <input type="text" id="adresse2_etudiant" class="span3" placeholder="Adresse 2" value="<?php echo $etudiant->getAdresse2(); ?>">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Ville*</label>
                <div class="controls">
                    <input type="text" id="ville_etudiant" class="span3" placeholder="Ville" value="<?php echo $etudiant->getNomVille(); ?>">
                    <input type="text" id="cp_etudiant" class="span3" placeholder="CP" value="<?php echo $etudiant->getCPVille(); ?>" style="width : 50px;">
                    <input type="text" id="pays_etudiant" class="span3" placeholder="Pays" value="<?php echo $etudiant->getPaysVille(); ?>">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Nationalité</label>
                <div class="controls">
                    <input type="text" id="nationalite_etudiant" class="span3" placeholder="Nationalité" value="<?php echo $etudiant->getNationalite(); ?>">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Date de naissance*</label>
                <div class="controls">
                    <input type="text" id="anniv_etudiant" class="span3" placeholder="Date de naissance" value="<?php echo $etudiant->getAnniv(); ?>">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Ville de naissance*</label>
                <div class="controls">
                    <input type="text" id="ville_naissance_etudiant" class="span3" placeholder="Ville de naissance" value="<?php echo $etudiant->getNomVilleNaissance(); ?>">
                    <input type="text" id="cp_naissance_etudiant" class="span3" placeholder="CP" value="<?php echo $etudiant->getCPVilleNaissance(); ?>" style="width : 50px;">
                    <input type="text" id="pays_naissance_etudiant" class="span3" placeholder="Pays de naissance" value="<?php echo $etudiant->getPaysVilleNaissance(); ?>">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="email">Mail*</label>
                <div class="controls">
                    <div class="input-prepend">
                        <span class="add-on">@</span><input type="text" id="mail_etudiant" class="span3" placeholder="Adresse Mail" value="<?php echo $etudiant->getMail(); ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="group">
        <h3><a href="#">Expériences professionnelles</a></h3>
        <div id="div_XP">
            <a id="btn_annuler_xp" class="btn" href="javascript:Annuler_XP();" style="display: none;position: absolute; left: 90%;">Annuler</a>
            <div id="div_nouvelle_XP"></div>
            <div id="div_ancienne_XP"></div>
        </div>
    </div>
    <div class="group">
        <h3><a href="#">Diplome(s)</a></h3>
        <div id="div_Formation">
            <a id="btn_annuler_diplome" class="btn" href="javascript:Annuler_diplome();" style="display: none;position: absolute; left: 90%;">Annuler</a>
            <div id="div_nouveau_Diplome"></div>
            <div id="div_ancien_Diplome"></div>
        </div>
    </div>
    <div class="group">
        <h3><a href="#">Formation</a></h3>
        <div id="div_Formation">
            <a id="btn_annuler_formation" class="btn" href="javascript:Annuler_formation();" style="display: none;position: absolute; left: 90%;">Annuler</a>
            <div id="div_nouvelle_Formation"></div>
            <div id="div_ancienne_Formation"></div>
        </div>
    </div>
    <div class="group">
        <h3><a href="#">Langues</a></h3>
        <div id="div_Langues">
            <a id="btn_annuler_langue" class="btn" href="javascript:Annuler_langue();" style="display: none;position: absolute; left: 90%;">Annuler</a>
            <div id="div_nouvelle_langue"></div>
            <div id="div_ancienne_langue"></div>
        </div>
    </div>
    <div class="group">
        <h3><a href="#">Autres</a></h3>
        <div id="div_Autres">
            <div class="control-group">
                <label class="control-label">Titre du CV</label>
                <div class="controls">
                    <input type="text" id="titre_cv" class="span3" placeholder="Titre du CV" value="<?php echo $cv->getTitre() ?>">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Loisir(s)</label>
                <div class="controls">
                    <input type="text" id="loisir_etudiant" class="span3" placeholder="Loisir(s)" value="<?php echo $cv->getLoisir() ?>">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Mobilité</label>
                <div class="controls">
                    <select id="sel_mobilite" >
                        <?php
                        foreach ($liste_mobilite as $mobilite) {
                            if ($mobilite['ID_MOBILITE'] == $cv->getIDMobilite()) {
                                echo "<option value='" . $mobilite['ID_MOBILITE'] . "' SELECTED>" . $mobilite['LIBELLE_MOBILITE'] . "</option> ";
                            } else {
                                echo "<option value='" . $mobilite['ID_MOBILITE'] . "'>" . $mobilite['LIBELLE_MOBILITE'] . "</option> ";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>

        </div>
    </div>
</div>



<?php
inclure_fichier('cvtheque', 'edit_cv', 'js');

echo '<script>';

foreach ($liste_langue_etudiant as $langue_etudiant) {
    echo 'Ajouter_Langue("' . $langue_etudiant->getIdLAngue() . '","' . $langue_etudiant->getIdNiveau() . '","' . $langue_etudiant->getIdCertif() . '","' . $langue_etudiant->getScoreCertif() . '");';
}

foreach ($liste_XP as $XP) {
    echo 'Ajouter_XP("' . $XP->getDebut() . '","' . $XP->getFin() . '","' . $XP->getTitre() . '","' . $XP->getDescription() . '","' . $XP->getEntreprise() . '","' . $XP->getNomVille() . '");';
}

foreach ($liste_formation_etudiant as $formation_etudiant) {
    echo 'Ajouter_Formation("' . $formation_etudiant->getDebut() . '","' . $formation_etudiant->getFin() . '","' . $formation_etudiant->getInstitut() . '","' . $formation_etudiant->getNomVille() . '","' . $formation_etudiant->getAnnee() . '");';
}

foreach ($liste_diplome_etudiant as $diplome_etudiant) {
    echo 'Ajouter_Diplome("' . $diplome_etudiant->getAnnee() . '","' . $diplome_etudiant->getIdMention() . '","' . $diplome_etudiant->getLibelle() . '","' . $diplome_etudiant->getInstitut() . '","' . $diplome_etudiant->getNomVille() . '");';
}

echo '</script>';
?>