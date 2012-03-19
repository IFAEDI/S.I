<?php

require_once dirname(__FILE__) . '/base.inc.php';
inclure_fichier('global', 'bd.inc', 'php');

class Utilisateur {

    //****************  Attributs  ******************//
    private $id;
    private $nom;
    private $prenom;
    private $annee;
    private $mail;

    //****************  Fonctions statiques  ******************//
    public static function GetUtilisateur($_id) {
        if (is_numeric($_id)) {
            return BD::Prepare('SELECT * FROM Utilisateur WHERE id = :id', array($_id), BD::RECUPERER_UNE_LIGNE,PDO::FETCH_CLASS,__CLASS__);
        }
        return NULL;
    }

    //****************  Fonctions  ******************//
    public function __construct() {
        
    }

    public function estConnecte() {
        //TODO
        return true;
    }

    public function estEtudiant() {
        //TODO
        return true;
    }

    public function estEntreprise() {
        //TODO
        return true;
    }

    //****************  Getters & Setters  ******************//
    public function getId() {
        return $this->id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getAnnee() {
        return $this->annee;
    }

    public function getMail() {
        return $this->mail;
    }

}

?>
