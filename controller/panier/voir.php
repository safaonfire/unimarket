<?php
session_start();
require_once __DIR__ . '/../../model/CRUD_produit.php';

if (!isset($_SESSION['panier'])) $_SESSION['panier'] = [];

$crud = new CRUD_produit();
$articles = [];
$total = 0;

foreach ($_SESSION['panier'] as $id_produit => $quantite) {
    $p = $crud->getById($id_produit);
    if ($p) {
        $prix_final = ($p['promo'] && !empty($p['prix_promo'])) ? $p['prix_promo'] : $p['prix'];
        $p['quantite_panier'] = $quantite;
        $p['prix_final'] = $prix_final;
        $p['sous_total'] = $prix_final * $quantite;
        $total += $p['sous_total'];
        $articles[] = $p;
    }
}

include __DIR__ . '/../../view/panier.php';

