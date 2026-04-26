<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/leaf.png" rel="icon" type="image/png">
    <title>Mes commandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background:#f8f9fa; padding-top: 80px; }
        .order-img { width:70px; height:70px; object-fit:cover; border-radius:8px; }
    </style>
</head>
<body>

<?php include __DIR__ . '/navbar.php'; ?>

<div class="container py-4" style="max-width:800px;">

    <?php if (!empty($_GET['success'])): ?>
        <div class="alert alert-success d-flex align-items-center gap-2">
            <i class="bi bi-check-circle-fill"></i>
            Votre commande a été passée avec succès ! Le vendeur va la traiter prochainement.
        </div>
    <?php endif; ?>

    <h4 class="mb-4"><i class="bi bi-bag me-2"></i>Mes commandes</h4>

    <?php if (empty($commandes)): ?>
        <div class="text-center py-5">
            <i class="bi bi-bag-x fs-1 text-muted d-block mb-3"></i>
            <p class="text-muted">Vous n'avez pas encore passé de commande.</p>
            <a href="/controller/home.php" class="btn btn-dark btn-sm">Découvrir les produits</a>
        </div>
    <?php else: ?>
        <div class="d-flex flex-column gap-3">
        <?php foreach ($commandes as $cmd):
            $prix_u = !empty($cmd['prix_unitaire']) ? $cmd['prix_unitaire'] : $cmd['prix'];
            $total  = $prix_u * $cmd['quantite'];
            $statut_labels = ['en_attente' => 'En attente', 'confirmee' => 'Confirmée', 'annulee' => 'Annulée'];
            $statut_colors = ['en_attente' => 'warning', 'confirmee' => 'success', 'annulee' => 'danger'];
            $statut = $cmd['statut'];
        ?>
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex gap-3 align-items-start">
                    <img src="/<?= htmlspecialchars($cmd['image']) ?>" class="order-img">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold"><?= htmlspecialchars($cmd['libelle']) ?></div>
                                <div class="text-muted small">
                                    <?= $prix_u ?> TND × <?= $cmd['quantite'] ?> =
                                    <strong><?= number_format($total, 2) ?> TND</strong>
                                </div>
                            </div>
                            <span class="badge bg-<?= $statut_colors[$statut] ?? 'secondary' ?>">
                                <?= $statut_labels[$statut] ?? $statut ?>
                            </span>
                        </div>

                        <?php if (!empty($cmd['adresse_livraison'])): ?>
                        <div class="text-muted small mt-1">
                            <i class="bi bi-pin-map me-1"></i>RDV : <?= htmlspecialchars($cmd['adresse_livraison']) ?>
                            <?php if (!empty($cmd['telephone'])): ?>
                             — <i class="bi bi-telephone me-1"></i><?= htmlspecialchars($cmd['telephone']) ?>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($cmd['description'])): ?>
                        <div class="text-muted small mt-1">
                            <i class="bi bi-chat-left-text me-1"></i><?= htmlspecialchars($cmd['description']) ?>
                        </div>
                        <?php endif; ?>

                        <div class="text-muted small mt-1">
                            <i class="bi bi-calendar me-1"></i>
                            Commandé le <?= date('d/m/Y à H:i', strtotime($cmd['date_commande'])) ?>
                            <?php if (!empty($cmd['date_confirmation'])): ?>
                             — Confirmé le <?= date('d/m/Y', strtotime($cmd['date_confirmation'])) ?>
                            <?php endif; ?>
                        </div>

                        <!-- Chat -->
                        <?php include __DIR__ . '/partials/chat_commande.php'; ?>
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



