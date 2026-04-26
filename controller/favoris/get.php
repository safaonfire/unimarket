<?php
session_start();
require_once __DIR__ . '/../../connexion.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$pdo = (new Connexion())->getConnexion();
$stmt = $pdo->prepare("SELECT id_produit FROM favoris WHERE id_user = ?");
$stmt->execute([$_SESSION['user_id']]);
$ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Sync session
$_SESSION['favoris'] = $ids;

echo json_encode($ids);
