<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finaliser la commande</title>
    <link rel="icon" type="image/png" href="/assets/leaf.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background:#f8f9fa; padding-top: 80px; }
        .cart-thumb { width:55px; height:55px; object-fit:cover; border-radius:6px; }
    </style>
</head>
<body>

<?php include __DIR__ . '/../navbar.php'; ?>

<div class="container py-4" style="max-width:700px;">
    <h4 class="mb-4"><i class="bi bi-bag-check me-2"></i>Finaliser la commande</h4>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Récapitulatif panier -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">Récapitulatif</div>
        <div class="card-body p-0">
            <?php foreach ($articles as $a): ?>
            <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                <img src="/<?= htmlspecialchars($a['image']) ?>" class="cart-thumb">
                <div class="flex-grow-1">
                    <div class="fw-semibold small"><?= htmlspecialchars($a['libelle']) ?></div>
                    <div class="text-muted small"><?= $a['prix_final'] ?> TND × <?= $a['quantite_panier'] ?></div>
                    <?php if ($a['quantite_panier'] > $a['quantite']): ?>
                        <div class="text-danger small"><i class="bi bi-exclamation-triangle me-1"></i>Stock insuffisant (<?= $a['quantite'] ?> dispo)</div>
                    <?php endif; ?>
                </div>
                <div class="fw-bold"><?= number_format($a['sous_total'], 2) ?> TND</div>
            </div>
            <?php endforeach; ?>
            <div class="d-flex justify-content-between fw-bold p-3">
                <span>Total</span>
                <span><?= number_format($total, 2) ?> TND</span>
            </div>
        </div>
    </div>

    <!-- Formulaire rendez-vous -->
    <form method="POST" class="card border-0 shadow-sm p-4">
        <h6 class="mb-1"><i class="bi bi-pin-map me-2"></i>Lieu de récupération</h6>
        <p class="text-muted small mb-3">La remise se fait <strong>à l'intérieur de l'université</strong>. Choisissez un lieu de rendez-vous avec le vendeur.</p>

        <div class="mb-3">
            <label class="form-label">Lieu de rendez-vous sur le campus *</label>
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
                <option value="Parking principal">Parking principal</option>
                <option value="À convenir avec le vendeur">À convenir avec le vendeur</option>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Votre téléphone *</label>
            <input type="text" name="telephone" class="form-control"
                   value="<?= htmlspecialchars($_SESSION['user_tel'] ?? '') ?>"
                   placeholder="Ex: 55 123 456" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Note pour les vendeurs <span class="text-muted">(optionnel)</span></label>
            <textarea name="description" class="form-control" rows="2"
                      placeholder="Créneau horaire souhaité, instructions..."></textarea>
        </div>

        <button type="submit" class="btn btn-dark w-100">
            <i class="bi bi-check-lg me-2"></i>Confirmer la commande
        </button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>





