<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: /controller/user/login.php"); exit();
}

require_once __DIR__ . "/../../model/CRUD_produit.php";
$crud = new CRUD_produit();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $produit = $crud->getById((int)$_GET['id']);
    // Vérifier que le produit appartient bien à ce vendeur
    if ($produit && $produit['id_proprietaire'] == $_SESSION['user_id']) {
        $crud->delete((int)$_GET['id']);
    }
}

header("Location: /controller/vendeur/dashboard.php");
exit();
