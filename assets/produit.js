// Charger les favoris existants depuis la DB
document.addEventListener('DOMContentLoaded', function () {
    fetch('/controller/favoris/get.php')
        .then(r => r.json())
        .then(ids => {
            ids.forEach(id => {
                const btn = document.querySelector('[data-id="' + id + '"]');
                if (btn) {
                    const icon = btn.querySelector('i');
                    if (icon) {
                        icon.classList.remove('bi-heart');
                        icon.classList.add('bi-heart-fill');
                    }
                }
            });
            updateNavLikeBadge(ids.length);
        })
        .catch(() => { });
});

function updateNavLikeBadge(count) {
    const badge = document.getElementById('navLikeBadge');
    if (!badge) return;
    if (count > 0) {
        badge.textContent = count;
        badge.style.display = '';
    } else {
        badge.style.display = 'none';
    }
}

function toggleFavori(id_produit, btn) {
    if (!isLoggedIn) {
        window.location.href = '/controller/user/login.php?redirect=' + encodeURIComponent(window.location.pathname);
        return;
    }
    fetch('/controller/favoris/toggle.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id_produit=' + id_produit
    })
        .then(r => r.json())
        .then(data => {
            const icon = btn.querySelector('i');
            const counter = document.querySelector('.nb-likes-' + id_produit);
            const badge = document.getElementById('navLikeBadge');
            const current = badge ? (parseInt(badge.textContent) || 0) : 0;

            if (data.status === 'added') {
                if (icon) { icon.classList.remove('bi-heart'); icon.classList.add('bi-heart-fill'); }
                updateNavLikeBadge(current + 1);
            } else {
                if (icon) { icon.classList.remove('bi-heart-fill'); icon.classList.add('bi-heart'); }
                updateNavLikeBadge(Math.max(0, current - 1));
            }
            // Utiliser le vrai total depuis la DB
            if (counter && data.nb_likes !== undefined) counter.textContent = data.nb_likes;
        });
}

function ajouterPanier(id_produit, btn) {
    // BUG FIX #17: les visiteurs non connectés peuvent aussi utiliser le panier —
    // ne pas rediriger vers login ici. La redirection se fait uniquement au checkout.
    fetch('/controller/panier/index.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'action=ajouter&id_produit=' + id_produit + '&quantite=1'
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
                // Badge panier navbar
                const badge = document.querySelector('a[href="/controller/panier/voir.php"] .badge');
                if (badge) {
                    badge.textContent = data.total;
                } else {
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

