<?php
session_start();
require_once __DIR__ . '/../../model/CRUD_produit.php';
require_once __DIR__ . '/../../model/CRUD_commande.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: /controller/user/login.php?redirect=/controller/panier/voir.php");
    exit;
}

if (empty($_SESSION['panier'])) {
    header("Location: /controller/panier/voir.php");
    exit;
}

$crud    = new CRUD_produit();
$articles = [];
$total   = 0;

foreach ($_SESSION['panier'] as $id => $qte) {
    $p = $crud->getById($id);
    if ($p && $p['id_proprietaire'] != $_SESSION['user_id']) {
        $prix = ($p['promo'] && !empty($p['prix_promo'])) ? $p['prix_promo'] : $p['prix'];
        $p['quantite_panier'] = $qte;
        $p['prix_final']      = $prix;
        $p['sous_total']      = $prix * $qte;
        $total += $p['sous_total'];
        $articles[] = $p;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adresse   = trim($_POST['adresse_livraison'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');

    if (empty($adresse) || empty($telephone)) {
        $error = "Veuillez renseigner votre adresse et téléphone.";
    } else {
        $crudCmd = new CRUD_commande();
        foreach ($articles as $a) {
            if ($a['quantite_panier'] > $a['quantite']) continue; // skip si stock insuffisant
            $crudCmd->ajouterCommande([
                'client_id'        => $_SESSION['user_id'],
                'produit_id'       => $a['id'],
                'quantite'         => $a['quantite_panier'],
                'prix_unitaire'    => $a['prix_final'],
                'adresse_livraison'=> $adresse,
                'telephone'        => $telephone,
                'description'      => trim($_POST['description'] ?? ''),
            ]);
        }
        $_SESSION['panier'] = [];
        // Vider le panier en DB
        require_once __DIR__ . '/../../connexion.php';
        (new Connexion())->getConnexion()->prepare("DELETE FROM panier WHERE id_user = ?")->execute([$_SESSION['user_id']]);
        header("Location: /controller/user/mes_commandes.php?success=1");
        exit;
    }
}

include __DIR__ . '/../../view/commande/checkout.php';

