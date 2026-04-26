<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Dashboard Super Admin</title>
    <link rel="icon" type="image/png" href="/assets/leaf.png">
    <style>
        body { background-color: #f8f9fa; padding-top: 60px; }

        .navbar-admin {
            background-color: #1a1a1a;
        }
        .navbar-admin .navbar-brand,
        .navbar-admin .nav-link {
            color: white !important;
        }
        .navbar-admin .nav-link:hover {
            color: #ffc107 !important;
        }
        .navbar-admin .nav-link.active-section {
            color: #ffc107 !important;
            font-weight: 600;
        }
        .table-section { display: none; }
        .table-section.active { display: block; }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-admin fixed-top px-4">
    <a class="navbar-brand fw-bold" href="#">UniMarket</a>
    <span class="text-muted ms-2" style="color:#aaa !important; font-size:13px;">
        | Super Admin : <?= htmlspecialchars($_SESSION['user_nom']) ?>
    </span>

    <div class="collapse navbar-collapse ms-4">
        <ul class="navbar-nav me-auto">
            <li class="nav-item">
                <a class="nav-link <?= $section === 'produits' ? 'active-section' : '' ?>"
                   href="?section=produits"> Liste des produits</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $section === 'vendeurs' ? 'active-section' : '' ?>"
                   href="?section=vendeurs"> Liste des vendeurs</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $section === 'clients' ? 'active-section' : '' ?>"
                   href="?section=clients"> Liste des clients</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $section === 'demandes' ? 'active-section' : '' ?>"
                   href="?section=demandes">
                    Demandes vendeurs
                    <?php if (!empty($demandes)): ?>
                        <span class="badge bg-danger ms-1"><?= count($demandes) ?></span>
                    <?php endif; ?>
                </a>
            </li>
        </ul>
        <a href="/controller/user/logout.php" class="btn btn-outline-danger btn-sm">Déconnexion</a>
    </div>
</nav>

<div class="container-fluid px-4 mt-3">

    <!-- Produits -->
    <div id="section-produits" class="table-section <?= $section === 'produits' ? 'active' : '' ?>">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Produits</h5>
            <a href="/controller/admin/add_produit.php" class="btn btn-dark btn-sm">+ Ajouter un nouveau produit</a>
        </div>
        <?php if (empty($produits)): ?>
            <div class="alert alert-info">Aucun produit disponible pour le moment.</div>
        <?php else: ?>
        <table class="table table-bordered table-hover bg-white">
            <thead class="table-dark">
                <tr><th>#</th><th>Libellé</th><th>Prix</th><th>Quantité</th><th>Vendeur</th><th>Catégorie</th><th>Promo</th></tr>
            </thead>
            <tbody>
                <?php foreach ($produits as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= htmlspecialchars($p['libelle']) ?></td>
                    <td><?= $p['prix'] ?> TND</td>
                    <td><?= $p['quantite'] ?></td>
                    <td><?= htmlspecialchars($p['vendeur_nom'] . ' ' . $p['vendeur_prenom']) ?></td>
                    <td><?= htmlspecialchars($p['categorie'] ?? '-') ?></td>
                    <td><?= $p['promo'] ? '<span class="badge bg-success">Oui</span>' : '<span class="badge bg-secondary">Non</span>' ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <!-- Vendeurs -->
    <div id="section-vendeurs" class="table-section <?= $section === 'vendeurs' ? 'active' : '' ?>">
        <h5 class="mb-3">Vendeurs</h5>
        <?php if (empty($vendeurs)): ?>
            <div class="alert alert-info">Aucun vendeur enregistré.</div>
        <?php else: ?>
        <table class="table table-bordered table-hover bg-white">
            <thead class="table-dark">
                <tr><th>#</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Téléphone</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php foreach ($vendeurs as $v): ?>
                <tr>
                    <td><?= $v['id'] ?></td>
                    <td><?= htmlspecialchars($v['nom']) ?></td>
                    <td><?= htmlspecialchars($v['prenom']) ?></td>
                    <td><?= htmlspecialchars($v['email']) ?></td>
                    <td><?= htmlspecialchars($v['tel']) ?></td>
                    <td>
                        <a href="/controller/admin/delete_user.php?id=<?= $v['id'] ?>&from=vendeurs"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Supprimer ce vendeur ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <!-- Clients -->
    <div id="section-clients" class="table-section <?= $section === 'clients' ? 'active' : '' ?>">
        <h5 class="mb-3">Clients</h5>
        <?php if (empty($clients)): ?>
            <div class="alert alert-info">Aucun client enregistré.</div>
        <?php else: ?>
        <table class="table table-bordered table-hover bg-white">
            <thead class="table-dark">
                <tr><th>#</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Téléphone</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= htmlspecialchars($c['nom']) ?></td>
                    <td><?= htmlspecialchars($c['prenom']) ?></td>
                    <td><?= htmlspecialchars($c['email']) ?></td>
                    <td><?= htmlspecialchars($c['tel']) ?></td>
                    <td>
                        <a href="/controller/admin/delete_user.php?id=<?= $c['id'] ?>&from=clients"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Supprimer ce client ?')">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <!-- Demandes vendeurs -->
    <div id="section-demandes" class="table-section <?= $section === 'demandes' ? 'active' : '' ?>">
        <h5 class="mb-3">Demandes pour devenir vendeur</h5>
        <?php if (empty($demandes)): ?>
            <div class="alert alert-info">Aucune demande en attente.</div>
        <?php else: ?>
        <table class="table table-bordered table-hover bg-white">
            <thead class="table-dark">
                <tr><th>#</th><th>Nom complet</th><th>Email</th><th>Téléphone</th><th>Type de produits</th><th>Description</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($demandes as $d): ?>
                <tr>
                    <td><?= $d['id'] ?></td>
                    <td><?= htmlspecialchars($d['nom_complet'] ?: $d['prenom'] . ' ' . $d['nom']) ?></td>
                    <td><?= htmlspecialchars($d['email'] ?: '—') ?></td>
                    <td><?= htmlspecialchars($d['telephone'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($d['type_produits'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($d['message'] ?: '—') ?></td>
                    <td><?= date('d/m/Y', strtotime($d['created_at'])) ?></td>
                    <td class="d-flex gap-2">
                        <a href="/controller/admin/traiter_demande.php?id=<?= $d['id'] ?>&action=accepter"
                           class="btn btn-success btn-sm"
                           onclick="return confirm('Accepter cette demande ?')">✅ Accepter</a>
                        <a href="/controller/admin/traiter_demande.php?id=<?= $d['id'] ?>&action=refuser"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('Refuser cette demande ?')">❌ Refuser</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>





