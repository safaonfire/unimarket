<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'supadm') {
    header("Location: /controller/user/login.php");
    exit();
}

require_once __DIR__ . "/../../model/CRUD_produit.php";
$crud = new CRUD_produit();

$categories = $crud->getCategoriesFlat();
$vendeurs   = $crud->getVendeurs();
$error      = '';

if (isset($_POST['ok'])) {
    $libelle        = trim($_POST['libelle']);
    $prix           = (float)$_POST['prix'];
    $quantite       = (int)$_POST['quantite'];
    $description    = trim($_POST['description']);
    $promo          = isset($_POST['promo']) ? 1 : 0;
    $id_proprietaire= (int)$_POST['id_proprietaire'];
    $id_categorie   = !empty($_POST['id_categorie']) ? (int)$_POST['id_categorie'] : null;
    $image          = '';

    // Upload image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $ext     = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $allowed = ['jpg','jpeg','png','webp'];
        if (!in_array(strtolower($ext), $allowed)) {
            $error = "Format image non supporté.";
        } else {
            $uploadDir = __DIR__ . '/../../uploads/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $filename = 'img_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename);
            $image = 'uploads/' . $filename;
        }
    } else {
        $error = "Veuillez choisir une image.";
    }

    if (!$error) {
        $crud->add($libelle, $prix, $quantite, $image, $description, $promo, $id_proprietaire, $id_categorie);
        header("Location: /controller/admin/dashboard.php?section=produits");
        exit();
    }
}

include __DIR__ . "/../../view/admin/add_produit.php";

