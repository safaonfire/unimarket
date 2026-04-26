<?php
session_start();
require_once __DIR__ . '/../../model/CRUD_user.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: /controller/home.php"); exit;
}

$nom     = trim($_POST['nom'] ?? '');
$prenom  = trim($_POST['prenom'] ?? '');
$tel     = trim($_POST['tel'] ?? '');
$adresse = trim($_POST['adresse'] ?? '');
$email   = trim($_POST['email'] ?? '');

if (empty($nom) || empty($prenom) || empty($email)) {
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/controller/home.php') . "?profil_error=1");
    exit;
}

$crud = new CRUD_user();

// Vérifier email unique (sauf le sien)
$existing = $crud->login($email);
if ($existing && $existing['id'] != $_SESSION['user_id']) {
    header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/controller/home.php') . "?profil_error=email");
    exit;
}

// Gérer l'upload de photo
$photo = null;
if (!empty($_POST['delete_photo']) && $_POST['delete_photo'] === '1') {
    // Supprimer la photo et revenir au logo par défaut
    if (!empty($_SESSION['user_photo'])) {
        $old = __DIR__ . '/../../' . $_SESSION['user_photo'];
        if (file_exists($old)) unlink($old);
    }
    $photo = '';
    $_SESSION['user_photo'] = null;
} elseif (!empty($_FILES['photo']['tmp_name'])) {
    $ext     = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'avif'];
    if (in_array($ext, $allowed)) {
        $filename = 'profil_' . $_SESSION['user_id'] . '.' . $ext;
        $dest     = __DIR__ . '/../../uploads/' . $filename;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $dest)) {
            $photo = 'uploads/' . $filename;
            $_SESSION['user_photo'] = $photo;
        }
    }
}

$crud->updateProfil($_SESSION['user_id'], $nom, $prenom, $tel, $adresse, $email, $photo);

$_SESSION['user_nom']    = $nom;
$_SESSION['user_prenom'] = $prenom;
$_SESSION['user_tel']    = $tel;
$_SESSION['user_adresse']= $adresse;
$_SESSION['user_email']  = $email;

header("Location: " . ($_SERVER['HTTP_REFERER'] ?? '/controller/home.php') . "?profil_ok=1");
exit;

