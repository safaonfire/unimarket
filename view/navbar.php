<?php
if (!isset($categories_tree)) {
    require_once __DIR__ . '/../model/CRUD_produit.php';
    $categories_tree = (new CRUD_produit())->getCategoriesTree();
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

<style>
.navbar-brand { 
    text-transform: none !important; 
    font-weight: 700 !important;
    font-size: 1.5rem;
}

.search-box { width: 40%; }

.account-dropdown { position: relative; cursor: pointer; margin-left: 20px; }

.account-trigger {
    display: flex;
    align-items: center;
    gap: 8px;
    color: black;
    font-size: 15px;
}

.account-menu {
    position: absolute; right: -10px; top: 50px; width: 220px;
    background: white; border: 1px solid #f0f0f0; display: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    z-index: 1000; text-align: center;
    border-radius: 16px;
    overflow: hidden;
}

.menu-top { padding: 15px; }

.btn-login {
    background-color: #1a1a1a; color: white; border: none;
    border-radius: 8px; padding: 10px; font-weight: 500;
    width: 100%; margin-bottom: 8px;
    display: block; text-decoration: none; text-align: center;
}

body { padding-top: 70px; }

#searchResults {
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    max-height: 300px;
    overflow-y: auto;
    background: white;
    border: 1px solid #ddd;
    border-radius: 10px;
    z-index: 999;
}
</style>

<nav class="navbar navbar-expand-lg bg-white border-bottom fixed-top">
  <div class="container-fluid">

    <!-- Sidebar button -->
    <button class="btn p-0 border-0 bg-transparent me-2" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu">
      ☰
    </button>

    <!-- Logo -->
    <a class="navbar-brand" href="/controller/home.php">UniMarket</a>

    <div class="collapse navbar-collapse">

        <!-- SEARCH -->
        <div class="search-box position-relative mx-auto">
            <input class="form-control rounded-pill" id="searchInput" placeholder="Rechercher...">
            <div id="searchResults"></div>
        </div>

        <?php
        $nb_panier = isset($_SESSION['panier']) ? array_sum($_SESSION['panier']) : 0;
        // Toujours compter les favoris depuis la DB si connecté
        $nb_favoris = 0;
        if (isset($_SESSION['user_id'])) {
            require_once __DIR__ . '/../connexion.php';
            $pdo = (new Connexion())->getConnexion();
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM favoris WHERE id_user = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $nb_favoris = (int)$stmt->fetchColumn();
            $_SESSION['favoris_count'] = $nb_favoris;
        }
        ?>

        <!-- Icône J'aime -->
        <a href="/controller/favoris/index.php" id="navLikeIcon" class="text-decoration-none me-2 position-relative" title="Mes j'aime">
            <i class="bi bi-heart fs-5" style="color:#333;"></i>
        </a>

        <!-- Icône Panier -->
        <a href="/controller/panier/voir.php" class="text-decoration-none me-2 position-relative" title="Mon panier">
            <i class="bi bi-cart3 fs-5" style="color:#333;"></i>
            <?php if ($nb_panier > 0): ?>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:10px;">
                    <?= $nb_panier ?>
                </span>
            <?php endif; ?>
        </a>

        <!-- ACCOUNT -->
        <div class="account-dropdown" id="accountDropdown">
            <div class="account-trigger">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php $photo_src = !empty($_SESSION['user_photo']) ? '/' . $_SESSION['user_photo'] : '/assets/logo.avif'; ?>
                    <div class="position-relative d-inline-block">
                        <img src="<?= $photo_src ?>" alt="profil" id="navProfilImg" style="width:32px; height:32px; border-radius:50%; object-fit:cover; cursor:pointer;">
                        <span id="navNotifBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                              style="font-size:10px; display:none;"></span>
                    </div>                <?php else: ?>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span>Connexion</span>
                <?php endif; ?>
            </div>

            <div class="account-menu" id="accountMenu">
                <div class="menu-top">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <!-- Nom cliquable → ouvre modal profil -->
                        <button type="button"
                                class="d-flex align-items-center gap-2 w-100 mb-3 p-2 rounded border-0 text-start"
                                style="background:#f0f0f0; cursor:pointer;"
                                data-bs-toggle="modal" data-bs-target="#modalProfil">
                            <img src="<?= $photo_src ?>" style="width:38px; height:38px; border-radius:50%; object-fit:cover;">
                            <div>
                                <div class="fw-bold" style="font-size:14px;"><?= htmlspecialchars($_SESSION['user_nom'] . ' ' . ($_SESSION['user_prenom'] ?? '')) ?></div>
                                <div style="font-size:11px; color:#888;">Modifier mon profil</div>
                            </div>
                        </button>
                        <?php if (in_array($_SESSION['user_role'] ?? '', ['client', 'admin'])): ?>
                            <a href="/controller/user/mes_commandes.php" class="btn-login" style="background:#f0f0f0; color:#1a1a1a; margin-bottom:8px;">
                                Mes commandes
                                <span id="navNotifBadgeMenu" class="badge bg-danger ms-1" style="display:none;"></span>
                            </a>
                        <?php endif; ?>
                        <?php if (($_SESSION['user_role'] ?? '') === 'admin'): ?>                            <a href="/controller/vendeur/dashboard.php" class="btn-login" style="background:#f0f0f0; color:#1a1a1a; margin-bottom:8px;">Mon dashboard</a>
                        <?php endif; ?>
                        <a href="/controller/user/logout.php" class="btn-login">Se déconnecter</a>
                    <?php else: ?>
                        <a href="/controller/user/login.php" class="btn-login">Se connecter</a>
                        <div class="signup-text">
                            Créer un compte <a href="/view/inscription.php" class="signup-link">S'inscrire</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
  </div>
