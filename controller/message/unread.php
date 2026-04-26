<?php
session_start();
require_once __DIR__ . '/../../connexion.php';

if (!isset($_SESSION['user_id'])) { echo json_encode(['count' => 0]); exit; }

$pdo = (new Connexion())->getConnexion();
$user_id = $_SESSION['user_id'];

// Total non lus
$stmt = $pdo->prepare("
    SELECT COUNT(*) AS count
    FROM message m
    JOIN commande c ON m.id_commande = c.id
    JOIN produit p ON c.id_produit = p.id
    WHERE m.lu = 0
      AND m.id_expediteur != ?
      AND (c.id_client = ? OR p.id_proprietaire = ?)
");
$stmt->execute([$user_id, $user_id, $user_id]);
$total = (int)$stmt->fetch()['count'];

// Détail par commande avec dernier message
$stmt2 = $pdo->prepare("
    SELECT m.id_commande,
           COUNT(*) AS nb,
           u.nom AS expediteur_nom,
           u.prenom AS expediteur_prenom,
           (SELECT contenu FROM message m2
            WHERE m2.id_commande = m.id_commande
              AND m2.id_expediteur != ?
              AND m2.lu = 0
            ORDER BY m2.created_at DESC LIMIT 1) AS dernier_message
    FROM message m
    JOIN commande c ON m.id_commande = c.id
    JOIN produit p ON c.id_produit = p.id
    JOIN user u ON m.id_expediteur = u.id
    WHERE m.lu = 0
      AND m.id_expediteur != ?
      AND (c.id_client = ? OR p.id_proprietaire = ?)
    GROUP BY m.id_commande, u.nom, u.prenom
");
$stmt2->execute([$user_id, $user_id, $user_id, $user_id]);
$rows = $stmt2->fetchAll();

$unread_commandes = [];
$counts = [];
$details = [];
foreach ($rows as $r) {
    $unread_commandes[] = (int)$r['id_commande'];
    $counts[$r['id_commande']] = $r['nb'];
    $details[$r['id_commande']] = [
        'nom'     => $r['expediteur_prenom'] . ' ' . $r['expediteur_nom'],
        'message' => $r['dernier_message']
    ];
}

echo json_encode([
    'count'            => $total,
    'unread_commandes' => $unread_commandes,
    'counts'           => $counts,
    'details'          => $details,
]);

