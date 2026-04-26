<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/leaf.png" rel="icon" type="image/png">
    <title>Mes J'aime</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background:#f8f9fa; padding-top: 80px; }
        .card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .card:hover { transform: translateY(-6px); box-shadow: 0 12px 25px rgba(0,0,0,0.12); }
    </style>
</head>
<body>

<?php include __DIR__ . '/navbar.php'; ?>

<div class="container py-5">
    <h4 class="mb-4">
        <i class="bi bi-heart-fill text-danger me-2"></i>Mes J'aime
    </h4>

    <?php if (empty($favoris)): ?>
        <div class="alert alert-light border text-center py-5">
            <i class="bi bi-heart fs-1 text-muted d-block mb-3"></i>
            <p class="text-muted mb-3">Vous n'avez pas encore aimé de produits.</p>
            <a href="/controller/home.php" class="btn btn-dark btn-sm">Découvrir les produits</a>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($favoris as $p): ?>
            <div class="col">
                <?php include __DIR__ . '/partials/produit_card.php'; ?>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>var isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;</script>
<script src="/assets/produit.js"></script>
</body>
</html>



