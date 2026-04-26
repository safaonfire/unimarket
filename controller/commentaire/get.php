<?php
session_start();
require_once __DIR__ . '/../../connexion.php';

$id_produit = (int)($_GET['id_produit'] ?? 0);
if (!$id_produit) { echo json_encode([]); exit; }

$pdo = (new Connexion())->getConnexion();
$stmt = $pdo->prepare("
    SELECT c.id, c.contenu, c.created_at, u.nom, u.prenom, u.photo,
           c.id_user = ? AS est_moi
    FROM commentaire c
    JOIN user u ON c.id_user = u.id
    WHERE c.id_produit = ?
    ORDER BY c.created_at DESC
");
$stmt->execute([$_SESSION['user_id'] ?? 0, $id_produit]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(array_map(fn($r) => [
    'id'       => $r['id'],
    'nom'      => htmlspecialchars($r['prenom'] . ' ' . $r['nom']),
    'photo'    => $r['photo'] ? '/' . $r['photo'] : '/assets/leaf.png',
    'contenu'  => htmlspecialchars($r['contenu']),
    'date'     => date('d/m/Y à H:i', strtotime($r['created_at'])),
    'est_moi'  => (bool)$r['est_moi'],
], $rows));
