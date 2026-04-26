<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: /controller/user/login.php"); exit();
}

require_once __DIR__ . "/../../model/CRUD_produit.php";
$crud = new CRUD_produit();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /controller/vendeur/dashboard.php"); exit();
}

$produit = $crud->getById((int)$_GET['id']);

if (!$produit || $produit['id_proprietaire'] != $_SESSION['user_id']) {
    header("Location: /controller/vendeur/dashboard.php"); exit();
}

$categories = $crud->getCategoriesFlat();
$error = '';

if (isset($_POST['ok'])) {
    $libelle     = trim($_POST['libelle']);
    $prix        = (float)$_POST['prix'];
    $quantite    = (int)$_POST['quantite'];
    $description = trim($_POST['description']);
    $promo       = isset($_POST['promo']) ? 1 : 0;
    $handmade    = isset($_POST['handmade']) ? 1 : 0;
    $id_categorie= !empty($_POST['id_categorie']) ? (int)$_POST['id_categorie'] : null;
    $image       = null;

    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        if (!in_array(strtolower($ext), ['jpg','jpeg','png','webp'])) {
            $error = "Format image non supporté.";
        } else {
            $uploadDir = __DIR__ . '/../../uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $filename = 'img_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename);
            $image = 'uploads/' . $filename;
        }
    }

    if (!$error) {
        $prix_promo = ($promo && !empty($_POST['prix_promo'])) ? (float)$_POST['prix_promo'] : null;
        $crud->update($produit['id'], $libelle, $prix, $quantite, $description, $promo, $id_categorie, $image, $prix_promo, $handmade);
        header("Location: /controller/vendeur/dashboard.php"); exit();
    }
}

include __DIR__ . "/../../view/vendeur/edit_produit.php";

