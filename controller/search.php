<?php
require_once __DIR__ . '/../model/CRUD_produit.php';

$crud = new CRUD_produit();

if (isset($_GET['search'])) {
    $mot = trim($_GET['search']);

    if ($mot === '') {
        exit;
    }

    $produits = $crud->search($mot);

    if ($produits) {
        foreach ($produits as $p) {
            echo '<a href="/controller/produit/detail.php?id=' . $p['id'] . '" 
                     style="display:flex; align-items:center; gap:10px; padding:10px 14px; 
                            border-bottom:1px solid #f0f0f0; text-decoration:none; color:#1a1a1a;"
                     onmouseover="this.style.background=\'#f8f9fa\'" 
                     onmouseout="this.style.background=\'white\'">';
            echo '<img src="/' . htmlspecialchars($p['image']) . '" 
                       style="width:40px; height:40px; object-fit:cover; border-radius:6px;">';
            echo '<div>';
            echo '<div style="font-weight:600;">' . htmlspecialchars($p['libelle']) . '</div>';
            echo '<div style="font-size:12px; color:#888;">' . $p['prix'] . ' TND · ' . htmlspecialchars($p['vendeur_prenom'] . ' ' . $p['vendeur_nom']) . '</div>';
            echo '</div>';
            echo '</a>';
        }
    } else {
        echo '<div style="padding:12px 14px; color:#888;">Aucun résultat pour "' . htmlspecialchars($mot) . '"</div>';
    }
}
?>
