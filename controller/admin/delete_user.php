<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'supadm') {
    header("Location: /controller/user/login.php");
    exit();
}

require_once __DIR__ . "/../../model/CRUD_user.php";
$crud = new CRUD_user();

// Accepte GET et POST pour compatibilité
$id       = $_POST['id']   ?? $_GET['id']   ?? null;
$redirect = $_POST['from'] ?? $_GET['from'] ?? 'vendeurs';

if ($id && is_numeric($id) && (int)$id !== $_SESSION['user_id']) {
    $crud->deleteUser((int)$id);
}

if (!in_array($redirect, ['vendeurs', 'clients'])) {
    $redirect = 'vendeurs';
}

header("Location: /controller/admin/dashboard.php?section=" . $redirect);
exit();
