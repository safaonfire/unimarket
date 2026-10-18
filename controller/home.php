<?php
session_start();
require_once __DIR__ . "/../model/CRUD_produit.php";

$crud_produit = new CRUD_produit();

if (!empty($_GET['categorie']) && is_numeric($_GET['categorie'])) {
    $produits = $crud_produit->getByCategorie((int)$_GET['categorie']);
    $categorie_active = (int)$_GET['categorie'];
} else {
    $produits = $crud_produit->getAll();
    $categorie_active = null;
}

include __DIR__ . "/../view/unimarket.php";

