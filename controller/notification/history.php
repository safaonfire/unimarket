<?php
session_start();
require_once __DIR__ . '/../../connexion.php';

if (!isset($_SESSION['user_id'])) { echo json_encode([]); exit; }

$pdo = (new Connexion())->getConnexion();

$stmt = $pdo->prepare("SELECT id, contenu, lu, created_at FROM notification WHERE id_user = ? ORDER BY created_at DESC LIMIT 50");
$stmt->execute([$_SESSION['user_id']]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($items);

