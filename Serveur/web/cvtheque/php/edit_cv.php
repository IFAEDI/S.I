<?php
if (!Utilisateur_connecter('etudiant')) {
    inclure_fichier('', '401', 'php');
    die;
}

inclure_fichier('controleur', 'etudiant.class', 'php');

$etudiant = new Etudiant();
$etudiant = Etudiant::GetEtudiantByID(1);

$liste_permis = Etudiant::GetListePermis();
$liste_statut_marital = Etudiant::GetListeStatutMarital();
$ville_naissance = $etudiant->getVilleNaissance();
$ville  = $etudiant->getVille();
?> 



<div id="accordion">
    <div class="group">
        <h3><a href="#">Informations personnelles</a></h3>
        <div>
            <h4>Informations personnelles décrivant votre état civil</h4><br/>
            <input type="text" name="nom_etudiant" id="nom_etudiant" class="span3" placeholder="Nom" value="<?php echo $etudiant->getNom(); ?>">
            <input type="text" name="prenom_etudiant" id="prenom_etudiant" class="span3" placeholder="Prenom" value="<?php echo $etudiant->getPrenom(); ?>"><br/>
            <input type="text" name="telephone_etudiant" id="telephone_etudiant" class="span3" placeholder="Téléphone" value="<?php echo $etudiant->getTel(); ?>">
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
            <select id="sel_statut_marital" name="sel_statut_marital">
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

            <select id="sel_sexe" name="sel_sexe">
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

            <input type="text" name="adresse1_etudiant" id="adresse1_etudiant" class="span3" placeholder="Adresse 1" value="<?php echo $etudiant->getAdresse1(); ?>">
            <input type="text" name="adresse2_etudiant" id="adresse2_etudiant" class="span3" placeholder="Adresse 2" value="<?php echo $etudiant->getAdresse2(); ?>">
            <input type="text" name="mail_etudiant" id="mail_etudiant" class="span3" placeholder="Adresse Mail" value="<?php echo $etudiant->getMail(); ?>">
            <input type="text" name="nationalite_etudiant" id="nationalite_etudiant" class="span3" placeholder="Nationalité" value="<?php echo $etudiant->getNationalite(); ?>">
            <input type="text" name="ville_etudiant" id="ville_etudiant" class="span3" placeholder="Ville" value="<?php echo $ville['LIBELLE_VILLE']; ?>">
            <input type="text" name="ville_naissance" id="ville_naissance" class="span3" placeholder="Ville naissance" value="<?php echo $ville_naissance['LIBELLE_VILLE']; ?>">
        </div>
    </div>
    <div class="group">
        <h3><a href="#">Expériences professionnelles</a></h3>
        <div>
            <p>Sed non urna. Donec et ante. Phasellus eu ligula. Vestibulum sit amet purus. Vivamus hendrerit, dolor at aliquet laoreet, mauris turpis porttitor velit, faucibus interdum tellus libero ac justo. Vivamus non quam. In suscipit faucibus urna. </p>
        </div>
    </div>
    <div class="group">
        <h3><a href="#">Projets personnels</a></h3>
        <div>
            <p>Sed non urna. Donec et ante. Phasellus eu ligula. Vestibulum sit amet purus. Vivamus hendrerit, dolor at aliquet laoreet, mauris turpis porttitor velit, faucibus interdum tellus libero ac justo. Vivamus non quam. In suscipit faucibus urna. </p>
        </div>
    </div>
    <div class="group">
        <h3><a href="#">Formation</a></h3>
        <div>
            <p>Nam enim risus, molestie et, porta ac, aliquam ac, risus. Quisque lobortis. Phasellus pellentesque purus in massa. Aenean in pede. Phasellus ac libero ac tellus pellentesque semper. Sed ac felis. Sed commodo, magna quis lacinia ornare, quam ante aliquam nisi, eu iaculis leo purus venenatis dui. </p>
            <ul>
                <li>List item one</li>
                <li>List item two</li>
                <li>List item three</li>
            </ul>
        </div>
    </div>
    <div class="group">
        <h3><a href="#">Langues</a></h3>
        <div>
            <p>Cras dictum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aenean lacinia mauris vel est. </p><p>Suspendisse eu nisl. Nullam ut libero. Integer dignissim consequat lectus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. </p>
        </div>
    </div>
    <div class="group">
        <h3><a href="#">Autres</a></h3>
        <div>
            <p>Cras dictum. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Aenean lacinia mauris vel est. </p><p>Suspendisse eu nisl. Nullam ut libero. Integer dignissim consequat lectus. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. </p>
        </div>
    </div>
</div>



<?php
inclure_fichier('cvtheque', 'edit_cv', 'js');
?>