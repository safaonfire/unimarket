<?php
session_start();
require_once __DIR__ . '/../../model/CRUD_produit.php';

$favoris = [];
if (!empty($_SESSION['favoris'])) {
    $crud = new CRUD_produit();
    foreach ($_SESSION['favoris'] as $id) {
        $p = $crud->getById($id);
        if ($p) $favoris[] = $p;
    }
}

include __DIR__ . '/../../view/favoris.php';

