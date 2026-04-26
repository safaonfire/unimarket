<?php
session_start();
require_once __DIR__ . '/../../model/CRUD_commande.php';
require_once __DIR__ . '/../../model/CRUD_produit.php';
require_once __DIR__ . '/../../model/CRUD_user.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /controller/home.php");
    exit;
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /controller/user/login.php?redirect=/controller/home.php");
    exit;
}

$crudProduit = new CRUD_produit();
$produit = $crudProduit->getById((int)$_POST['id_produit']);

if (!$produit) {
    echo "<script>alert('Produit introuvable'); window.history.back();</script>";
    exit;
}

if ($produit['id_proprietaire'] == $_SESSION['user_id']) {
    echo "<script>alert('Vous ne pouvez pas commander vos propres produits.'); window.history.back();</script>";
    exit;
}

$quantite = max(1, (int)$_POST['quantite']);

if ($quantite > (int)$produit['quantite']) {
    echo "<script>alert('Quantité demandée dépasse le stock disponible (" . (int)$produit['quantite'] . " disponible(s))'); window.history.back();</script>";
    exit;
}

$adresse   = trim($_POST['adresse_livraison'] ?? '');
$telephone = trim($_POST['telephone'] ?? '');

if (empty($adresse) || empty($telephone)) {
    echo "<script>alert('Veuillez renseigner votre adresse et téléphone de livraison.'); window.history.back();</script>";
    exit;
}

// Prix au moment de la commande
$prix_unitaire = ($produit['promo'] && !empty($produit['prix_promo']))
    ? (float)$produit['prix_promo']
    : (float)$produit['prix'];

$crud = new CRUD_commande();
$crud->ajouterCommande([
    'client_id'        => $_SESSION['user_id'],
    'produit_id'       => $produit['id'],
    'quantite'         => $quantite,
    'prix_unitaire'    => $prix_unitaire,
    'adresse_livraison'=> $adresse,
    'telephone'        => $telephone,
    'description'      => trim($_POST['description'] ?? ''),
]);

header("Location: /controller/user/mes_commandes.php?success=1");
exit;

