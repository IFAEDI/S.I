<?php
if (!Utilisateur_connecter('etudiant')) {
    inclure_fichier('', '401', 'php');
    die;
}

inclure_fichier('controleur', 'etudiant.class', 'php');

$etudiant = new Etudiant();
$etudiant = Etudiant::GetEtudiant(1);
?>



<div id="accordion">
    <div class="group">
        <h3><a href="#">Informations personnelles</a></h3>
        <div>
            <h4>Informations personnelles décrivant votre état civil</h4><br/>
            <input type="text" name="nom_etudiant" id="nom_etudiant" class="span3" placeholder="Nom" value="<?php echo $etudiant->getNom(); ?>">
            <input type="text" name="prenom_etudiant" id="prenom_etudiant" class="span3" placeholder="Prenom" value="<?php echo $etudiant->getPrenom(); ?>"><br/>
             <input type="text" name="telephone_etudiant" id="telephone_etudiant" class="span3" placeholder="Téléphone" value="<?php echo $etudiant->getTel(); ?>">
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
inclure_fichier('cvtheque', 'cv', 'js');
?>