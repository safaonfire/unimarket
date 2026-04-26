<?php
session_start();
require_once __DIR__ . '/../../connexion.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]); exit;
}

$id_commande = (int)($_GET['id_commande'] ?? 0);
if (!$id_commande) { echo json_encode([]); exit; }

$pdo = (new Connexion())->getConnexion();

// Vérifier accès
$stmt = $pdo->prepare("
    SELECT c.id_client, p.id_proprietaire
    FROM commande c JOIN produit p ON c.id_produit = p.id
    WHERE c.id = ?
");
$stmt->execute([$id_commande]);
$commande = $stmt->fetch();

if (!$commande) { echo json_encode([]); exit; }

$user_id = $_SESSION['user_id'];
if ($user_id != $commande['id_client'] && $user_id != $commande['id_proprietaire']) {
    echo json_encode([]); exit;
}

// Marquer comme lus les messages reçus
$pdo->prepare("UPDATE message SET lu = 1 WHERE id_commande = ? AND id_expediteur != ?")
    ->execute([$id_commande, $user_id]);

// Récupérer les messages
$stmt = $pdo->prepare("
    SELECT m.*, u.nom, u.prenom
    FROM message m
    JOIN user u ON m.id_expediteur = u.id
    WHERE m.id_commande = ?
    ORDER BY m.created_at ASC
");
$stmt->execute([$id_commande]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

$result = array_map(function($m) use ($user_id) {
    return [
        'id'         => $m['id'],
        'contenu'    => htmlspecialchars($m['contenu']),
        'expediteur' => htmlspecialchars($m['nom'] . ' ' . $m['prenom']),
        'moi'        => $m['id_expediteur'] == $user_id,
        'heure'      => date('H:i', strtotime($m['created_at'])),
        'date'       => date('d/m/Y', strtotime($m['created_at'])),
    ];
}, $messages);

echo json_encode($result);
