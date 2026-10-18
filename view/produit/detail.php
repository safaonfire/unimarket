<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <title><?= htmlspecialchars($produit['libelle']) ?></title>
    <style>
        body { background-color: #f8f9fa; padding-top: 80px; }
        .product-img { width:100%; max-height:500px; object-fit:cover; border-radius:8px; }
        .btn-like {
            background: none;
            border: none;
            padding: 0;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 1.5rem;
            cursor: pointer;
            transition: transform 0.15s;
        }
        .btn-like:active { transform: scale(1.3); }
        .btn-like .nb-likes { font-size: 1rem; color: #555; }
    </style>
</head>
<body>

<?php include __DIR__ . '/../navbar.php'; ?>

<div class="container py-4">
    <a href="/controller/home.php" class="btn btn-outline-secondary btn-sm mb-4">← Retour</a>

    <div class="row g-4">
        <div class="col-md-5 position-relative">
            <?php if ($produit['promo']): ?>
                <span class="badge bg-danger position-absolute" style="top:10px; left:20px; font-size:13px; z-index:1;">Promo</span>
            <?php endif; ?>
            <img src="/<?= htmlspecialchars($produit['image']) ?>" class="product-img" alt="<?= htmlspecialchars($produit['libelle']) ?>">
        </div>

        <div class="col-md-7">
            <h3><?= htmlspecialchars($produit['libelle']) ?></h3>

            <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                <?php if (!empty($produit['categorie'])): ?>
                    <span class="badge bg-secondary"><?= htmlspecialchars($produit['categorie']) ?></span>
                <?php endif; ?>
                <?php if (!empty($produit['handmade'])): ?>
                    <span class="badge" style="background:#6f4e37;">🤝 Handmade</span>
                <?php endif; ?>
            </div>

            <?php if ($produit['promo'] && !empty($produit['prix_promo'])): ?>
                <h4 class="mt-3">
                    <span style="text-decoration:line-through; color:#aaa; font-size:18px;"><?= $produit['prix'] ?> TND</span>
                    <span class="ms-2"><?= $produit['prix_promo'] ?> TND</span>
                </h4>
            <?php else: ?>
                <h4 class="mt-3"><?= $produit['prix'] ?> TND</h4>
            <?php endif; ?>

            <p class="text-muted mt-3"><?= nl2br(htmlspecialchars($produit['description'])) ?></p>

            <p class="mt-2">
                <span class="text-muted">Quantité disponible :</span>
                <strong><?= $produit['quantite'] ?></strong>
            </p>

            <p>
                <span class="text-muted">Vendeur :</span>
                <strong><?= htmlspecialchars($produit['vendeur_nom'] . ' ' . $produit['vendeur_prenom']) ?></strong>
            </p>

            <!-- J'aime style Instagram -->
            <div class="d-flex align-items-center gap-3 my-3">
                <?php if (!isset($_SESSION['user_id']) || $produit['id_proprietaire'] != $_SESSION['user_id']): ?>
                <button class="btn-like" onclick="toggleFavori(<?= $produit['id'] ?>, this)" data-id="<?= $produit['id'] ?>">
                    <i class="bi bi-heart text-danger"></i>
                    <span class="nb-likes nb-likes-<?= $produit['id'] ?>"><?= (int)($produit['nb_likes'] ?? 0) ?></span>
                </button>
                <?php else: ?>
                <span class="d-flex align-items-center gap-1" style="font-size:1.3rem;">
                    <i class="bi bi-heart-fill text-danger"></i>
                    <span class="nb-likes-<?= $produit['id'] ?>" style="font-size:1rem; color:#555;"><?= (int)($produit['nb_likes'] ?? 0) ?></span>
                </span>
                <?php endif; ?>
            </div>

            <!-- Panier (sans connexion requise) -->
            <?php if (!isset($_SESSION['user_id']) || $produit['id_proprietaire'] != $_SESSION['user_id']): ?>
            <?php if ($produit['quantite'] > 0): ?>
            <div class="d-flex align-items-center gap-2 mt-3 mb-2">
                <button class="btn btn-outline-secondary btn-sm" onclick="changeQte(-1)" style="width:34px;">
                    <i class="bi bi-dash"></i>
                </button>
                <span id="qteDetail" class="fw-bold px-2">1</span>
                <button class="btn btn-outline-secondary btn-sm" onclick="changeQte(1)" style="width:34px;">
                    <i class="bi bi-plus"></i>
                </button>
                <span class="text-muted small">(<?= $produit['quantite'] ?> dispo)</span>
            </div>
            <button onclick="ajouterPanierQte(<?= $produit['id'] ?>, this)"
                    class="btn btn-success w-100">
                <i class="bi bi-cart-plus me-2"></i>Ajouter au panier
            </button>
            <?php else: ?>
            <div class="alert alert-warning mt-3 py-2">
                <i class="bi bi-exclamation-triangle me-1"></i>Rupture de stock
            </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Section commentaires -->
<div class="container py-4" style="max-width:700px;">
    <h5 class="mb-3"><i class="bi bi-chat-left-text me-2"></i>Commentaires</h5>

    <?php if (isset($_SESSION['user_id']) && $produit['id_proprietaire'] != $_SESSION['user_id']): ?>
    <div class="d-flex gap-2 mb-4">
        <input type="text" id="inputCommentaire" class="form-control"
               placeholder="Écrire un commentaire..."
               onkeydown="if(event.key==='Enter') ajouterCommentaire()">
        <button class="btn btn-dark px-3" onclick="ajouterCommentaire()">
            <i class="bi bi-send"></i>
        </button>
    </div>
    <?php endif; ?>

    <div id="listeCommentaires">
        <div class="text-muted small text-center py-3">Chargement...</div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>var isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;</script>
<script src="/assets/produit.js"></script>
<script>
const produitId = <?= (int)$produit['id'] ?>;

// Charger les commentaires
function chargerCommentaires() {
    fetch('/controller/commentaire/get.php?id_produit=' + produitId)
        .then(r => r.json())
        .then(items => {
            const zone = document.getElementById('listeCommentaires');
            if (!items.length) {
                zone.innerHTML = '<div class="text-muted small text-center py-3">Aucun commentaire pour le moment.</div>';
                return;
            }
            zone.innerHTML = items.map(c => `
                <div class="d-flex gap-3 mb-3" id="comment-${c.id}">
                    <img src="${c.photo}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold" style="font-size:14px;">${c.nom}</span>
                            <span class="text-muted" style="font-size:11px;">${c.date}</span>
                        </div>
                        <div style="font-size:14px;">${c.contenu}</div>
                        ${c.est_moi ? `<button onclick="supprimerCommentaire(${c.id})" class="btn btn-link btn-sm text-danger p-0 mt-1" style="font-size:12px;">Supprimer</button>` : ''}
                    </div>
                </div>
            `).join('<hr class="my-2">');
        });
}

function ajouterCommentaire() {
    const input = document.getElementById('inputCommentaire');
    const contenu = input.value.trim();
    if (!contenu) return;
    input.value = '';
    fetch('/controller/commentaire/add.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id_produit=' + produitId + '&contenu=' + encodeURIComponent(contenu)
    }).then(r => r.json()).then(data => {
        if (data.success) chargerCommentaires();
    });
}

function supprimerCommentaire(id) {
    fetch('/controller/commentaire/delete.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id=' + id
    }).then(r => r.json()).then(data => {
        if (data.success) chargerCommentaires();
    });
}

chargerCommentaires();
let qteDetail = 1;

function changeQte(delta) {
    qteDetail = Math.min(stockMax, Math.max(1, qteDetail + delta));
    document.getElementById('qteDetail').textContent = qteDetail;
}

function ajouterPanierQte(id_produit, btn) {
    fetch('/controller/panier/index.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=ajouter&id_produit=' + id_produit + '&quantite=' + qteDetail
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const original = btn.innerHTML;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i>Ajouté !';
            btn.classList.replace('btn-success', 'btn-secondary');
            setTimeout(() => {
                btn.innerHTML = original;
                btn.classList.replace('btn-secondary', 'btn-success');
            }, 1500);
            const badge = document.querySelector('a[href="/controller/panier/voir.php"] .badge');
            if (badge) badge.textContent = data.total;
            else {
                const cartLink = document.querySelector('a[href="/controller/panier/voir.php"]');
                if (cartLink) {
                    const span = document.createElement('span');
                    span.className = 'position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger';
                    span.style.fontSize = '10px';
                    span.textContent = data.total;
                    cartLink.appendChild(span);
                }
            }
        }
    });
}
</script>
</body>
</html>





