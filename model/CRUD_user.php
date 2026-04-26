<?php
require_once "user.php";
require_once __DIR__ . '/../connexion.php';

class CRUD_user {
    private $pdo;

    function __construct() {
        $con = new Connexion();
        $this->pdo = $con->getConnexion();
    }

    // Ajouter un utilisateur
    function add(User $user) {
        $sql = "INSERT INTO user (id, nom, prenom, tel, adresse, email, mot_de_passe, role) 
                VALUES (NULL, ?, ?, ?, ?, ?, ?, 'client')";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            $user->getNom(),
            $user->getPrenom(),
            $user->getTel(),
            $user->getAdresse(),
            $user->getEmail(),
            $user->getMot_de_passe()
        ]);
    }

    public function emailExists($email) {
        $sql = "SELECT id FROM user WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->rowCount() > 0; 
    }

    public function login($email) {
        $sql = "SELECT * FROM user WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllClients() {
        $sql = "SELECT id, nom, prenom, tel, adresse, email FROM user WHERE role = 'client'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllVendeurs() {
        $sql = "SELECT id, nom, prenom, tel, adresse, email FROM user WHERE role = 'admin'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteUser($id) {
        $sql = "DELETE FROM user WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfil($id, $nom, $prenom, $tel, $adresse, $email, $photo = null) {
        if ($photo !== null) {
            // $photo peut être '' (suppression) ou un chemin (nouvelle photo)
            $stmt = $this->pdo->prepare("UPDATE user SET nom=?, prenom=?, tel=?, adresse=?, email=?, photo=? WHERE id=?");
            return $stmt->execute([$nom, $prenom, $tel, $adresse, $email, $photo ?: null, $id]);
        }
        $stmt = $this->pdo->prepare("UPDATE user SET nom=?, prenom=?, tel=?, adresse=?, email=? WHERE id=?");
        return $stmt->execute([$nom, $prenom, $tel, $adresse, $email, $id]);
    }
}
?>

