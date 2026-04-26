<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'supadm') {
    header("Location: /controller/user/login.php");
    exit();
}

require_once __DIR__ . "/../../model/CRUD_user.php";
$crud = new CRUD_user();

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $crud->deleteUser((int)$_GET['id']);
}

$redirect = isset($_GET['from']) ? $_GET['from'] : 'vendeurs';
header("Location: /controller/admin/dashboard.php?section=" . $redirect);
exit();
