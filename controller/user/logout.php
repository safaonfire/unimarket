<?php
session_start();
require_once __DIR__ . '/../../connexion.php';

// Sauvegarder le panier en DB avant déconnexion
if (!empty($_SESSION['user_id']) && !empty($_SESSION['panier'])) {
    $pdo = (new Connexion())->getConnexion();
    $pdo->prepare("DELETE FROM panier WHERE id_user = ?")->execute([$_SESSION['user_id']]);
    $stmt = $pdo->prepare("INSERT INTO panier (id_user, id_produit, quantite) VALUES (?, ?, ?)");
    foreach ($_SESSION['panier'] as $id_produit => $qte) {
        $stmt->execute([$_SESSION['user_id'], $id_produit, $qte]);
    }
}

session_destroy();
header("Location: ../../view/unimarket.php");
exit();
