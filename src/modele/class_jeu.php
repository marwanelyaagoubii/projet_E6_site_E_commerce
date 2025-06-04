<?php

class Jeu {
    private $db;
    private $insert;
    private $selectAll;
    private $delete;
    private $update;
    private $selectIn;


    public function __construct($db) {
        $this->db = $db;

        $this->insert = $this->db->prepare("INSERT INTO jeux (titre, plateforme, prix, quantites, genre, image, photo) VALUES (:titre, :plateforme, :prix, :quantites, :genre, :image, :photo)");

        $this->selectAll = $this->db->prepare("SELECT id, titre, plateforme, prix, quantites, genre, image, photo FROM jeux ORDER BY titre ASC");

        $this->delete = $this->db->prepare("DELETE FROM jeux WHERE id = :id");

        $this->update = $this->db->prepare("UPDATE jeux SET titre = :titre, plateforme = :plateforme, prix = :prix, quantites = :quantites, genre = :genre, image = :photo WHERE id = :id");

        $this->selectIn = $this->db->prepare("SELECT id, designation, description, prix, image, idType FROM jeux WHERE FIND_IN_SET(id, :ids)");
    }


    // function pour inserer les champ d'un jeu ds la bdd 
public function insert($titre, $plateforme, $prix, $quantites, $genre, $image, $photo) {
    $this->insert->bindValue(':titre', $titre);
    $this->insert->bindValue(':plateforme', $plateforme);
    $this->insert->bindValue(':prix', $prix);
    $this->insert->bindValue(':quantites', $quantites);
    $this->insert->bindValue(':genre', $genre);
    $this->insert->bindValue(':image', $image); 
    $this->insert->bindValue(':photo', $photo); 
    return $this->insert->execute();
}


 // function pr afficher les jeux de la bdd sur le site ( sur la page jeu.php)
    public function selectAll() {
        $this->selectAll->execute();

        if ($this->selectAll->errorCode() != 0) {
            print_r($this->selectAll->errorInfo());
        }

        return $this->selectAll->fetchAll();
    }


// function pr supprimer les jeux de la bdd grace a l'id
    public function delete($id) {
        $this->delete->bindValue(':id', $id);

        if ($this->delete->errorCode() != 0) {
           print_r($this->delete->errorInfo());
        }

        return $this->delete->execute();
    }



    //funcion pour modifier un jeu
public function update($id, $titre, $plateforme, $prix, $quantites, $genre, $image, $photo) {
    $sql = "UPDATE jeux 
            SET titre = :titre, 
                plateforme = :plateforme, 
                prix = :prix,
                quantites = :quantites, 
                genre = :genre, 
                image = :image,
                photo = :photo
            WHERE id = :id";
    
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':titre', $titre);
    $stmt->bindValue(':plateforme', $plateforme);
    $stmt->bindValue(':prix', $prix);
    $stmt->bindValue(':quantites', $quantites);
    $stmt->bindValue(':genre', $genre);
    $stmt->bindValue(':image', $image);
    $stmt->bindValue(':photo', $photo);

    return $stmt->execute();
}


//function pour selectionner un jeu par son id (unique)

    public function selectById($id) {
    $sql = "SELECT * FROM jeux WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->errorCode() != 0) {
        print_r($stmt->errorInfo());
    }

    return $stmt->fetch();
}

// function pour récupér plusieurs jeux à partir d’une liste d’identifiants (afficher les jeux ds le panier par exemple)
public function selectIn($ids) {
    $implose = implode(',', $ids);

$this->selectIn = $this->db->prepare(
    "SELECT 
        id, 
        titre, 
        image, 
        photo,
        prix, 
        genre, 
        plateforme, 
        quantites 
     FROM jeux 
     WHERE FIND_IN_SET(id, :ids)"
);


    $this->selectIn->bindParam(':ids', $implose);
    $this->selectIn->execute();

    if ($this->selectIn->errorCode() != 0) {
        print_r($this->selectIn->errorInfo());
    }

return $this->selectIn->fetchAll(PDO::FETCH_ASSOC) ?: [];
}

// function pr mettre à jour la quantité disponible pour un jeu donné (après un achat par exemple)

public function updateQuantite($id, $quantite) {
    $sql = "UPDATE jeux SET quantites = :quantites WHERE id = :id";
    $query = $this->db->prepare($sql);
    $query->bindValue(':quantites', $quantite, PDO::PARAM_INT);
    $query->bindValue(':id', $id, PDO::PARAM_INT);
    return $query->execute();
}

}

?>
