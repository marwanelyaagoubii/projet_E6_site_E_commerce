<?php

class Type {
    private $db;
    private $selectAll;
    private $insert;
    private $selectById;
    private $update;
    private $delete;


    public function __construct($db) {
        $this->db = $db;
        $this->selectAll = $db->prepare("SELECT id, libelle FROM types ORDER BY libelle ASC");
        $this->insert = $db->prepare("INSERT INTO types (libelle) VALUES (:libelle)");
        $this->selectById = $db->prepare("SELECT id, libelle FROM types WHERE id = :id");
        $this->update = $db->prepare("UPDATE types SET libelle = :libelle WHERE id = :id");
        $this->delete = $db->prepare("DELETE FROM types WHERE id = : id");
    }

    public function insert($libelle) {
        $this->insert->bindValue(':libelle', $libelle);
        return $this->insert->execute();
    }

    public function selectAll() {
        $this->selectAll->execute();
        
        if ($this->selectAll->errorCode() != 0) {
            print_r($this->selectAll->errorInfo());
        }
        return $this->selectAll->fetchAll();
    }

    public function selectById($id) {
        $this->selectById->bindValue(':id', $id);
        $this->selectById->execute();

        if ($this->selectById->errorCode() != 0) {
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }

    public function update($id, $libelle) {
        $this->update->bindValue(':id', $id);
        $this->update->bindValue(':libelle', $libelle);

        $result = $this->update->execute();
        if ($this->update->errorCode() != 0) {
            print_r($this->update->errorInfo());
        }
        return $result;
    }


    public function delete($db) {
        $this->delete->bindValue(':id', $id);

        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
        }
        return $this->delete->fetch();
    }
    
}

?>


