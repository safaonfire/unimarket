<?php
session_start();
require_once __DIR__ . '/../../model/CRUD_produit.php';

$favoris = [];
$crud = new CRUD_produit();

if (isset($_SESSION['user_id'])) {
    // BUG FIX #9: charger les favoris depuis la DB pour les utilisateurs connectés
    // La session peut être désynchronisée si l'utilisateur a aimé/retiré depuis un autre appareil
    require_once __DIR__ . '/../../connexion.php';
    $pdo = (new Connexion())->getConnexion();
    $stmt = $pdo->prepare("SELECT id_produit FROM favoris WHERE id_user = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);
    // Synchroniser la session
    $_SESSION['favoris'] = $ids;
    foreach ($ids as $id) {
        $p = $crud->getById($id);
        if ($p) $favoris[] = $p;
    }
} elseif (!empty($_SESSION['favoris'])) {
    // Utilisateur non connecté : utiliser la session
    foreach ($_SESSION['favoris'] as $id) {
        $p = $crud->getById($id);
        if ($p) $favoris[] = $p;
    }
}

include __DIR__ . '/../../view/favoris.php';

