<?php
session_start();

require_once __DIR__ . '/../../model/CRUD_commande.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /controller/user/login.php");
    exit;
}

$crud = new CRUD_commande();
$commandes = $crud->getCommandesByVendeur($_SESSION['user_id']);

require_once __DIR__ . '/../../view/vendeur/commandes.php';

