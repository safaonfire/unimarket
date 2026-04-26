<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes reçues</title>
    <link rel="icon" type="image/png" href="/assets/leaf.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background:#f8f9fa; padding-top: 70px; }
        .order-img { width:65px; height:65px; object-fit:cover; border-radius:8px; }
    </style>
</head>
<body>

<?php include __DIR__ . '/_navbar.php'; ?>

<div class="container py-4" style="max-width:900px;">
    <h4 class="mb-4"><i class="bi bi-inbox me-2"></i>Commandes reçues</h4>

    <?php if (empty($commandes)): ?>
        <div class="text-center py-5">
            <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
            <p class="text-muted">Aucune commande reçue pour le moment.</p>
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
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                            <div>
                                <div class="fw-semibold"><?= htmlspecialchars($cmd['libelle']) ?></div>
                                <div class="text-muted small">
                                    <?= $prix_u ?> TND × <?= $cmd['quantite'] ?> =
                                    <strong><?= number_format($total, 2) ?> TND</strong>
                                </div>
                            </div>
                            <span class="badge bg-<?= $statut_colors[$statut] ?? 'secondary' ?> fs-6">
                                <?= $statut_labels[$statut] ?? $statut ?>
                            </span>
                        </div>

                        <!-- Infos client -->
                        <div class="mt-2 p-2 rounded" style="background:#f8f9fa;">
                            <div class="fw-semibold small mb-1">
                                <i class="bi bi-person me-1"></i>
                                <?= htmlspecialchars($cmd['client_nom'] . ' ' . $cmd['client_prenom']) ?>
                            </div>
                            <?php if (!empty($cmd['adresse_livraison'])): ?>
                            <div class="text-muted small">
                                <i class="bi bi-pin-map me-1"></i>RDV : <?= htmlspecialchars($cmd['adresse_livraison']) ?>
                            </div>
                            <?php endif; ?>
                            <div class="text-muted small">
                                <i class="bi bi-telephone me-1"></i>
                                <?= htmlspecialchars($cmd['telephone'] ?? $cmd['client_tel']) ?>
                            </div>
                        </div>

                        <?php if (!empty($cmd['description'])): ?>
                        <div class="text-muted small mt-1">
                            <i class="bi bi-chat-left-text me-1"></i><?= htmlspecialchars($cmd['description']) ?>
                        </div>
                        <?php endif; ?>

                        <div class="text-muted small mt-1">
                            <i class="bi bi-calendar me-1"></i>
                            <?= date('d/m/Y à H:i', strtotime($cmd['date_commande'])) ?>
                        </div>

                        <!-- Actions -->
                        <?php if ($statut === 'en_attente'): ?>
                        <div class="d-flex gap-2 mt-3">
                            <a href="/controller/vendeur/update_commande.php?id=<?= $cmd['id'] ?>&statut=confirmee"
                               class="btn btn-success btn-sm"
                               onclick="return confirm('Confirmer cette commande ?')">
                                <i class="bi bi-check-lg me-1"></i>Confirmer
                            </a>
                            <a href="/controller/vendeur/update_commande.php?id=<?= $cmd['id'] ?>&statut=annulee"
                               class="btn btn-outline-danger btn-sm"
                               onclick="return confirm('Refuser cette commande ?')">
                                <i class="bi bi-x-lg me-1"></i>Refuser
                            </a>
                        </div>
                        <?php endif; ?>

        <!-- Chat -->
                        <?php include __DIR__ . '/../partials/chat_commande.php'; ?>
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