</nav>

<!-- SIDEBAR -->
<div class="offcanvas offcanvas-start" id="sidebarMenu">
  <div class="offcanvas-header border-bottom">
    <h5>Catégories</h5>
    <button class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>

  <div class="offcanvas-body p-0">
    <div class="accordion accordion-flush" id="categoriesAccordion">

      <?php if (!empty($categories_tree)): ?>
        <?php foreach ($categories_tree as $i => $grande): ?>
          <div class="accordion-item border-0 border-bottom">

            <?php if (!empty($grande['children'])): ?>
              <!-- Grande catégorie avec sous-catégories -->
              <h2 class="accordion-header">
                <button class="accordion-button collapsed fw-semibold" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#cat-<?= $grande['id'] ?>">
                  <?= htmlspecialchars($grande['nom']) ?>
                </button>
              </h2>
              <div id="cat-<?= $grande['id'] ?>" class="accordion-collapse collapse"
                   data-bs-parent="#categoriesAccordion">
                <div class="accordion-body p-0">

                  <?php foreach ($grande['children'] as $j => $sous): ?>
                    <?php if (!empty($sous['children'])): ?>
                      <!-- Sous-catégorie avec enfants -->
                      <div class="border-bottom">
                        <button class="btn w-100 text-start px-4 py-2 fw-medium text-secondary"
                                style="background:none; border:none;"
                                data-bs-toggle="collapse"
                                data-bs-target="#subcat-<?= $sous['id'] ?>">
                          ➤ <?= htmlspecialchars($sous['nom']) ?>
                        </button>
                        <div id="subcat-<?= $sous['id'] ?>" class="collapse">
                          <?php foreach ($sous['children'] as $enfant): ?>
                            <a href="/controller/home.php?categorie=<?= $enfant['id'] ?>"
                               class="d-block px-5 py-2 text-dark text-decoration-none border-top"
                               style="font-size:0.9rem; background:#fafafa;">
                              <?= htmlspecialchars($enfant['nom']) ?>
                            </a>
                          <?php endforeach; ?>
                        </div>
                      </div>
                    <?php else: ?>
                      <!-- Sous-catégorie sans enfants -->
                      <a href="/controller/home.php?categorie=<?= $sous['id'] ?>"
                         class="d-block px-4 py-2 text-dark text-decoration-none border-bottom"
                         style="font-size:0.9rem;">
                        ➤ <?= htmlspecialchars($sous['nom']) ?>
                      </a>
                    <?php endif; ?>
                  <?php endforeach; ?>

                </div>
              </div>

            <?php else: ?>
              <!-- Grande catégorie sans sous-catégories -->
              <a href="/controller/home.php?categorie=<?= $grande['id'] ?>"
                 class="d-block px-3 py-3 fw-semibold text-dark text-decoration-none">
                <?= htmlspecialchars($grande['nom']) ?>
              </a>
            <?php endif; ?>

          </div>
        <?php endforeach; ?>
      <?php endif; ?>

    </div>
  </div>

  <?php
  if (isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'client'):
      require_once __DIR__ . '/../connexion.php';
      $pdo_dv  = (new Connexion())->getConnexion();
      $stmt_dv = $pdo_dv->prepare("SELECT statut FROM demande_vendeur WHERE id_user = ? ORDER BY created_at DESC LIMIT 1");
      $stmt_dv->execute([$_SESSION['user_id']]);
      $dv = $stmt_dv->fetch();
  ?>
  <div class="p-3 border-top">
      <?php if (!$dv): ?>
          <button class="btn btn-dark w-100 btn-sm" data-bs-toggle="modal" data-bs-target="#modalDemandeVendeur">
               Lancer mes ventes
          </button>
      <?php elseif ($dv['statut'] === 'en_attente'): ?>
          <div class="text-center text-muted small py-2">Demande en attente de validation</div>
          <a href="/controller/vendeur/delete_demande.php"
             class="btn btn-outline-danger w-100 btn-sm mt-1"
             onclick="return confirm('Annuler votre demande ?')">Annuler la demande</a>
      <?php elseif ($dv['statut'] === 'refusee'): ?>
          <button class="btn btn-outline-dark w-100 btn-sm" data-bs-toggle="modal" data-bs-target="#modalDemandeVendeur">
               Renvoyer une demande
          </button>
      <?php endif; ?>
  </div>
  <?php endif; ?>

