<?php

class Utilisateur {
    private $db;
    private $connect;
    private $select;
    private $selectById;
    private $update;
    private $delete;

    public function __construct($db) {
        $this->db = $db;
        $this->connect = $this->db->prepare("SELECT id, email, idRole, password FROM utilisateur WHERE email = :email");
        $this->select = $db->prepare("SELECT u.id, email, idRole, nom, prenom, r.libelle as libellerole FROM utilisateur u, role r WHERE u.idRole = r.id ORDER BY nom");
        $this->selectById = $db->prepare("SELECT id, email, nom, prenom, idRole FROM utilisateur WHERE id = :id");
        $this->update = $db->prepare("UPDATE utilisateur SET nom = :nom, prenom = :prenom, idRole = :role WHERE id = :id");
        $this->delete = $db->prepare("DELETE FROM utilisateur WHERE id = :id");
    }

    public function insert($email, $mdp, $role, $nom, $prenom, $valider, $idgenere) {
        $sql = "INSERT INTO utilisateur (email, password, idRole, nom, prenom, valider, idgenere)
                VALUES (:email, :password, :role, :nom, :prenom, :valider, :idgenere)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $mdp);
        $stmt->bindValue(':role', $role);
        $stmt->bindValue(':nom', $nom);
        $stmt->bindValue(':prenom', $prenom);
        $stmt->bindValue(':valider', $valider, PDO::PARAM_BOOL);
        $stmt->bindValue(':idgenere', $idgenere);
        return $stmt->execute();
    }

    public function connect($email) {
        $this->connect->execute([':email' => $email]);
        if ($this->connect->errorCode() != 0) {
            print_r($this->connect->errorInfo());
        }
        return $this->connect->fetch();
    }

    public function emailExiste($email) {
        $sql = "SELECT COUNT(*) FROM utilisateur WHERE email = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetchColumn() > 0;
    }

    public function select() {
        $this->select->execute();
        if ($this->select->errorCode() != 0) {
            print_r($this->select->errorInfo());
        }
        return $this->select->fetchAll();
    }

    public function selectById($id) {
        $this->selectById->execute([':id' => $id]);
        if ($this->selectById->errorCode() != 0) {
            print_r($this->selectById->errorInfo());
        }
        return $this->selectById->fetch();
    }

    public function update($id, $email, $nom, $prenom, $role) {
        $sql = "UPDATE utilisateur SET email = :email, nom = :nom, prenom = :prenom, idRole = :role WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':nom', $nom);
        $stmt->bindValue(':prenom', $prenom);
        $stmt->bindValue(':role', $role);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function updateMdp($id, $password) {
        $query = "UPDATE utilisateur SET password = :password WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $this->delete->execute([':id' => $id]);
        if ($this->delete->errorCode() != 0) {
            print_r($this->delete->errorInfo());
            return false;
        }
        return true;
    }

    public function selectByEmail($email) {
        $sql = "SELECT * FROM utilisateur WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->rowCount() > 0 ? $stmt->fetch() : null;
    }

    public function validerCompte($email) {
        $sql = "UPDATE utilisateur SET valider = 1 WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        return $stmt->execute();
    }

    public function setResetToken($email, $token, $time) {
        $sql = "UPDATE utilisateur SET reset_token = :token, reset_time = :time WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':token', $token);
        $stmt->bindValue(':time', $time);
        $stmt->bindValue(':email', $email);
        return $stmt->execute();
    }

    public function resetPassword($email, $newHash) {
        $sql = "UPDATE utilisateur SET password = :password, reset_token = NULL, reset_time = NULL WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':password', $newHash);
        $stmt->bindValue(':email', $email);
        return $stmt->execute();
    }
}
?>
