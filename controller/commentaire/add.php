<?php
session_start();
require_once __DIR__ . '/../../connexion.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'non autorisé']); exit;
}

$id_produit = (int)($_POST['id_produit'] ?? 0);
$contenu    = trim($_POST['contenu'] ?? '');

if (!$id_produit || empty($contenu)) {
    echo json_encode(['error' => 'données manquantes']); exit;
}

$pdo = (new Connexion())->getConnexion();
$pdo->prepare("INSERT INTO commentaire (id_produit, id_user, contenu) VALUES (?, ?, ?)")
    ->execute([$id_produit, $_SESSION['user_id'], $contenu]);

// Notifier le vendeur
$stmt = $pdo->prepare("SELECT p.libelle, p.id_proprietaire FROM produit p WHERE p.id = ?");
$stmt->execute([$id_produit]);
$produit = $stmt->fetch();

if ($produit && $produit['id_proprietaire'] != $_SESSION['user_id']) {
    $client  = htmlspecialchars($_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']);
    $libelle = htmlspecialchars($produit['libelle']);
    $extrait = mb_strimwidth(htmlspecialchars($contenu), 0, 50, '...');
    $pdo->prepare("INSERT INTO notification (id_user, contenu) VALUES (?, ?)")
        ->execute([$produit['id_proprietaire'], "💬 $client a commenté votre produit \"$libelle\" : \"$extrait\""]);
}

echo json_encode([
    'success'  => true,
    'nom'      => htmlspecialchars($_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']),
    'contenu'  => htmlspecialchars($contenu),
    'date'     => date('d/m/Y à H:i'),
]);
