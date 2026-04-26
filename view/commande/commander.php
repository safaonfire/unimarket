<?php
session_start();
require_once __DIR__ . '/../../model/CRUD_produit.php';

if (!isset($_GET['id'])) { header("Location: /controller/home.php"); exit; }

$crud    = new CRUD_produit();
$produit = $crud->getById((int)$_GET['id']);

if (!$produit) { header("Location: /controller/home.php"); exit; }

if (!isset($_SESSION['user_id'])) {
    header("Location: /controller/user/login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit;
}

if ($produit['quantite'] <= 0) {
    header("Location: /controller/produit/detail.php?id=" . $produit['id'] . "&rupture=1");
    exit;
}

$prix_final = ($produit['promo'] && !empty($produit['prix_promo'])) ? $produit['prix_promo'] : $produit['prix'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commander — <?= htmlspecialchars($produit['libelle']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background:#f8f9fa; padding-top: 80px; }
        .order-box { max-width: 620px; margin: auto; }
        .product-thumb { width:80px; height:80px; object-fit:cover; border-radius:8px; }
    </style>
</head>
<body>

<?php include __DIR__ . '/../navbar.php'; ?>

<div class="container order-box py-4">
    <h4 class="mb-4"><i class="bi bi-bag-check me-2"></i>Passer une commande</h4>

    <!-- Résumé produit -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body d-flex gap-3 align-items-center">
            <img src="/<?= htmlspecialchars($produit['image']) ?>" class="product-thumb">
            <div class="flex-grow-1">
                <div class="fw-semibold"><?= htmlspecialchars($produit['libelle']) ?></div>
                <div class="text-muted small"><?= htmlspecialchars(mb_strimwidth($produit['description'], 0, 80, '...')) ?></div>
            </div>
            <div class="fw-bold text-nowrap"><?= $prix_final ?> TND</div>
        </div>
    </div>

    <form method="POST" action="/controller/commande/store_commande.php" class="card border-0 shadow-sm p-4">
        <input type="hidden" name="id_produit" value="<?= $produit['id'] ?>">

        <!-- Quantité -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Quantité</label>
            <input type="number" name="quantite" id="quantite" class="form-control"
                   min="1" max="<?= $produit['quantite'] ?>" value="1" required>
            <div class="form-text"><?= $produit['quantite'] ?> disponible(s)</div>
        </div>

        <!-- Infos rendez-vous -->
        <div class="alert alert-info d-flex gap-2 align-items-start py-2 px-3" style="font-size:0.88rem;">
            <i class="bi bi-info-circle-fill mt-1"></i>
            <span>La remise des commandes se fait <strong>à l'intérieur de l'université</strong>. Indiquez un lieu et un créneau pour convenir avec le vendeur.</span>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold"><i class="bi bi-geo-alt me-1"></i>Lieu de rendez-vous sur le campus</label>
            <select name="adresse_livraison" class="form-select" required>
                <option value="">-- Choisir un lieu --</option>
                <option value="Entrée principale">Entrée principale</option>
                <option value="Bibliothèque">Bibliothèque</option>
                <option value="Cafétéria">Cafétéria</option>
                <option value="Département Informatique">Département Informatique</option>
                <option value="Département Gestion">Département Gestion</option>
                <option value="Département Électrique">Département Électrique</option>
                <option value="Département Mécanique">Département Mécanique</option>
                <option value="Département Génie Civil">Département Génie Civil</option>
                <option value="Salle d'attente département">Salle d'attente département</option>
                <option value="Parking principal">Parking principal</option>
                <option value="À convenir avec le vendeur">À convenir avec le vendeur</option>
            </select>
        </div>

        <!-- Téléphone -->
        <div class="mb-3">
            <label class="form-label fw-semibold"><i class="bi bi-telephone me-1"></i>Votre téléphone</label>
            <input type="text" name="telephone" class="form-control"
                   value="<?= htmlspecialchars($_SESSION['user_tel'] ?? '') ?>"
                   placeholder="Ex: 55 123 456" required>
        </div>

        <!-- Note optionnelle -->
        <div class="mb-3">
            <label class="form-label fw-semibold">Note pour le vendeur <span class="text-muted fw-normal">(optionnel)</span></label>
            <textarea name="description" class="form-control" rows="2"
                      placeholder="Couleur, taille, instructions spéciales..."></textarea>
        </div>

        <!-- Récapitulatif prix -->
        <div class="rounded p-3 mb-3" style="background:#f0f0f0;">
            <div class="d-flex justify-content-between">
                <span class="text-muted">Prix unitaire</span>
                <span><?= $prix_final ?> TND</span>
            </div>
            <hr class="my-2">
            <div class="d-flex justify-content-between fw-bold">
                <span>Total</span>
                <span id="prixTotal"><?= $prix_final ?> TND</span>
            </div>
        </div>

        <button type="submit" class="btn btn-dark w-100">
            <i class="bi bi-check-lg me-2"></i>Confirmer la commande
        </button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const prixUnitaire = <?= (float)$prix_final ?>;
document.getElementById('quantite').addEventListener('input', function() {
    const qte = Math.max(1, parseInt(this.value) || 1);
    document.getElementById('prixTotal').textContent = (prixUnitaire * qte).toFixed(2) + ' TND';
});
</script>
</body>
</html>





