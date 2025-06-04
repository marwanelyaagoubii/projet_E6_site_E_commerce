<?php

class Contact {
    private $db;
    private $insert;

    public function __construct($db) {
        $this->db = $db;
        $this->insert = $this->db->prepare(
            "INSERT INTO contact (name, email, message)
             VALUES (:name, :email, :message)"
        );
    }

    public function insert($name, $email, $message) {
        $this->insert->bindValue(':name', $name);
        $this->insert->bindValue(':email', $email);
        $this->insert->bindValue(':message', $message);

        $result = $this->insert->execute();

        return $result;
    }
}
?>
