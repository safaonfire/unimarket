<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'supadm') {
    header("Location: /controller/user/login.php"); exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /controller/admin/dashboard.php?section=produits"); exit();
}

require_once __DIR__ . '/../../model/CRUD_produit.php';
$crud = new CRUD_produit();

if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $crud->delete((int)$_POST['id']);
}

header("Location: /controller/admin/dashboard.php?section=produits");
exit();
