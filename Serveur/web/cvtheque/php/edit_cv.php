<?php
if (!Utilisateur_connecter('etudiant')) {
    inclure_fichier('', '401', 'php');
    die;
}

inclure_fichier('controleur', 'etudiant.class', 'php');


//Récuperation complete du CV de l'étudiant
$etudiant = new Etudiant();
$etudiant = Etudiant::GetEtudiantByID(1);
$cv = $etudiant->getCV();
$liste_diplome_etudiant = $etudiant->getDiplome();
$liste_langue_etudiant = $etudiant->getLangue();
$liste_formation_etudiant = $etudiant->getFormation();
$liste_XP = $etudiant->getXP();


//Récupération des données pour les differente boite de sélection
$liste_permis = Etudiant::GetListePermis();
$liste_statut_marital = Etudiant::GetListeStatutMarital();
$liste_langue = CV_Langue::GetListeLangue();
$liste_niveau = CV_Langue::GetListeNiveau();
$liste_certif = CV_Langue::GetListeCertif();
        
        
//Passage des données pour les boites de sélection au js
echo '<script> var liste_langue=$.parseJSON(\'' . json_encode(Adaptation_tableau($liste_langue)) . '\');</script>';
echo '<script> var liste_niveau=$.parseJSON(\'' . json_encode(Adaptation_tableau($liste_niveau)) . '\');</script>';
echo '<script> var liste_certif=$.parseJSON(\'' . json_encode(Adaptation_tableau($liste_certif)) . '\');</script>';





//A finir
$ville_naissance = $etudiant->getVilleNaissance();
$ville = $etudiant->getVille();
?> 

<div id="accordion"  class="form-horizontal" style="min-height: 500px;">
    <div class="group">
        <h3><a href="#">Informations personnelles</a></h3>
        <div>
            <legend>Informations personnelles décrivant votre état civil</legend>

            <div class="control-group" id="control_nom">
                <label class="control-label">Nom et prenom</label>
                <div class="controls">
                    <input type="text" id="nom_etudiant" class="span3" placeholder="Nom" value="<?php echo $etudiant->getNom(); ?>">
                    <input type="text" id="prenom_etudiant" class="span3" placeholder="Prenom" value="<?php echo $etudiant->getPrenom(); ?>"><br/>
                </div>
            </div>

            <div class="control-group" id="control_nom">
                <label class="control-label">Téléphone</label>
                <div class="controls">
                    <input type="text" id="telephone_etudiant" class="span3" placeholder="Téléphone" value="<?php echo $etudiant->getTel(); ?>">
                </div>
            </div>

            <div class="control-group" id="control_nom">
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

            <div class="control-group" id="control_nom">
                <label class="control-label">Statut Marital</label>
                <div class="controls">
                    <select id="sel_statut_marital" >
                        <?php
                        foreach ($liste_statuts_maritals as $statut_marital) {
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

            <div class="control-group" id="control_nom">
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

            <div class="control-group" id="control_nom">
                <label class="control-label">Adresse</label>
                <div class="controls">
                    <input type="text" id="adresse1_etudiant" class="span3" placeholder="Adresse 1" value="<?php echo $etudiant->getAdresse1(); ?>">
                    <input type="text" id="adresse2_etudiant" class="span3" placeholder="Adresse 2" value="<?php echo $etudiant->getAdresse2(); ?>">
                </div>
            </div>

            <div class="control-group" id="control_nom">
                <label class="control-label">Ville</label>
                <div class="controls">
                    <input type="text" id="ville_etudiant" class="span3" placeholder="Ville" value="<?php echo $ville['LIBELLE_VILLE']; ?>">
                </div>
            </div>

            <div class="control-group" id="control_nom">
                <label class="control-label">Nationalité</label>
                <div class="controls">
                    <input type="text" id="nationalite_etudiant" class="span3" placeholder="Nationalité" value="<?php echo $etudiant->getNationalite(); ?>">
                </div>
            </div>

            <div class="control-group" id="control_nom">
                <label class="control-label">Ville de naissance</label>
                <div class="controls">
                    <input type="text" id="ville_naissance" class="span3" placeholder="Ville naissance" value="<?php echo $ville_naissance['LIBELLE_VILLE']; ?>">
                </div>
            </div>

            <div class="control-group" id="control_email">
                <label class="control-label" for="email">Mail</label>
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

        </div>
    </div>
    <div class="group">
        <h3><a href="#">Projets personnels</a></h3>
        <div id="div_ProjetPersonnels">

        </div>
    </div>
    <div class="group">
        <h3><a href="#">Formation</a></h3>
        <div id="div_Formation">

        </div>
    </div>
    <div class="group">
        <h3><a href="#">Langues</a></h3>
        <div id="div_Langues">

        </div>
    </div>
    <div class="group">
        <h3><a href="#">Autres</a></h3>
        <div id="div_Autres">

        </div>
    </div>
</div>



<?php
inclure_fichier('cvtheque', 'edit_cv', 'js');

echo '<script>';

foreach ($liste_langue_etudiant as $langue_etudiant){
    echo 'Ajouter_Langue('.$langue_etudiant->getIdLAngue().','.$langue_etudiant->getIdNiveau().','.$langue_etudiant->getIdCertif().','.$langue_etudiant->getScoreCertif().');';
}

echo '</script>';





?>