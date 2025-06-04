<?php

class Role {
    private $db;
    private $select;

    public function __construct($db) {
        $this->db = $db;
        
        $this->select = $this->db->prepare("SELECT id, libelle FROM role ORDER BY libelle");
    }

    // Récupère tous les rôles depuis la base de données
    public function select() {
        $this->select->execute();

        if ($this->select->errorCode() != '00000') {
            print_r($this->select->errorInfo());
        }

        return $this->select->fetchAll();
    }
}

?>
