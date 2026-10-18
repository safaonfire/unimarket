<?php
if (!isset($nb_notifications)) {
    require_once __DIR__ . '/../../model/CRUD_commande.php';
    $crudNav = new CRUD_commande();
    $commandesNav = $crudNav->getCommandesByVendeur($_SESSION['user_id']);
    $nb_notifications = 0;
    foreach ($commandesNav as $cmd) {
        if ($cmd['statut'] === 'en_attente') $nb_notifications++;
    }
}
?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

<style>
.navbar-vendeur {
    background-color: #1a1a1a;
    height: 55px;
    padding: 0 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.navbar-vendeur > a {
    color: white;
    text-decoration: none;
    font-size: 23px;
    font-weight: bold;
}
.navbar-center {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
}
.welcome-text { font-size: 16px; color: #ccc; }
.btn-custom {
    font-size: 16px;
    background-color: white;
    color: black !important;
    border: 1px solid white;
    transition: 0.2s;
}
.btn-custom:hover {
    background-color: black;
    color: white !important;
    border: 1px solid white;
}
</style>

<nav class="navbar-vendeur fixed-top">
    <a href="/controller/home.php">UniMarket</a>

    <div class="navbar-center">
        <div class="welcome-text">
            Bienvenue, <strong><?= htmlspecialchars($_SESSION['user_nom']) ?></strong>
        </div>
    </div>

    <div class="d-flex gap-3 align-items-center">

        <!-- Icône coeur : notifications j'aime -->
        <div class="position-relative" style="cursor:pointer;" id="vendeurHeartBtn">
            <i class="bi bi-heart" style="font-size:20px; color:white;"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge bg-danger" id="navHeartBadge"
                  style="font-size:10px; display:none;"></span>
            <!-- Panel dropdown -->
            <div id="heartPanel" style="display:none; position:absolute; right:-10px; top:45px;
                 background:white; border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,0.15);
                 width:280px; z-index:1000; overflow:hidden; max-height:320px; overflow-y:auto;">
                <div style="padding:12px 14px; font-weight:600; font-size:13px; border-bottom:1px solid #f0f0f0; color:#1a1a1a;">
                    ❤️ J'aimes et commentaires reçus
                </div>
                <div id="heartList"></div>
            </div>
        </div>

        <!-- Icône cloche : commandes en attente + messages non lus -->
        <a href="/controller/vendeur/commandes.php" class="btn btn-custom position-relative"
           style="border-radius:50%; width:34px; height:34px; display:flex; align-items:center; justify-content:center; padding:0;">
            <i class="bi bi-bell" style="font-size:18px;"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge bg-danger" id="navBellBadge"
                  data-commandes="<?= $nb_notifications ?>"
                  style="<?= ($nb_notifications > 0) ? '' : 'display:none;' ?>">
                <?= $nb_notifications ?>
            </span>
        </a>

        <!-- Avatar + menu -->
        <?php $vendeur_photo = !empty($_SESSION['user_photo']) ? '/' . $_SESSION['user_photo'] : '/assets/logo.avif'; ?>
        <div style="position:relative;">
            <img src="<?= $vendeur_photo ?>" id="vendeurLogoBtn"
                 style="width:34px; height:34px; border-radius:50%; object-fit:cover; cursor:pointer;">

            <div id="vendeurMenu" style="display:none; position:absolute; right:0; top:45px;
                 background:white; border-radius:16px; box-shadow:0 10px 30px rgba(0,0,0,0.15);
                 width:220px; z-index:1000; overflow:hidden; text-align:center; padding:15px;">

                <div style="display:flex; align-items:center; gap:10px; background:#f0f0f0; border-radius:10px; padding:10px; margin-bottom:10px; cursor:pointer;"
                     onclick="document.getElementById('vendeurMenu').style.display='none'; new bootstrap.Modal(document.getElementById('modalProfilVendeur')).show();">
                    <img src="<?= $vendeur_photo ?>" id="vendeurAvatarMenu" style="width:38px; height:38px; border-radius:50%; object-fit:cover;">
                    <div style="text-align:left;">
                        <div style="font-weight:bold; color:#1a1a1a; font-size:14px;"><?= htmlspecialchars($_SESSION['user_nom'] . ' ' . ($_SESSION['user_prenom'] ?? '')) ?></div>
                        <div style="font-size:11px; color:#888;">Modifier mon profil</div>
                    </div>
                </div>

                <a href="/controller/vendeur/commandes.php"
                   style="display:block; background:#f0f0f0; color:#1a1a1a; padding:10px;
                          border-radius:8px; text-decoration:none; font-size:14px; margin-bottom:8px;">
                     Commandes à préparer
                </a>

                <a href="/controller/user/logout.php"
                   style="display:block; background:#1a1a1a; color:white; padding:10px;
                          border-radius:8px; text-decoration:none; font-size:14px;">
                    Se déconnecter
                </a>
            </div>
        </div>

    </div>
</nav>

<!-- Modal Profil Vendeur -->
<div class="modal fade" id="modalProfilVendeur" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Mon profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/controller/user/update_profil.php" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Photo -->
                        <div class="col-12 text-center">
                            <img src="<?= $vendeur_photo ?>" id="previewPhotoVendeur"
                                 style="width:80px; height:80px; border-radius:50%; object-fit:cover; cursor:pointer; border:3px solid #f0f0f0;"
                                 onclick="document.getElementById('inputPhotoVendeur').click()">
                            <div style="font-size:12px; color:#888; margin-top:6px;">Cliquer pour changer la photo</div>
                            <input type="file" name="photo" id="inputPhotoVendeur" accept="image/*" style="display:none;"
                                   onchange="previewImageVendeur(this)">
                            <?php if (!empty($_SESSION['user_photo'])): ?>
                                <button type="button" class="btn btn-sm btn-outline-danger mt-2"
                                        onclick="supprimerPhotoVendeur()">
                                    <i class="bi bi-trash me-1"></i>Supprimer la photo
                                </button>
                                <input type="hidden" name="delete_photo" id="deletePhotoVendeurInput" value="0">
                            <?php endif; ?>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Nom</label>
                            <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($_SESSION['user_nom'] ?? '') ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Prénom</label>
                            <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Téléphone</label>
                            <input type="text" name="tel" class="form-control" value="<?= htmlspecialchars($_SESSION['user_tel'] ?? '') ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Adresse</label>
                            <input type="text" name="adresse" class="form-control" value="<?= htmlspecialchars($_SESSION['user_adresse'] ?? '') ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-dark w-100">
                        <i class="bi bi-check-lg me-1"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const vendeurBtn  = document.getElementById('vendeurLogoBtn');
const vendeurMenu = document.getElementById('vendeurMenu');
const heartBtn    = document.getElementById('vendeurHeartBtn');
const heartPanel  = document.getElementById('heartPanel');

vendeurBtn?.addEventListener('click', function(e) {
    e.stopPropagation();
    vendeurMenu.style.display = vendeurMenu.style.display === 'block' ? 'none' : 'block';
    if (heartPanel) heartPanel.style.display = 'none';
    // Don't mark_read here — only mark read when heart panel is opened
});

heartBtn?.addEventListener('click', function(e) {
    e.stopPropagation();
    const isOpen = heartPanel.style.display === 'block';
    heartPanel.style.display = isOpen ? 'none' : 'block';
    if (vendeurMenu) vendeurMenu.style.display = 'none';

    if (!isOpen) {
        // Charger l'historique complet des notifications j'aime
        fetch('/controller/notification/history.php')
            .then(r => r.json())
            .then(items => {
                const list = document.getElementById('heartList');
                if (!list) return;
                if (items.length > 0) {
                    list.innerHTML = items.map(n =>
                        `<div style="padding:10px 12px; border-bottom:1px solid #f0f0f0; font-size:13px; color:${n.lu == 1 ? '#888' : '#1a1a1a'};">
                            ${n.contenu}
                            <div style="font-size:11px; color:#bbb; margin-top:3px;">${n.created_at}</div>
                        </div>`
                    ).join('');
                } else {
                    list.innerHTML = '<div style="padding:12px; color:#aaa; font-size:13px; text-align:center;">Aucune notification</div>';
                }
                // Marquer comme lus
                fetch('/controller/notification/mark_read.php', { method: 'POST' }).then(() => {
                    const badge = document.getElementById('navHeartBadge');
                    if (badge) badge.style.display = 'none';
                });
            });
    }
});

document.addEventListener('click', function() {
    if (vendeurMenu) vendeurMenu.style.display = 'none';
    if (heartPanel) heartPanel.style.display = 'none';
});

function previewImageVendeur(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewPhotoVendeur').src = e.target.result;
            document.getElementById('vendeurLogoBtn').src = e.target.result;
            document.getElementById('vendeurAvatarMenu').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function supprimerPhotoVendeur() {
    const def = '/assets/logo.avif';
    document.getElementById('previewPhotoVendeur').src = def;
    document.getElementById('vendeurLogoBtn').src = def;
    document.getElementById('vendeurAvatarMenu').src = def;
    document.getElementById('deletePhotoVendeurInput').value = '1';
    document.getElementById('inputPhotoVendeur').value = '';
}
</script>
<script src="/assets/chat.js"></script>





