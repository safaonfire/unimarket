<?php
session_start();
require_once __DIR__ . '/../../model/CRUD_commande.php';
require_once __DIR__ . '/../../connexion.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['user_role'] ?? '', ['admin', 'supadm'])) {
    header("Location: /controller/user/login.php"); exit;
}

$id     = (int)($_GET['id']     ?? $_POST['id']     ?? 0);
$statut = trim($_GET['statut']  ?? $_POST['statut'] ?? '');

if (!$id || !in_array($statut, ['confirmee', 'annulee'])) {
    header("Location: /controller/vendeur/commandes.php"); exit;
}

$pdo = (new Connexion())->getConnexion();

// Si on annule une commande confirmée, remettre le stock
if ($statut === 'annulee') {
    $stmt = $pdo->prepare("SELECT c.quantite, c.id_produit FROM commande c WHERE c.id = ? AND c.statut = 'confirmee'");
    $stmt->execute([$id]);
    $cmd = $stmt->fetch();
    if ($cmd) {
        $pdo->prepare("UPDATE produit SET quantite = quantite + ? WHERE id = ?")
            ->execute([$cmd['quantite'], $cmd['id_produit']]);
    }
}

$crud = new CRUD_commande();
$crud->updateStatut($id, $statut);

// Récupérer le client et le produit pour la notification
$stmt = $pdo->prepare("
    SELECT c.id_client, p.libelle
    FROM commande c
    JOIN produit p ON c.id_produit = p.id
    WHERE c.id = ?
");
$stmt->execute([$id]);
$info = $stmt->fetch();

if ($info) {
    $label   = $statut === 'confirmee' ? '✅ confirmée' : '❌ refusée';
    $contenu = "Votre commande \"" . $info['libelle'] . "\" a été $label par le vendeur.";
    $pdo->prepare("INSERT INTO notification (id_user, contenu) VALUES (?, ?)")
        ->execute([$info['id_client'], $contenu]);
}

header("Location: /controller/vendeur/commandes.php");
exit;
