<?php
session_start();
require_once __DIR__ . "/../../model/CRUD_produit.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /controller/home.php");
    exit();
}

$crud = new CRUD_produit();
$produit = $crud->getById((int)$_GET['id']);

if (!$produit) {
    header("Location: /controller/home.php");
    exit();
}

include __DIR__ . "/../../view/produit/detail.php";

