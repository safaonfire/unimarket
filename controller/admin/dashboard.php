<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'supadm') {
    header("Location: /controller/user/login.php");
    exit();
}

require_once __DIR__ . "/../../model/CRUD_user.php";
require_once __DIR__ . "/../../model/CRUD_produit.php";
$crud         = new CRUD_user();
$crud_produit = new CRUD_produit();

$clients  = $crud->getAllClients();
$vendeurs = $crud->getAllVendeurs();
$produits = $crud_produit->getAll();

require_once __DIR__ . '/../../connexion.php';
$pdo = (new Connexion())->getConnexion();

// Demandes vendeur en attente
$stmt = $pdo->prepare("
    SELECT d.*, u.nom, u.prenom, u.email
    FROM demande_vendeur d
    JOIN user u ON d.id_user = u.id
    WHERE d.statut = 'en_attente'
    ORDER BY d.created_at DESC
");
$stmt->execute();
$demandes = $stmt->fetchAll();

// ── STATS ───────────────────────────────────────────────────────────────────

$total_clients   = count($clients);
$total_vendeurs  = count($vendeurs);
$total_produits  = count($produits);

$stmt = $pdo->query("SELECT COUNT(*) FROM commande"); 
$total_commandes = (int)$stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM commande WHERE statut = 'en_attente'");
$commandes_attente = (int)$stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM commande WHERE statut = 'confirmee'");
$commandes_confirmees = (int)$stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM commande WHERE statut = 'annulee'");
$commandes_annulees = (int)$stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM produit WHERE promo = 1");
$total_promos = (int)$stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM demande_vendeur WHERE statut = 'en_attente'");
$total_demandes = (int)$stmt->fetchColumn();

$stmt = $pdo->query("SELECT COALESCE(SUM(quantite * prix_unitaire),0) FROM commande WHERE statut = 'confirmee'");
$chiffre_affaires = (float)$stmt->fetchColumn();

// Commandes par statut (donut)
$commandes_par_statut = [
    ['statut' => 'en_attente', 'nb' => $commandes_attente],
    ['statut' => 'confirmee',  'nb' => $commandes_confirmees],
    ['statut' => 'annulee',    'nb' => $commandes_annulees],
];

// Commandes des 7 derniers jours (line chart)
try {
    $stmt = $pdo->query("
        SELECT DATE_FORMAT(date_commande, '%d/%m') AS jour, COUNT(*) AS nb
        FROM commande
        WHERE date_commande >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        GROUP BY DATE(date_commande), jour
        ORDER BY DATE(date_commande) ASC
    ");
    $commandes_7j = $stmt->fetchAll();
} catch (Exception $e) {
    $commandes_7j = [];
}

// Produits par catégorie (pie chart)
$stmt = $pdo->query("
    SELECT COALESCE(c.nom, 'Sans catégorie') AS categorie, COUNT(*) AS nb
    FROM produit p
    LEFT JOIN categorie c ON p.id_categorie = c.id
    GROUP BY categorie
    ORDER BY nb DESC
    LIMIT 5
");
$produits_par_categorie = $stmt->fetchAll();

// Top 5 produits les plus commandés (bar chart)
$stmt = $pdo->query("
    SELECT p.libelle, SUM(c.quantite) AS total_cmd
    FROM commande c
    JOIN produit p ON c.id_produit = p.id
    GROUP BY p.id, p.libelle
    ORDER BY total_cmd DESC
    LIMIT 5
");
$top_produits = $stmt->fetchAll();

// Catégories pour la navbar
$categories = $crud_produit->getCategoriesTree();

$section = $_GET['section'] ?? 'stats';
$search  = trim($_GET['search'] ?? '');

// Filtrer produits si recherche
if ($search !== '') {
    $produits = array_filter($produits, fn($p) =>
        stripos($p['libelle'], $search) !== false ||
        stripos($p['vendeur_nom'] . ' ' . $p['vendeur_prenom'], $search) !== false
    );
}

// Filtrer demandes si recherche
$demandes_filtrees = $demandes;
if ($search !== '' && $section === 'demandes') {
    $demandes_filtrees = array_filter($demandes, fn($d) =>
        stripos($d['nom'] . ' ' . $d['prenom'], $search) !== false ||
        stripos($d['email'] ?? '', $search) !== false
    );
}

include __DIR__ . "/../../view/admin/dashboard.php";