</div>

<!-- Modal demande vendeur -->
<?php if (isset($_SESSION['user_id']) && ($_SESSION['user_role'] ?? '') === 'client'): ?>
<div class="modal fade" id="modalDemandeVendeur" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Devenir vendeur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/controller/vendeur/demande.php">
                <div class="modal-body">
                    <?php if (!empty($_GET['demande'])): ?>
                        <?php if ($_GET['demande'] === 'sent'): ?>
                            <div class="alert alert-success py-2">Demande envoyée avec succès !</div>
                        <?php elseif ($_GET['demande'] === 'already'): ?>
                            <div class="alert alert-warning py-2">Vous avez déjà une demande en attente.</div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Nom complet</label>
                            <input type="text" name="nom_complet" class="form-control"
                                   value="<?= htmlspecialchars(($_SESSION['user_prenom'] ?? '') . ' ' . ($_SESSION['user_nom'] ?? '')) ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Téléphone</label>
                            <input type="text" name="telephone" class="form-control"
                                   value="<?= htmlspecialchars($_SESSION['user_tel'] ?? '') ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Type de produits</label>
                            <input type="text" name="type_produits" class="form-control"
                                   placeholder="Ex: Bijoux, sacs crochet, stickers..." required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Description de l'activité</label>
                            <textarea name="message" class="form-control" rows="3"
                                      placeholder="Décrivez votre activité..." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-dark w-100">Envoyer la demande</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// ACCOUNT DROPDOWN
const dropdown = document.getElementById("accountDropdown");
const menu = document.getElementById("accountMenu");

dropdown?.addEventListener("click", (e) => {
    e.stopPropagation();
    menu.style.display = menu.style.display === "block" ? "none" : "block";

    // Marquer les notifications comme lues quand on ouvre le menu
    const badge = document.getElementById('navNotifBadge');
    if (badge && badge.style.display !== 'none') {
        fetch('/controller/notification/mark_read.php', { method: 'POST' })
            .then(() => {
                badge.style.display = 'none';
                const badgeMenu = document.getElementById('navNotifBadgeMenu');
                if (badgeMenu) badgeMenu.style.display = 'none';
            });
    }
});

document.addEventListener("click", () => menu.style.display = "none");


// SEARCH
const input = document.getElementById("searchInput");
const results = document.getElementById("searchResults");

