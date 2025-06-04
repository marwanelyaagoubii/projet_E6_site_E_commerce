<?php

class Commande {
    private $db;
    private $insert;
    private $selectByDateCli;

    public function __construct($db) {
        $this->db = $db;

        $this->insert = $this->db->prepare(
            "INSERT INTO commande (montant, etat, date, idUtilisateur)
             VALUES (:montant, :etat, :date, :idUtilisateur)"
        );

        $this->selectByDateCli = $this->db->prepare(
            "SELECT id FROM commande 
             WHERE date = :date AND idUtilisateur = :idUtilisateur"
        );
    }


    //function relie les info de la commande a la bdd 

    public function insert($montant, $etat, $date, $idUtilisateur) {
        $this->insert->bindValue(':montant', $montant);
        $this->insert->bindValue(':etat', $etat);
        $this->insert->bindValue(':date', $date);
        $this->insert->bindValue(':idUtilisateur', $idUtilisateur);

        $result = $this->insert->execute();

        if ($this->insert->errorCode() !== "00000") {
            print_r($this->insert->errorInfo());
        }

        return $result;
    }


    // function qui permet de selectionner la date et l'id dun utilisateur vers la bdd
    
    public function selectByDateCli($date, $idUtilisateur) {
        $this->selectByDateCli->bindValue(':date', $date);
        $this->selectByDateCli->bindValue(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
        $this->selectByDateCli->execute();

        if ($this->selectByDateCli->errorCode() !== "00000") {
            print_r($this->selectByDateCli->errorInfo());
        }

        return $this->selectByDateCli->fetch();
    }
}
?>
