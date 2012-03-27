<?php
/*
 * @author Loïc Gevrey
 *
 *
 */

if (!Utilisateur_connecter('entreprise')) {
    inclure_fichier('', '401', 'php');
    die;
}

inclure_fichier('controleur', 'etudiant.class', 'php');
inclure_fichier('cvtheque', 'cvtheque', 'js');
?>


<div class="alert alert-success" style="padding: 20px;">
    <table style="width : 100%;">
        <tr>
            <td>
                Quelle année recherchez vous? 
                <select id="annee_voulu" style="width: 120px;">
                    <option value="">Toute</option>
                    <option value="3">3ème année</option>
                    <option value="4">4ème année</option>
                    <option value="5">5ème année</option>
                    <option value="0">Ingénieur</option>
                    <option value="-1">Favoris</option>
                </select> 
                et/ou un mot clef? <input id="mot_clef_voulu"/>
            </td>
            <td style="text-align : right;">
                <a class="btn btn-primary" href="javascript:Rechercher();">Rechercher</a>
            </td>
        </tr>
    </table>
</div>

<table style="width: 100%;">
    <tr valign="top">
        <td id="div_liste_cv" style="width: 220px; max-height: 500px; overflow: auto; display: inline-block;"></td>
        <td id="div_cv"></td>
    </tr>
</table>

</div>
