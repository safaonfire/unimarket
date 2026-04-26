<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'supadm') {
    header("Location: /controller/user/login.php");
    exit();
}

require_once __DIR__ . "/../../model/CRUD_user.php";
require_once __DIR__ . "/../../model/CRUD_produit.php";
$crud        = new CRUD_user();
$crud_produit = new CRUD_produit();

$clients  = $crud->getAllClients();
$vendeurs = $crud->getAllVendeurs();
$produits = $crud_produit->getAll();

// Demandes vendeur
require_once __DIR__ . '/../../connexion.php';
$pdo = (new Connexion())->getConnexion();
$stmt = $pdo->prepare("
    SELECT d.*, u.nom, u.prenom, u.email
    FROM demande_vendeur d
    JOIN user u ON d.id_user = u.id
    WHERE d.statut = 'en_attente'
    ORDER BY d.created_at DESC
");
$stmt->execute();
$demandes = $stmt->fetchAll();

$section = isset($_GET['section']) ? $_GET['section'] : 'produits';

include __DIR__ . "/../../view/admin/dashboard.php";

