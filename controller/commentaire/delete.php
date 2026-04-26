<?php
session_start();
require_once __DIR__ . '/../../connexion.php';

if (!isset($_SESSION['user_id'])) { echo json_encode(['error' => 'non autorisé']); exit; }

$id = (int)($_POST['id'] ?? 0);
if (!$id) { echo json_encode(['error' => 'id manquant']); exit; }

$pdo = (new Connexion())->getConnexion();
$pdo->prepare("DELETE FROM commentaire WHERE id = ? AND id_user = ?")
    ->execute([$id, $_SESSION['user_id']]);

echo json_encode(['success' => true]);
