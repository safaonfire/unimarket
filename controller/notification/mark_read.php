<?php
session_start();
require_once __DIR__ . '/../../connexion.php';

if (!isset($_SESSION['user_id'])) { exit; }

$pdo = (new Connexion())->getConnexion();
$pdo->prepare("UPDATE notification SET lu = 1 WHERE id_user = ?")->execute([$_SESSION['user_id']]);

echo json_encode(['success' => true]);

