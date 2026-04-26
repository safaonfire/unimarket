<?php
session_start();
require_once __DIR__ . '/../../connexion.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'supadm') {
    header("Location: /controller/user/login.php"); exit;
}

$id     = (int)($_GET['id'] ?? 0);
$action = $_GET['action'] ?? '';

if (!$id || !in_array($action, ['accepter', 'refuser'])) {
    header("Location: /controller/admin/dashboard.php?section=demandes"); exit;
}

$pdo = (new Connexion())->getConnexion();

// Récupérer la demande
$stmt = $pdo->prepare("SELECT id_user FROM demande_vendeur WHERE id = ?");
$stmt->execute([$id]);
$demande = $stmt->fetch();

if (!$demande) {
    header("Location: /controller/admin/dashboard.php?section=demandes"); exit;
}

if ($action === 'accepter') {
    $pdo->prepare("UPDATE demande_vendeur SET statut = 'acceptee' WHERE id = ?")->execute([$id]);
    $pdo->prepare("UPDATE user SET role = 'admin' WHERE id = ?")->execute([$demande['id_user']]);
    // Notifier l'utilisateur
    $pdo->prepare("INSERT INTO notification (id_user, contenu) VALUES (?, ?)")
        ->execute([$demande['id_user'], '✅ Votre demande pour devenir vendeur a été acceptée ! Vous pouvez maintenant accéder à votre espace vendeur.']);
} else {
    $pdo->prepare("UPDATE demande_vendeur SET statut = 'refusee' WHERE id = ?")->execute([$id]);
    $pdo->prepare("INSERT INTO notification (id_user, contenu) VALUES (?, ?)")
        ->execute([$demande['id_user'], '❌ Votre demande pour devenir vendeur a été refusée.']);
}

header("Location: /controller/admin/dashboard.php?section=demandes"); exit;

