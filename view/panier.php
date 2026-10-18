<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier — UniMarket</title>
    <link rel="icon" type="image/png" href="/assets/leaf.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background:#f8f9fa; padding-top: 80px; }
        .cart-img { width:75px; height:75px; object-fit:cover; border-radius:8px; flex-shrink:0; }
        .qty-btn { width:30px; height:30px; padding:0; display:flex; align-items:center; justify-content:center; }
        .qty-input { width:45px; text-align:center; border:1px solid #dee2e6; border-radius:6px; font-weight:600; }
    </style>
</head>
<body>

<?php include __DIR__ . '/navbar.php'; ?>

<div class="container py-4" style="max-width:820px;">
    <h4 class="mb-4"><i class="bi bi-cart3 me-2"></i>Mon Panier
        <?php if (!empty($articles)): ?>
            <span class="badge bg-secondary fs-6 ms-1"><?= count($articles) ?></span>
        <?php endif; ?>
    </h4>

    <?php if (empty($articles)): ?>
        <div class="text-center py-5">
            <i class="bi bi-cart-x fs-1 text-muted d-block mb-3"></i>
            <p class="text-muted">Votre panier est vide.</p>
            <a href="/controller/home.php" class="btn btn-dark">Continuer mes achats</a>
        </div>
    <?php else: ?>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-0">
                <?php foreach ($articles as $a): ?>
                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                    <img src="/<?= htmlspecialchars($a['image']) ?>" class="cart-img">

                    <div class="flex-grow-1">
                        <div class="fw-semibold"><?= htmlspecialchars($a['libelle']) ?></div>
                        <div class="text-muted small"><?= $a['prix_final'] ?> TND / unité</div>
                        <?php if ($a['quantite_panier'] > $a['quantite']): ?>
                            <div class="text-danger small mt-1">
                                <i class="bi bi-exclamation-triangle me-1"></i>
                                Stock limité (<?= $a['quantite'] ?> dispo)
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Contrôle quantité + / - -->
                    <div class="d-flex align-items-center gap-1">
                        <form method="POST" action="/controller/panier/index.php">
                            <input type="hidden" name="action" value="diminuer">
                            <input type="hidden" name="id_produit" value="<?= $a['id'] ?>">
                            <button class="btn btn-outline-secondary btn-sm qty-btn" type="submit">
                                <i class="bi bi-dash"></i>
                            </button>
                        </form>

                        <span class="qty-input"><?= $a['quantite_panier'] ?></span>

                        <form method="POST" action="/controller/panier/index.php">
                            <input type="hidden" name="action" value="augmenter">
                            <input type="hidden" name="id_produit" value="<?= $a['id'] ?>">
                            <input type="hidden" name="stock_max" value="<?= $a['quantite'] ?>">
                            <button class="btn btn-outline-secondary btn-sm qty-btn" type="submit"
                                    <?= $a['quantite_panier'] >= $a['quantite'] ? 'disabled' : '' ?>>
                                <i class="bi bi-plus"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Sous-total -->
                    <div class="fw-bold text-nowrap" style="min-width:80px; text-align:right;">
                        <?= number_format($a['sous_total'], 2) ?> TND
                    </div>

                    <!-- Supprimer -->
                    <form method="POST" action="/controller/panier/index.php">
                        <input type="hidden" name="action" value="supprimer">
                        <input type="hidden" name="id_produit" value="<?= $a['id'] ?>">
                        <button class="btn btn-sm text-danger border-0 bg-transparent">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Total + actions -->
        <div class="card border-0 shadow-sm p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small">Total (<?= count($articles) ?> article<?= count($articles) > 1 ? 's' : '' ?>)</div>
                    <div class="fs-4 fw-bold"><?= number_format($total, 2) ?> TND</div>
                </div>
                <form method="POST" action="/controller/panier/index.php">
                    <input type="hidden" name="action" value="vider">
                    <button class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-circle me-1"></i>Vider le panier
                    </button>
                </form>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="/controller/home.php" class="btn btn-outline-dark flex-fill">
                <i class="bi bi-arrow-left me-1"></i>Continuer mes achats
            </a>
            <a href="/controller/panier/checkout.php" class="btn btn-dark flex-fill">
                <i class="bi bi-bag-check me-2"></i>Commander (<?= number_format($total, 2) ?> TND)
            </a>
        </div>

    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>