input?.addEventListener("keyup", function() {
    let value = this.value.trim();

    if (value.length === 0) {
        results.innerHTML = "";
        return;
    }

    fetch("/controller/search.php?search=" + encodeURIComponent(value))
        .then(res => res.text())
        .then(data => results.innerHTML = data);
});

document.addEventListener("click", function(e) {
    if (input && results && !input.contains(e.target) && !results.contains(e.target)) {
        results.innerHTML = "";
    }
});

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewPhoto').src = e.target.result;
            document.getElementById('navProfilImg').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function supprimerPhoto() {
    const defaultSrc = '/assets/logo.avif';
    document.getElementById('previewPhoto').src = defaultSrc;
    document.getElementById('navProfilImg').src = defaultSrc;
    document.getElementById('deletePhotoInput').value = '1';
    // Vider le file input au cas où
    document.getElementById('inputPhoto').value = '';
}
</script>
<?php if (isset($_SESSION['user_id'])): ?>
<script src="/assets/chat.js"></script>
<?php endif; ?>

<?php include __DIR__ . '/partials/footer.php'; ?>

<?php if (isset($_SESSION['user_id'])): ?>
<!-- Modal Modifier Profil -->
<div class="modal fade" id="modalProfil" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Mon profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/controller/user/update_profil.php" enctype="multipart/form-data">
                <div class="modal-body">
                    <?php if (!empty($_GET['profil_ok'])): ?>
                        <div class="alert alert-success py-2" id="profilSuccessMsg">Profil mis à jour !</div>
                    <?php elseif (!empty($_GET['profil_error'])): ?>
                        <div class="alert alert-danger py-2">
                            <?= $_GET['profil_error'] === 'email' ? 'Cet email est déjà utilisé.' : 'Veuillez remplir tous les champs.' ?>
                        </div>
                    <?php endif; ?>

                    <div class="row g-3">
                        <!-- Photo de profil -->
                        <div class="col-12 text-center">
                            <img src="<?= $photo_src ?>" id="previewPhoto"
                                 style="width:80px; height:80px; border-radius:50%; object-fit:cover; cursor:pointer; border:3px solid #f0f0f0;"
                                 onclick="document.getElementById('inputPhoto').click()">
                            <div style="font-size:12px; color:#888; margin-top:6px;">Cliquer pour changer la photo</div>
                            <input type="file" name="photo" id="inputPhoto" accept="image/*" style="display:none;"
                                   onchange="previewImage(this)">
                            <?php if (!empty($_SESSION['user_photo'])): ?>
                                <button type="button" class="btn btn-sm btn-outline-danger mt-2"
                                        onclick="supprimerPhoto()">
                                    <i class="bi bi-trash me-1"></i>Supprimer la photo
                                </button>
                                <input type="hidden" name="delete_photo" id="deletePhotoInput" value="0">
                            <?php endif; ?>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Nom</label>
                            <input type="text" name="nom" class="form-control"
                                   value="<?= htmlspecialchars($_SESSION['user_nom'] ?? '') ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Prénom</label>
                            <input type="text" name="prenom" class="form-control"
                                   value="<?= htmlspecialchars($_SESSION['user_prenom'] ?? '') ?>" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control"
                                   value="<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Téléphone</label>
                            <input type="text" name="tel" class="form-control"
                                   value="<?= htmlspecialchars($_SESSION['user_tel'] ?? '') ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-semibold">Adresse</label>
                            <input type="text" name="adresse" class="form-control"
                                   value="<?= htmlspecialchars($_SESSION['user_adresse'] ?? '') ?>">
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

<?php if (!empty($_GET['profil_ok'])): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new bootstrap.Modal(document.getElementById('modalProfil')).show();
        // Clean URL
        const url = new URL(window.location.href);
        url.searchParams.delete('profil_ok');
        window.history.replaceState({}, '', url);
        // Auto-hide message after 3s
        setTimeout(() => {
            const msg = document.getElementById('profilSuccessMsg');
            if (msg) msg.style.display = 'none';
        }, 3000);
    });
</script>
<?php endif; ?>
<?php endif; ?>





