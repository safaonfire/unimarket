<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Ajouter un produit</title>
    <link rel="icon" type="image/png" href="/assets/leaf.png">
    <style>
        body { background-color: #f8f9fa; padding-top: 70px; }
        .navbar-admin { background-color: #1a1a1a; }
        .navbar-admin .navbar-brand, .navbar-admin .nav-link { color: white !important; }
    </style>
</head>
<body>

<nav class="navbar navbar-admin fixed-top px-4">
    <a class="navbar-brand fw-bold" href="/controller/admin/dashboard.php">← Retour au dashboard</a>
</nav>

<div class="container" style="max-width:600px;">
    <h5 class="mb-4">Ajouter un nouveau produit</h5>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="/controller/admin/add_produit.php" method="POST" enctype="multipart/form-data" class="bg-white p-4 border rounded shadow-sm">

        <div class="mb-3">
            <label class="form-label">Libellé *</label>
            <input type="text" name="libelle" class="form-control" required>
        </div>

        <div class="row">
            <div class="col mb-3">
                <label class="form-label">Prix (TND) *</label>
                <input type="number" step="0.01" name="prix" class="form-control" required>
            </div>
            <div class="col mb-3">
                <label class="form-label">Quantité *</label>
                <input type="number" name="quantite" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Description *</label>
            <textarea name="description" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Image *</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Vendeur (propriétaire) *</label>
            <select name="id_proprietaire" class="form-select" required>
                <option value="">-- Choisir un vendeur --</option>
                <?php foreach ($vendeurs as $v): ?>
                    <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['nom'] . ' ' . $v['prenom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Catégorie</label>
            <?php include __DIR__ . '/../partials/categorie_select.php'; ?>
        </div>

        <div class="mb-3 form-check">
            <input type="checkbox" name="promo" id="promo" class="form-check-input">
            <label for="promo" class="form-check-label">En promotion</label>
        </div>

        <button type="submit" name="ok" class="btn btn-dark w-100">Ajouter le produit</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>





