<?php
session_start();

require_once __DIR__ . '/../../connexion.php';

// BUG FIX #10: rediriger proprement au lieu d'utiliser die()
if (!isset($_SESSION['user_id'])) {
    header("Location: /controller/user/login.php?redirect=/controller/user/mes_commandes.php");
    exit;
}

$conn = new Connexion();
$pdo = $conn->getConnexion();

$client_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT c.*, p.libelle, p.prix, p.image
    FROM commande c
    JOIN produit p ON c.id_produit = p.id
    WHERE c.id_client = :id_client
    ORDER BY c.date_commande DESC
");

$stmt->execute([
    'id_client' => $client_id
]);

$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// send data to view
require_once __DIR__ . '/../../view/mes_commandes.php';

