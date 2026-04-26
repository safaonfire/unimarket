<?php
session_start();

require_once __DIR__ . '/../../connexion.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    echo "Accès refusé";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);

    if (empty($nom)) {
        echo "Nom invalide";
        exit;
    }

    $db = new Connexion();
    $pdo = $db->getConnexion();

    $stmt = $pdo->prepare("INSERT INTO categorie (nom) VALUES (:nom)");
    $stmt->execute(['nom' => $nom]);

    echo "success";
}

