<?php
/**
 * Partial : carte produit
 * Variable attendue : $p (tableau produit avec nb_likes)
 */
// Charger les favoris une seule fois par requête
if (!isset($GLOBALS['_favoris_ids']) && isset($_SESSION['user_id'])) {
    require_once __DIR__ . '/../../connexion.php';
    $pdo_fav = (new Connexion())->getConnexion();
    $stmt_fav = $pdo_fav->prepare("SELECT id_produit FROM favoris WHERE id_user = ?");
    $stmt_fav->execute([$_SESSION['user_id']]);
    $GLOBALS['_favoris_ids'] = $stmt_fav->fetchAll(PDO::FETCH_COLUMN, 0);
}
$est_favori = isset($_SESSION['user_id']) && in_array($p['id'], $GLOBALS['_favoris_ids'] ?? []);
?>
<div class="card h-100 shadow-sm position-relative">

    <?php if ($p['promo']): ?>
        <span class="badge bg-danger position-absolute" style="top:10px; left:10px;">Promo</span>
    <?php endif; ?>

<img src="/<?= htmlspecialchars($p['image']) ?>"
         class="card-img-top"
         alt="<?= htmlspecialchars($p['libelle']) ?>"
         style="height:200px; object-fit:cover;">

    <div class="card-body d-flex flex-column">

        <h6 class="card-title"><?= htmlspecialchars($p['libelle']) ?></h6>

        <p class="card-text text-muted small">
            <?= htmlspecialchars(mb_strimwidth($p['description'], 0, 60, '...')) ?>
        </p>

        <!-- Prix + likes -->
        <div class="d-flex justify-content-between align-items-center mt-auto mb-2">
            <?php if ($p['promo'] && !empty($p['prix_promo'])): ?>
                <div>
                    <span style="text-decoration:line-through; color:#aaa; font-size:13px;"><?= $p['prix'] ?> TND</span>
                    <span class="fw-bold ms-1"><?= $p['prix_promo'] ?> TND</span>
                </div>
            <?php else: ?>
                <span class="fw-bold"><?= $p['prix'] ?> TND</span>
            <?php endif; ?>

            <button onclick="toggleFavori(<?= $p['id'] ?>, this)"
                    class="btn btn-sm p-0 border-0 bg-transparent d-flex align-items-center gap-1"
                    data-id="<?= $p['id'] ?>"
                    <?= (isset($_SESSION['user_id']) && $p['id_proprietaire'] == $_SESSION['user_id']) ? 'disabled' : '' ?>>
                <i class="bi <?= $est_favori ? 'bi-heart-fill' : 'bi-heart' ?> text-danger fs-6"></i>
                <span class="nb-likes-<?= $p['id'] ?> text-muted small"><?= (int)($p['nb_likes'] ?? 0) ?></span>
            </button>
        </div>

        <!-- Voir détail -->
        <a href="/controller/produit/detail.php?id=<?= $p['id'] ?>"
           class="btn btn-dark btn-sm w-100 mb-2">
            Voir détail
        </a>

        <?php if (!isset($_SESSION['user_id']) || $p['id_proprietaire'] != $_SESSION['user_id']): ?>
            <!-- Panier -->
            <button onclick="ajouterPanier(<?= $p['id'] ?>, this)"
                    class="btn btn-success btn-sm w-100">
                <i class="bi bi-cart-plus me-1"></i>Ajouter au panier
            </button>
        <?php endif; ?>

        <!-- Actions vendeur sur ses propres produits -->
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin' && $_SESSION['user_id'] == $p['id_proprietaire']): ?>
            <a href="/controller/vendeur/edit_produit.php?id=<?= $p['id'] ?>"
               class="btn btn-dark btn-sm w-100 mt-2">
                <i class="bi bi-pencil me-1"></i>Modifier
            </a>
            <a href="/controller/vendeur/delete_produit.php?id=<?= $p['id'] ?>"
               class="btn btn-danger btn-sm w-100 mt-2"
               onclick="return confirm('Supprimer ce produit ?')">
                <i class="bi bi-trash me-1"></i>Supprimer
            </a>
        <?php endif; ?>

    </div>
</div>





