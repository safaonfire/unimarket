<?php
if (!isset($produits)) {
    require_once __DIR__ . '/../model/CRUD_produit.php';
    $crud_produit = new CRUD_produit();
    if (!empty($_GET['categorie']) && is_numeric($_GET['categorie'])) {
        $produits = $crud_produit->getByCategorie((int)$_GET['categorie']);
        $categorie_active = (int)$_GET['categorie'];
    } else {
        $produits = $crud_produit->getAll();
        $categorie_active = null;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><link href="/assets/leaf.png" rel="icon" type="image/png">
    <title>UniMarket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="/assets/leaf.png" rel="icon" type="image/png">
    <style>
        .hero {
            background-image: url("../assets/unimarket.png");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .hero-overlay {
            background: rgba(0,0,0,0.6);
            padding: 60px 30px;
            width: 100%;
        }
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        }
    </style>
</head>
<body>

<?php include __DIR__ . '/navbar.php'; ?>

<?php if (empty($categorie_active)): ?>
<div class="hero">
    <div class="hero-overlay">
        <h1 class="text-white fw-bold">La marketplace des étudiants</h1>
        <p class="lead text-white">Découvrez les créations de vos camarades — vêtements, accessoires, crochet et plus encore.</p>
        <a href="#produits" class="btn btn-warning btn-sm rounded-pill">Voir les produits</a>
    </div>
</div>
<?php endif; ?>

<div id="produits" class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">
            <?php if (!empty($categorie_active) && !empty($produits)): ?>
                <?= htmlspecialchars($produits[0]['categorie']) ?>
            <?php else: ?>
                Nos produits
            <?php endif; ?>
        </h3>
        <div class="d-flex align-items-center gap-2">
            <select class="form-select form-select-sm" style="width:auto;" id="triSelect">
                <option value="defaut">Tri par défaut</option>
                <option value="popularite">Popularité (j'aime)</option>
                <option value="recent">Plus récent</option>
                <option value="ancien">Plus ancien</option>
                <option value="prix_asc">Prix croissant</option>
                <option value="prix_desc">Prix décroissant</option>
            </select>
            <?php if (!empty($categorie_active)): ?>
                <a href="/controller/home.php" class="btn btn-outline-secondary btn-sm">Voir tout</a>
            <?php endif; ?>
        </div>
    </div>

    <?php
    // Filtrer les propres produits du vendeur connecté
    $produits_affiches = array_filter($produits ?? [], function($p) {
        return !isset($_SESSION['user_id']) || $_SESSION['user_id'] != $p['id_proprietaire'];
    });
    ?>

    <?php if (empty($produits_affiches)): ?>
        <div class="alert alert-info">Aucun produit disponible pour le moment.</div>
    <?php else: ?>
    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4" id="produitsGrid">
        <?php foreach ($produits_affiches as $p): ?>
        <div class="col produit-item"
             data-id="<?= $p['id'] ?>"
             data-likes="<?= (int)($p['nb_likes'] ?? 0) ?>"
             data-prix="<?= (float)(($p['promo'] && !empty($p['prix_promo'])) ? $p['prix_promo'] : $p['prix']) ?>">
            <?php include __DIR__ . '/partials/produit_card.php'; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>var isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;</script>
<script src="/assets/produit.js"></script>
<script>
document.getElementById('triSelect')?.addEventListener('change', function () {
    const tri = this.value;
    const grid = document.getElementById('produitsGrid');
    const items = [...grid.querySelectorAll('.produit-item')];

    items.sort((a, b) => {
        switch (tri) {
            case 'popularite': return b.dataset.likes - a.dataset.likes;
            case 'recent':     return b.dataset.id - a.dataset.id;
            case 'ancien':     return a.dataset.id - b.dataset.id;
            case 'prix_asc':   return a.dataset.prix - b.dataset.prix;
            case 'prix_desc':  return b.dataset.prix - a.dataset.prix;
            default:           return 0;
        }
    });

    items.forEach(item => grid.appendChild(item));
});
</script>
</body>
</html>


