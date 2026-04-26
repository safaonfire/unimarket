<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/assets/leaf.png" rel="icon" type="image/png">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<title>Modifier le produit</title>

<style>
body { background-color: #f8f9fa; padding-top: 70px; }
.form-box { max-width: 600px; margin: auto; }
</style>
</head>

<body>

<?php include __DIR__ . '/_navbar.php'; ?>

<div class="container form-box">

    <h5 class="mb-4 text-center">Modifier le produit</h5>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="/controller/vendeur/edit_produit.php?id=<?= $produit['id'] ?>" 
          method="POST" 
          enctype="multipart/form-data"
          class="bg-white p-4 border rounded shadow-sm">

        <div class="mb-3">
            <label class="form-label">Libellé *</label>
            <input type="text" name="libelle" class="form-control" value="<?= htmlspecialchars($produit['libelle']) ?>" required>
        </div>

        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Prix (TND) *</label>
                <input type="number" step="0.01" name="prix" id="prix_input" class="form-control" value="<?= $produit['prix'] ?>" required
                    style="<?= $produit['promo'] ? 'text-decoration:line-through; color:#aaa;' : '' ?>">
            </div>

            <div class="col mb-3">
                <label class="form-label">Quantité *</label>
                <input type="number" name="quantite" class="form-control" value="<?= $produit['quantite'] ?>" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Description *</label>
            <textarea name="description" class="form-control" rows="3" required><?= htmlspecialchars($produit['description']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Image</label>
            <input type="file" name="image" class="form-control" accept="image/*">
            <?php if ($produit['image']): ?>
                <img src="/<?= htmlspecialchars($produit['image']) ?>" class="mt-2 rounded" style="height:80px;">
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Catégorie</label>
            <?php $selected_id = $produit['id_categorie'] ?? null; include __DIR__ . '/../partials/categorie_select.php'; ?>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="promo" id="promo" class="form-check-input" <?= $produit['promo'] ? 'checked' : '' ?>>
            <label for="promo" class="form-check-label">En promotion</label>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="handmade" id="handmade" class="form-check-input" <?= !empty($produit['handmade']) ? 'checked' : '' ?>>
            <label for="handmade" class="form-check-label">🤝 Handmade (fait main)</label>
        </div>

        <div class="mb-3" id="prix_promo_box" style="<?= $produit['promo'] ? '' : 'display:none;' ?>">
            <label class="form-label">Prix en promotion (TND) *</label>
            <input type="number" step="0.01" name="prix_promo" id="prix_promo" class="form-control"
                   value="<?= htmlspecialchars($produit['prix_promo'] ?? '') ?>">
        </div>

        <button type="submit" name="ok" class="btn btn-dark w-100">
            Enregistrer
        </button>

    </form>
</div>

<script>
const promoCheck = document.getElementById('promo');
const promoBox   = document.getElementById('prix_promo_box');
const prixInput  = document.getElementById('prix_input');

promoCheck.addEventListener('change', function() {
    if (this.checked) {
        promoBox.style.display = 'block';
        prixInput.style.textDecoration = 'line-through';
        prixInput.style.color = '#aaa';
    } else {
        promoBox.style.display = 'none';
        prixInput.style.textDecoration = '';
        prixInput.style.color = '';
    }
});
</script>

</body>
</html>



