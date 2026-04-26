<?php
session_start();
require_once __DIR__ . '/../../connexion.php';

$id_produit = (int)($_POST['id_produit'] ?? 0);
if (!$id_produit) exit;

$pdo = (new Connexion())->getConnexion();

if (!isset($_SESSION['favoris'])) {
    $_SESSION['favoris'] = [];
}

if (in_array($id_produit, $_SESSION['favoris'])) {
    // Retirer le like
    $_SESSION['favoris'] = array_values(array_filter($_SESSION['favoris'], fn($id) => $id !== $id_produit));
    $status = 'removed';
    $pdo->prepare("UPDATE produit SET nb_likes = GREATEST(0, nb_likes - 1) WHERE id = ?")->execute([$id_produit]);

    // Retirer de la table favoris si connecté
    if (isset($_SESSION['user_id'])) {
        $pdo->prepare("DELETE FROM favoris WHERE id_user = ? AND id_produit = ?")->execute([$_SESSION['user_id'], $id_produit]);
    }
} else {
    // Ajouter le like
    $_SESSION['favoris'][] = $id_produit;
    $status = 'added';
    $pdo->prepare("UPDATE produit SET nb_likes = nb_likes + 1 WHERE id = ?")->execute([$id_produit]);

    // Ajouter dans la table favoris si connecté
    if (isset($_SESSION['user_id'])) {
        $pdo->prepare("INSERT IGNORE INTO favoris (id_user, id_produit) VALUES (?, ?)")->execute([$_SESSION['user_id'], $id_produit]);

        // Notifier le vendeur
        $stmt = $pdo->prepare("SELECT p.libelle, p.id_proprietaire FROM produit p WHERE p.id = ?");
        $stmt->execute([$id_produit]);
        $produit = $stmt->fetch();

        if ($produit && $produit['id_proprietaire'] != $_SESSION['user_id']) {
            $client = $_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom'];
            $contenu = "❤️ " . htmlspecialchars($client) . " a aimé votre produit \"" . htmlspecialchars($produit['libelle']) . "\".";
            $pdo->prepare("INSERT INTO notification (id_user, contenu) VALUES (?, ?)")
                ->execute([$produit['id_proprietaire'], $contenu]);
        }
    }
}

// Récupérer le vrai total depuis la DB
$stmt = $pdo->prepare("SELECT nb_likes FROM produit WHERE id = ?");
$stmt->execute([$id_produit]);
$row = $stmt->fetch();

echo json_encode(['status' => $status, 'nb_likes' => (int)$row['nb_likes']]);
