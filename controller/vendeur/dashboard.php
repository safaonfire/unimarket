<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: /controller/user/login.php");
    exit();
}

require_once __DIR__ . "/../../model/CRUD_produit.php";
$crud = new CRUD_produit();
$produits = $crud->getByProprietaire($_SESSION['user_id']);

include __DIR__ . "/../../view/vendeur/dashboard.php";

