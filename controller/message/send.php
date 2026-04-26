<?php
session_start();
require_once __DIR__ . '/../../connexion.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'non autorisé']); exit;
}

$id_commande = (int)($_POST['id_commande'] ?? 0);
$contenu     = trim($_POST['contenu'] ?? '');

if (!$id_commande || empty($contenu)) {
    echo json_encode(['error' => 'données manquantes']); exit;
}

$pdo = (new Connexion())->getConnexion();

// Vérifier que l'utilisateur est bien client ou vendeur de cette commande
$stmt = $pdo->prepare("
    SELECT c.id_client, p.id_proprietaire
    FROM commande c
    JOIN produit p ON c.id_produit = p.id
    WHERE c.id = ?
");
$stmt->execute([$id_commande]);
$commande = $stmt->fetch();

if (!$commande) { echo json_encode(['error' => 'commande introuvable']); exit; }

$user_id = $_SESSION['user_id'];
if ($user_id != $commande['id_client'] && $user_id != $commande['id_proprietaire']) {
    echo json_encode(['error' => 'accès refusé']); exit;
}

$pdo->prepare("INSERT INTO message (id_commande, id_expediteur, contenu) VALUES (?, ?, ?)")
    ->execute([$id_commande, $user_id, $contenu]);

$id = $pdo->lastInsertId();

echo json_encode([
    'success'    => true,
    'id'         => $id,
    'contenu'    => htmlspecialchars($contenu),
    'expediteur' => htmlspecialchars($_SESSION['user_nom'] . ' ' . ($_SESSION['user_prenom'] ?? '')),
    'moi'        => true,
    'heure'      => date('H:i'),
]);
