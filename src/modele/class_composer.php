<?php

class Composer {
    private $db;
    private $insert;
    

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $this->db->prepare(
            "INSERT INTO composer (idCommande, idJeux, qte)
             VALUES (:idCommande, :idJeux, :qte)"
        );
    }

    
    // function pour inserer lidentifiant de chaque commande, l'identifiant de chaque  produit et le a quantite de jeu a la bdd 
    public function insert($idCommande, $idProduit, $qte) {
        $this->insert->bindValue(':idCommande', $idCommande, PDO::PARAM_INT);
        $this->insert->bindValue(':idJeux', $idProduit, PDO::PARAM_INT);
        $this->insert->bindValue(':qte', $qte, PDO::PARAM_INT);

        $result = $this->insert->execute();

        if ($this->insert->errorCode() !== "00000") {
            print_r($this->insert->errorInfo());
        }

        return $result;
    }

    // function pour supprimer  un jeu par son id 
    public function deleteByJeu($idJeux) {
    $stmt = $this->db->prepare("DELETE FROM composer WHERE idJeux = :id");
    $stmt->bindValue(':id', $idJeux, PDO::PARAM_INT);
    return $stmt->execute();
}

}
?>
