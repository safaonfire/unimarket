<?php
session_start();
require_once __DIR__ . '/../../connexion.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /controller/home.php"); exit;
}

$pdo     = (new Connexion())->getConnexion();
$user_id = $_SESSION['user_id'];

// Vérifier si une demande existe déjà
$stmt = $pdo->prepare("SELECT id, statut FROM demande_vendeur WHERE id_user = ? ORDER BY created_at DESC LIMIT 1");
$stmt->execute([$user_id]);
$existing = $stmt->fetch();

if ($existing && $existing['statut'] === 'en_attente') {
    header("Location: /controller/home.php?demande=already"); exit;
}

$message      = trim($_POST['message'] ?? '');
$nom_complet  = trim($_POST['nom_complet'] ?? '');
$email        = trim($_POST['email'] ?? '');
$telephone    = trim($_POST['telephone'] ?? '');
$type_produits= trim($_POST['type_produits'] ?? '');

$pdo->prepare("INSERT INTO demande_vendeur (id_user, message, nom_complet, email, telephone, type_produits) VALUES (?, ?, ?, ?, ?, ?)")
    ->execute([$user_id, $message, $nom_complet, $email, $telephone, $type_produits]);

header("Location: /controller/home.php?demande=sent"); exit;
