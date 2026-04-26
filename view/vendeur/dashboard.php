<?php
require_once __DIR__ . '/../../model/CRUD_produit.php';
require_once __DIR__ . '/../../model/CRUD_commande.php';

$crudProduit = new CRUD_produit();
$produits = $crudProduit->getByProprietaire($_SESSION['user_id']);

$crudCommande = new CRUD_commande();
$commandes = $crudCommande->getCommandesByVendeur($_SESSION['user_id']);

$nb_notifications = 0;
foreach ($commandes as $cmd) {
    if ($cmd['statut'] === 'en_attente') {
        $nb_notifications++;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/leaf.png" rel="icon" type="image/png">
<title>Mon Espace Vendeur</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

<style>
html, body {
    height: 100%;
}

body {
    background-color: #f8f9fa;
    min-height: 100vh;
    padding-top: 60px;
}

/* NAVBAR */
.navbar-vendeur {
    background-color: #1a1a1a;
    height: 55px;
    padding: 0 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.navbar-vendeur > a {
    color: white;
    text-decoration: none;
    font-size: 23px;
    font-weight: bold;
}

/* CENTER TEXT */
.navbar-center {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
}

.welcome-text {
    font-size: 16px;
    color: #ccc;
}

/* BUTTON STYLE */
.btn-custom {
    font-size: 16px;
    background-color: white;
    color: black !important;
    border: 1px solid white;
    transition: 0.2s;
}

.btn-custom:hover {
    background-color: black;
    color: white !important;
    border: 1px solid white;
}
.card {
    border-radius: 12px;
    border: none;
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}

.card-img-top {
    height: 220px;
    object-fit: cover;
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
}

/* EMPTY */
.empty-state {
    text-align: center;
    padding: 60px;
}
</style>

</head>

<body>

<?php include __DIR__ . '/_navbar.php'; ?>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0">Mes produits</h5>
        <div class="d-flex gap-2">
            <a href="/controller/vendeur/commandes.php" class="btn btn-outline-dark btn-sm">
                <i class="bi bi-inbox me-1"></i>Commandes à préparer
                <?php if ($nb_notifications > 0): ?>
                    <span class="badge bg-danger ms-1"><?= $nb_notifications ?></span>
                <?php endif; ?>
            </a>
            <a href="/controller/vendeur/add_produit.php" class="btn btn-dark btn-sm">+ Ajouter un produit</a>
        </div>
    </div>

    <?php if (empty($produits)): ?>

        <div class="empty-state">
            <h6>Aucun produit pour le moment</h6>
        </div>

    <?php else: ?>

        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">

            <?php foreach ($produits as $p): ?>
            <div class="col">

                <div class="card h-100">

                    <div class="position-relative">
                        <?php if ($p['promo']): ?>
                            <span class="badge bg-danger position-absolute" style="top:10px; left:10px; z-index:1;">Promo</span>
                        <?php endif; ?>
                        <img src="/<?= htmlspecialchars($p['image']) ?>"
                             class="card-img-top"
                             alt="<?= htmlspecialchars($p['libelle']) ?>">
                    </div>

                    <div class="card-body">

                        <h6><?= htmlspecialchars($p['libelle']) ?></h6>

                        <?php if ($p['promo'] && !empty($p['prix_promo'])): ?>
                            <p class="mb-1">
                                <span style="text-decoration:line-through; color:#aaa; font-size:13px;"><?= $p['prix'] ?> TND</span>
                                <span class="fw-bold ms-1"><?= $p['prix_promo'] ?> TND</span>
                            </p>
                        <?php else: ?>
                            <p class="fw-bold mb-1"><?= $p['prix'] ?> TND</p>
                        <?php endif; ?>

                        <p class="text-muted small">Qté : <?= $p['quantite'] ?></p>

                        <div class="d-flex gap-2 mt-3">
                            <a href="/controller/vendeur/edit_produit.php?id=<?= $p['id'] ?>"
                               class="btn btn-outline-dark btn-sm flex-fill">
                               Modifier
                            </a>

                            <a href="/controller/vendeur/delete_produit.php?id=<?= $p['id'] ?>"
                               class="btn btn-danger btn-sm flex-fill"
                               onclick="return confirm('Supprimer ce produit ?')">
                               Supprimer
                            </a>
                        </div>

                    </div>

                </div>

            </div>
            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>



