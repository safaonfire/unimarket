<?php
session_start();
require_once __DIR__ . '/../../connexion.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /controller/home.php"); exit;
}

$pdo = (new Connexion())->getConnexion();
$pdo->prepare("DELETE FROM demande_vendeur WHERE id_user = ?")->execute([$_SESSION['user_id']]);

header("Location: /controller/home.php"); exit;

