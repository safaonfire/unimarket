<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Super Admin — UniMarket</title>
    <link rel="icon" type="image/png" href="/assets/leaf.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body { background: #f0f2f5; color: #1a1a2e; padding-top: 56px; font-family: 'Segoe UI', sans-serif; }

        /* ── Navbar ── */
        .navbar-admin {
            background: #1e293b;
            border-bottom: 1px solid #1e293b;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
        }
        .navbar-admin .navbar-brand { color: #fff; font-weight: 700; font-size: 17px; }
        .navbar-admin .nav-link     { color: #94a3b8; font-size: 13px; padding: 6px 12px; border-radius: 8px; transition: .2s; }
        .navbar-admin .nav-link:hover { color: #fff; background: #334155; }
        .navbar-admin .nav-link.active-section { color: #fff; background: #7c3aed; font-weight: 600; }
        .badge-admin { background: #ef4444; color:#fff; font-size: 10px; padding: 2px 6px; border-radius: 10px; }
        .user-chip { background: #334155; color: #94a3b8; font-size: 12px; padding: 4px 10px; border-radius: 20px; border: 1px solid #475569; }

        /* ── Stat cards ── */
        .stat-card {
            border-radius: 14px;
            padding: 18px 20px;
            color: #fff;
            position: relative;
            overflow: hidden;
            min-height: 90px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.10);
        }
        .stat-card .label { font-size: 11px; opacity: .88; text-transform: uppercase; letter-spacing: .5px; }
        .stat-card .value { font-size: 36px; font-weight: 700; line-height: 1.1; }
        .stat-card .sub   { font-size: 11px; opacity: .75; margin-top: 2px; }
        .stat-card .icon  { position: absolute; right: 16px; top: 50%; transform: translateY(-50%); font-size: 40px; opacity: .18; }
        .bg-grad-1 { background: linear-gradient(135deg,#7c3aed,#a855f7); }
        .bg-grad-2 { background: linear-gradient(135deg,#0891b2,#22d3ee); }
        .bg-grad-3 { background: linear-gradient(135deg,#059669,#34d399); }
        .bg-grad-4 { background: linear-gradient(135deg,#0d9488,#2dd4bf); }
        .bg-grad-5 { background: linear-gradient(135deg,#d97706,#fbbf24); }
        .bg-grad-6 { background: linear-gradient(135deg,#ea580c,#fb923c); }
        .bg-grad-7 { background: linear-gradient(135deg,#db2777,#f472b6); }
        .bg-grad-8 { background: linear-gradient(135deg,#475569,#94a3b8); }

        /* ── Chart cards ── */
        .chart-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 20px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 1px 6px rgba(0,0,0,0.05);
        }
        .chart-card h6 { color: #374151; font-size: 13px; font-weight: 600; margin-bottom: 14px; }

        /* ── Table section ── */
        .table-section { display: none; }
        .table-section.active { display: block; }

        .section-header { margin-bottom: 18px; }
        .section-header h5 { color: #1a1a2e; font-weight: 700; }

        .search-box {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            color: #374151;
            padding: 7px 14px;
            font-size: 13px;
            width: 220px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .search-box::placeholder { color: #9ca3af; }
        .search-box:focus { outline: none; border-color: #7c3aed; box-shadow: 0 0 0 3px rgba(124,58,237,0.1); }

        /* ── Table ── */
        .table-dark-custom { width: 100%; border-collapse: collapse; font-size: 13px; }
        .table-dark-custom thead tr { background: #1e293b; }
        .table-dark-custom thead th { color: #cbd5e1; font-weight: 600; padding: 12px 14px; border-bottom: none; text-transform: uppercase; font-size: 11px; letter-spacing: .5px; }
        .table-dark-custom tbody tr { border-bottom: 1px solid #f1f5f9; transition: background .15s; background: #fff; }
        .table-dark-custom tbody tr:hover { background: #f8fafc; }
        .table-dark-custom tbody td { padding: 11px 14px; color: #374151; vertical-align: middle; }
        .table-dark-custom .prod-img { width: 38px; height: 38px; border-radius: 8px; object-fit: cover; }

        .badge-promo { background: #d1fae5; color: #065f46; padding: 3px 9px; border-radius: 6px; font-size: 11px; font-weight: 600; }
        .badge-no    { background: #f1f5f9; color: #64748b; padding: 3px 9px; border-radius: 6px; font-size: 11px; }

        .btn-action { font-size: 12px; padding: 4px 12px; border-radius: 6px; cursor: pointer; font-weight: 500; }
        .btn-accept { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
        .btn-accept:hover { background: #16a34a; color: #fff; border-color: #16a34a; }
        .btn-refuse { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }
        .btn-refuse:hover { background: #dc2626; color: #fff; border-color: #dc2626; }
        .btn-del    { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; font-size: 12px; padding: 3px 10px; border-radius: 6px; font-weight: 500; }
        .btn-del:hover { background: #dc2626; color: #fff; border-color: #dc2626; }

        .pagination-wrap { margin-top: 12px; font-size: 13px; color: #9ca3af; }
    </style>
</head>
<body>

<!-- ── Navbar ──────────────────────────────────────────────────────────────── -->
<nav class="navbar navbar-expand-lg navbar-admin fixed-top px-3 py-2">
    <a class="navbar-brand me-3" href="?section=stats">
        <i class="bi bi-bag-heart-fill me-1 text-purple" style="color:#a855f7"></i>UniMarket
    </a>
    <span class="user-chip me-3">
        Super Admin : <?= htmlspecialchars($_SESSION['user_prenom'] . ' ' . $_SESSION['user_nom']) ?>
    </span>

    <ul class="navbar-nav flex-row gap-1 me-auto">
        <li class="nav-item">
            <a class="nav-link <?= $section==='stats' ? 'active-section':'' ?>" href="?section=stats">
                <i class="bi bi-bar-chart-line me-1"></i>Statistiques
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $section==='produits' ? 'active-section':'' ?>" href="?section=produits">
                <i class="bi bi-box-seam me-1"></i>Produits
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $section==='categories' ? 'active-section':'' ?>" href="?section=categories">
                <i class="bi bi-tag me-1"></i>Catégories
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $section==='vendeurs' ? 'active-section':'' ?>" href="?section=vendeurs">
                <i class="bi bi-shop me-1"></i>Vendeurs
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $section==='clients' ? 'active-section':'' ?>" href="?section=clients">
                <i class="bi bi-people me-1"></i>Clients
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= $section==='demandes' ? 'active-section':'' ?>" href="?section=demandes">
                <i class="bi bi-person-plus me-1"></i>Demandes
                <?php if ($total_demandes > 0): ?>
                    <span class="badge-admin"><?= $total_demandes ?></span>
                <?php endif; ?>
            </a>
        </li>
    </ul>

    <a href="/controller/user/logout.php" class="btn btn-sm" style="background:#ef4444;color:#fff;border-radius:8px;font-size:12px;">
        <i class="bi bi-box-arrow-right me-1"></i>Déconnexion
    </a>
</nav>

<div class="container-fluid px-4 py-3">

<!-- ══════════════════════════════════════════════════════════════════════════
     STATS
═══════════════════════════════════════════════════════════════════════════ -->
<div class="table-section <?= $section==='stats' ? 'active':'' ?>">

    <!-- Row 1: 4 cards -->
    <div class="row g-3 mb-3">
        <div class="col-6 col-md-3">
            <div class="stat-card bg-grad-1">
                <div class="label">Produits</div>
                <div class="value"><?= $total_produits ?></div>
                <div class="sub">sur la plateforme</div>
                <i class="bi bi-box-seam icon"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card bg-grad-2">
                <div class="label">Vendeurs</div>
                <div class="value"><?= $total_vendeurs ?></div>
                <div class="sub">comptes actifs</div>
                <i class="bi bi-shop icon"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card bg-grad-3">
                <div class="label">Clients</div>
                <div class="value"><?= $total_clients ?></div>
                <div class="sub">inscrits</div>
                <i class="bi bi-people icon"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card bg-grad-4">
                <div class="label">CA Confirmé</div>
                <div class="value"><?= number_format($chiffre_affaires, 0) ?></div>
                <div class="sub">TND</div>
                <i class="bi bi-cash-stack icon"></i>
            </div>
        </div>
    </div>

    <!-- Row 2: 4 cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="stat-card bg-grad-5">
                <div class="label">Commandes totales</div>
                <div class="value"><?= $total_commandes ?></div>
                <div class="sub">toutes périodes</div>
                <i class="bi bi-bag-check icon"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card bg-grad-6">
                <div class="label">En attente</div>
                <div class="value"><?= $commandes_attente ?></div>
                <div class="sub">à traiter</div>
                <i class="bi bi-hourglass-split icon"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card bg-grad-3">
                <div class="label">Confirmées</div>
                <div class="value"><?= $commandes_confirmees ?></div>
                <div class="sub">commandes</div>
                <i class="bi bi-check-circle icon"></i>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="stat-card bg-grad-8">
                <div class="label">Demandes</div>
                <div class="value"><?= $total_demandes ?></div>
                <div class="sub">en attente</div>
                <i class="bi bi-person-plus icon"></i>
            </div>
        </div>
    </div>

    <!-- Row 3: line 60% + donut 40% -->
    <div class="row g-3 mb-3">
        <div class="col-12 col-lg-7">
            <div class="chart-card h-100">
                <h6><i class="bi bi-graph-up me-2" style="color:#7c3aed"></i>Commandes — 7 derniers jours</h6>
                <canvas id="chartLine" height="120"></canvas>
            </div>
        </div>
        <div class="col-12 col-lg-5">
            <div class="chart-card h-100 d-flex flex-column">
                <h6><i class="bi bi-pie-chart me-2" style="color:#f59e0b"></i>Statuts des commandes</h6>
                <div class="flex-grow-1 d-flex align-items-center justify-content-center">
                    <canvas id="chartDonut" style="max-height:200px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 4: pie 35% + bar 65% -->
    <div class="row g-3">
        <div class="col-12 col-lg-4">
            <div class="chart-card h-100 d-flex flex-column">
                <h6><i class="bi bi-pie-chart-fill me-2" style="color:#10b981"></i>Produits par catégorie</h6>
                <div class="flex-grow-1 d-flex align-items-center justify-content-center">
                    <canvas id="chartPie" style="max-height:220px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-8">
            <div class="chart-card h-100">
                <h6><i class="bi bi-bar-chart-fill me-2" style="color:#60a5fa"></i>Top 5 produits commandés</h6>
                <canvas id="chartBar" height="130"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════════════
     PRODUITS
═══════════════════════════════════════════════════════════════════════════ -->
<div class="table-section <?= $section==='produits' ? 'active':'' ?>">
    <div class="section-header d-flex justify-content-between align-items-center">
        <h5><i class="bi bi-box-seam me-2"></i>Produits</h5>
        <div class="d-flex gap-2 align-items-center">
            <form method="GET" class="d-flex gap-2">
                <input type="hidden" name="section" value="produits">
                <input type="text" name="search" class="search-box" placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>">
            </form>
            <a href="/controller/admin/add_produit.php" class="btn btn-sm" style="background:#7c3aed;color:#fff;border-radius:8px;font-size:12px;">+ Ajouter</a>
        </div>
    </div>
    <div style="background:#ffffff;border-radius:14px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,0.05);">
        <table class="table-dark-custom">
            <thead>
                <tr>
                    <th>#</th><th>Image</th><th>Libellé</th><th>Prix</th><th>Qté</th>
                    <th>Vendeur</th><th>Catégorie</th><th>Promo</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($produits)): ?>
                <tr><td colspan="9" class="text-center py-4" style="color:#666;">Aucun produit.</td></tr>
            <?php else: foreach ($produits as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><img src="/<?= htmlspecialchars($p['image']) ?>" class="prod-img" alt=""></td>
                    <td><?= htmlspecialchars($p['libelle']) ?></td>
                    <td><?= $p['prix'] ?> TND</td>
                    <td><?= $p['quantite'] ?></td>
                    <td><?= htmlspecialchars($p['vendeur_prenom'] . ' ' . $p['vendeur_nom']) ?></td>
                    <td><?= htmlspecialchars($p['categorie'] ?? '—') ?></td>
                    <td><?= $p['promo'] ? '<span class="badge-promo">Oui</span>' : '<span class="badge-no">Non</span>' ?></td>
                    <td>
                        <form method="POST" action="/controller/admin/delete_produit.php"
                              onsubmit="return confirm('Supprimer ce produit ?')" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $p['id'] ?>">
                            <button class="btn-del">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap"><?= count($produits) ?> résultat(s)</div>
</div>

<!-- ══════════════════════════════════════════════════════════════════════════
     CATÉGORIES
═══════════════════════════════════════════════════════════════════════════ -->
<div class="table-section <?= $section==='categories' ? 'active':'' ?>">
    <div class="section-header">
        <h5><i class="bi bi-tag me-2"></i>Catégories</h5>
    </div>
    <div style="background:#ffffff;border-radius:14px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,0.05);">
        <table class="table-dark-custom">
            <thead>
                <tr><th>#</th><th>Nom</th><th>Catégorie parente</th></tr>
            </thead>
            <tbody>
            <?php
            $all_cats = $crud_produit->getCategoriesFlat();
            $cat_map  = array_column($all_cats, 'nom', 'id');
            if (empty($all_cats)): ?>
                <tr><td colspan="3" class="text-center py-4" style="color:#666;">Aucune catégorie.</td></tr>
            <?php else: foreach ($all_cats as $cat): ?>
                <tr>
                    <td><?= $cat['id'] ?></td>
                    <td><?= htmlspecialchars($cat['nom']) ?></td>
                    <td><?= $cat['parent_id'] ? htmlspecialchars($cat_map[$cat['parent_id']] ?? '—') : '<span style="color:#666;">—</span>' ?></td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════════════
     VENDEURS
═══════════════════════════════════════════════════════════════════════════ -->
<div class="table-section <?= $section==='vendeurs' ? 'active':'' ?>">
    <div class="section-header">
        <h5><i class="bi bi-shop me-2"></i>Vendeurs</h5>
    </div>
    <div style="background:#ffffff;border-radius:14px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,0.05);">
        <table class="table-dark-custom">
            <thead>
                <tr><th>#</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Téléphone</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php if (empty($vendeurs)): ?>
                <tr><td colspan="6" class="text-center py-4" style="color:#666;">Aucun vendeur.</td></tr>
            <?php else: foreach ($vendeurs as $v): ?>
                <tr>
                    <td><?= $v['id'] ?></td>
                    <td><?= htmlspecialchars($v['nom']) ?></td>
                    <td><?= htmlspecialchars($v['prenom']) ?></td>
                    <td><?= htmlspecialchars($v['email']) ?></td>
                    <td><?= htmlspecialchars($v['tel']) ?></td>
                    <td>
                        <form method="POST" action="/controller/admin/delete_user.php"
                              onsubmit="return confirm('Supprimer ce vendeur ?')" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $v['id'] ?>">
                            <input type="hidden" name="from" value="vendeurs">
                            <button class="btn-del">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════════════
     CLIENTS
═══════════════════════════════════════════════════════════════════════════ -->
<div class="table-section <?= $section==='clients' ? 'active':'' ?>">
    <div class="section-header">
        <h5><i class="bi bi-people me-2"></i>Clients</h5>
    </div>
    <div style="background:#ffffff;border-radius:14px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,0.05);">
        <table class="table-dark-custom">
            <thead>
                <tr><th>#</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Téléphone</th><th>Action</th></tr>
            </thead>
            <tbody>
            <?php if (empty($clients)): ?>
                <tr><td colspan="6" class="text-center py-4" style="color:#666;">Aucun client.</td></tr>
            <?php else: foreach ($clients as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><?= htmlspecialchars($c['nom']) ?></td>
                    <td><?= htmlspecialchars($c['prenom']) ?></td>
                    <td><?= htmlspecialchars($c['email']) ?></td>
                    <td><?= htmlspecialchars($c['tel']) ?></td>
                    <td>
                        <form method="POST" action="/controller/admin/delete_user.php"
                              onsubmit="return confirm('Supprimer ce client ?')" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $c['id'] ?>">
                            <input type="hidden" name="from" value="clients">
                            <button class="btn-del">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════════════
     DEMANDES
═══════════════════════════════════════════════════════════════════════════ -->
<div class="table-section <?= $section==='demandes' ? 'active':'' ?>">
    <div class="section-header d-flex justify-content-between align-items-center">
        <h5><i class="bi bi-person-plus me-2"></i>Demandes vendeurs</h5>
        <form method="GET">
            <input type="hidden" name="section" value="demandes">
            <input type="text" name="search" class="search-box" placeholder="Rechercher..." value="<?= htmlspecialchars($search) ?>">
        </form>
    </div>
    <div style="background:#ffffff;border-radius:14px;border:1px solid #e5e7eb;overflow:hidden;box-shadow:0 1px 6px rgba(0,0,0,0.05);">
        <table class="table-dark-custom">
            <thead>
                <tr><th>#</th><th>Nom complet</th><th>Email</th><th>Téléphone</th><th>Type de produits</th><th>Description</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php if (empty($demandes_filtrees)): ?>
                <tr><td colspan="8" class="text-center py-4" style="color:#666;">Aucune demande en attente.</td></tr>
            <?php else: foreach ($demandes_filtrees as $d): ?>
                <tr>
                    <td><?= $d['id'] ?></td>
                    <td><?= htmlspecialchars($d['nom_complet'] ?: $d['prenom'] . ' ' . $d['nom']) ?></td>
                    <td><?= htmlspecialchars($d['email'] ?: '—') ?></td>
                    <td><?= htmlspecialchars($d['telephone'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($d['type_produits'] ?? '—') ?></td>
                    <td><?= htmlspecialchars(mb_strimwidth($d['message'] ?? '', 0, 40, '...')) ?></td>
                    <td><?= date('d/m/Y', strtotime($d['created_at'])) ?></td>
                    <td>
                        <div class="d-flex gap-2">
                            <form method="POST" action="/controller/admin/traiter_demande.php"
                                  onsubmit="return confirm('Accepter ?')">
                                <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                <input type="hidden" name="action" value="accepter">
                                <button class="btn-action btn-accept">✓ Accepter</button>
                            </form>
                            <form method="POST" action="/controller/admin/traiter_demande.php"
                                  onsubmit="return confirm('Refuser ?')">
                                <input type="hidden" name="id" value="<?= $d['id'] ?>">
                                <input type="hidden" name="action" value="refuser">
                                <button class="btn-action btn-refuse">✕ Refuser</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap"><?= count($demandes_filtrees) ?> résultat(s)</div>
</div>

</div><!-- /container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.color = '#6b7280';
Chart.defaults.borderColor = '#e5e7eb';

// ── Data ─────────────────────────────────────────────────────────────────────
const lineLabels = <?= json_encode(array_column($commandes_7j, 'jour')) ?>;
const lineData   = <?= json_encode(array_map('intval', array_column($commandes_7j, 'nb'))) ?>;

const donutLabels = ['En attente', 'Confirmées', 'Annulées'];
const donutData   = [<?= $commandes_attente ?>, <?= $commandes_confirmees ?>, <?= $commandes_annulees ?>];

const pieLabels = <?= json_encode(array_column($produits_par_categorie, 'categorie')) ?>;
const pieData   = <?= json_encode(array_map('intval', array_column($produits_par_categorie, 'nb'))) ?>;

const barLabels = <?= json_encode(array_column($top_produits, 'libelle')) ?>;
const barData   = <?= json_encode(array_map('intval', array_column($top_produits, 'total_cmd'))) ?>;

// ── Line chart ───────────────────────────────────────────────────────────────
new Chart(document.getElementById('chartLine'), {
    type: 'line',
    data: {
        labels: lineLabels.length ? lineLabels : ['—'],
        datasets: [{
            label: 'Commandes',
            data: lineData.length ? lineData : [0],
            borderColor: '#7c3aed',
            backgroundColor: 'rgba(124,58,237,0.12)',
            borderWidth: 2,
            pointRadius: 4,
            pointBackgroundColor: '#7c3aed',
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { color: '#f1f5f9' } },
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } }
        }
    }
});

// ── Donut chart ──────────────────────────────────────────────────────────────
new Chart(document.getElementById('chartDonut'), {
    type: 'doughnut',
    data: {
        labels: donutLabels,
        datasets: [{
            data: donutData,
            backgroundColor: ['#f59e0b', '#10b981', '#ef4444'],
            borderColor: '#ffffff',
            borderWidth: 3
        }]
    },
    options: {
        responsive: true,
        cutout: '68%',
        plugins: {
            legend: { position: 'bottom', labels: { color: '#aaa', padding: 16, font: { size: 12 } } }
        }
    }
});

// ── Pie chart ────────────────────────────────────────────────────────────────
new Chart(document.getElementById('chartPie'), {
    type: 'pie',
    data: {
        labels: pieLabels,
        datasets: [{
            data: pieData,
            backgroundColor: ['#7c3aed','#db2777','#2563eb','#059669','#d97706'],
            borderColor: '#ffffff',
            borderWidth: 3
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { color: '#aaa', padding: 12, font: { size: 11 } } }
        }
    }
});

// ── Bar chart ────────────────────────────────────────────────────────────────
new Chart(document.getElementById('chartBar'), {
    type: 'bar',
    data: {
        labels: barLabels.length ? barLabels : ['—'],
        datasets: [{
            label: 'Commandes',
            data: barData.length ? barData : [0],
            backgroundColor: ['#7c3aed','#db2777','#10b981','#f59e0b','#60a5fa'],
            borderRadius: 8,
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false } },
            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } }
        }
    }
});
</script>
</body>
</html>
