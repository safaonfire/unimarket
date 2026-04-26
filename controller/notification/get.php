<?php
session_start();
require_once __DIR__ . '/../../connexion.php';

if (!isset($_SESSION['user_id'])) { echo json_encode(['count' => 0, 'items' => []]); exit; }

$pdo = (new Connexion())->getConnexion();

$stmt = $pdo->prepare("SELECT id, contenu, created_at FROM notification WHERE id_user = ? AND lu = 0 ORDER BY created_at DESC LIMIT 20");
$stmt->execute([$_SESSION['user_id']]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Don't mark as read here — only mark read when user explicitly opens the panel
echo json_encode(['count' => count($items), 'items' => $items]);
