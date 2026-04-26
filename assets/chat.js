const messagesCharges = {};
const chatOuverts = {};

function chargerMessages(id_commande) {
    const btn = document.getElementById('btn-chat-' + id_commande);
    if (btn) { btn.classList.remove('btn-msg-unread'); btn.classList.add('btn-outline-dark'); }
    const badge = document.getElementById('badge-chat-' + id_commande);
    if (badge) badge.classList.add('d-none');

    fetch('/controller/message/get.php?id_commande=' + id_commande)
        .then(r => r.json())
        .then(messages => {
            const zone = document.getElementById('messages-' + id_commande);
            if (!zone) return;
            if (messages.length === 0) {
                zone.innerHTML = '<div class="text-muted small text-center py-3">Aucun message pour le moment.</div>';
            } else {
                zone.innerHTML = '';
                let lastDate = '';
                messages.forEach(m => {
                    if (m.date !== lastDate) {
                        zone.innerHTML += `<div class="text-center text-muted" style="font-size:11px;">${m.date}</div>`;
                        lastDate = m.date;
                    }
                    zone.innerHTML += bulleMessage(m);
                });
                zone.scrollTop = zone.scrollHeight;
            }
            messagesCharges[id_commande] = true;
            if (badge) badge.classList.add('d-none');
            verifierNonLus();
            if (!chatOuverts[id_commande]) {
                chatOuverts[id_commande] = setInterval(() => {
                    const chatDiv = document.getElementById('chat-' + id_commande);
                    if (!chatDiv || !chatDiv.classList.contains('show')) {
                        clearInterval(chatOuverts[id_commande]);
                        delete chatOuverts[id_commande];
                        return;
                    }
                    pollMessages(id_commande);
                }, 5000);
            }
        });
}

function pollMessages(id_commande) {
    fetch('/controller/message/get.php?id_commande=' + id_commande)
        .then(r => r.json())
        .then(messages => {
            const zone = document.getElementById('messages-' + id_commande);
            if (!zone || messages.length === 0) return;
            zone.innerHTML = '';
            let lastDate = '';
            messages.forEach(m => {
                if (m.date !== lastDate) {
                    zone.innerHTML += `<div class="text-center text-muted" style="font-size:11px;">${m.date}</div>`;
                    lastDate = m.date;
                }
                zone.innerHTML += bulleMessage(m);
            });
            zone.scrollTop = zone.scrollHeight;
            verifierNonLus();
        });
}

function bulleMessage(m) {
    const align = m.moi ? 'flex-end' : 'flex-start';
    const bg = m.moi ? '#1a1a1a' : '#f0f0f0';
    const color = m.moi ? '#fff' : '#1a1a1a';
    const nom = m.moi ? 'Vous' : m.expediteur;
    return `<div style="display:flex;flex-direction:column;align-items:${align};max-width:80%;align-self:${align};">
        <div style="font-size:11px;color:#999;margin-bottom:2px;">${nom} · ${m.heure}</div>
        <div style="background:${bg};color:${color};padding:8px 12px;border-radius:12px;font-size:13px;word-break:break-word;">${m.contenu}</div>
    </div>`;
}

function envoyerMessage(id_commande) {
    const input = document.getElementById('input-' + id_commande);
    const contenu = input.value.trim();
    if (!contenu) return;
    input.value = '';
    fetch('/controller/message/send.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'id_commande=' + id_commande + '&contenu=' + encodeURIComponent(contenu)
    }).then(r => r.json()).then(data => {
        if (data.success) {
            const zone = document.getElementById('messages-' + id_commande);
            if (zone.querySelector('.text-center.py-3')) zone.innerHTML = '';
            zone.innerHTML += bulleMessage(data);
            zone.scrollTop = zone.scrollHeight;
        }
    });
}

// ── Unread messages badge ──────────────────────────────────────────────────
const _toastedMessages = new Set();

function verifierNonLus() {
    fetch('/controller/message/unread.php')
        .then(r => r.json())
        .then(data => {
            // Vendor bell badge
            const cloche = document.getElementById('navBellBadge');
            if (cloche) {
                fetch('/controller/notification/get.php')
                    .then(r => r.json())
                    .then(notifs => {
                        const total = parseInt(cloche.dataset.commandes || 0) + data.count;
                        cloche.textContent = total;
                        cloche.style.display = total > 0 ? '' : 'none';
                    });

                // Message toast for vendor
                if (data.unread_commandes) {
                    data.unread_commandes.forEach(id_commande => {
                        const key = 'msg-' + id_commande;
                        const chatDiv = document.getElementById('chat-' + id_commande);
                        if (!_toastedMessages.has(key) && !(chatDiv && chatDiv.classList.contains('show'))) {
                            _toastedMessages.add(key);
                            const detail = data.details && data.details[id_commande];
                            const contenu = detail
                                ? `💬 Nouveau message reçu :<br>de ${detail.nom} <span style="color:#ccc">"${detail.message}"</span>`
                                : '💬 Nouveau message reçu.';
                            afficherToast(contenu, () => {
                                const btn = document.getElementById('btn-chat-' + id_commande);
                                if (btn) {
                                    // Already on the right page
                                    if (!document.getElementById('chat-' + id_commande).classList.contains('show')) btn.click();
                                    btn.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                } else {
                                    window.location.href = '/controller/vendeur/commandes.php#chat-' + id_commande;
                                }
                            }, key, false);
                        }
                    });
                }
            }

            // Message toast for client
            if (!document.getElementById('navBellBadge') && data.unread_commandes) {
                data.unread_commandes.forEach(id_commande => {
                    const key = 'msg-' + id_commande;
                    const chatDiv = document.getElementById('chat-' + id_commande);
                    if (!_toastedMessages.has(key) && !(chatDiv && chatDiv.classList.contains('show'))) {
                        _toastedMessages.add(key);
                        const detail = data.details && data.details[id_commande];
                        const contenu = detail
                            ? `💬 Nouveau message reçu :<br>de ${detail.nom} <span style="color:#ccc">"${detail.message}"</span>`
                            : '💬 Nouveau message reçu.';
                        afficherToast(contenu, () => {
                            const btn = document.getElementById('btn-chat-' + id_commande);
                            if (btn) {
                                if (!document.getElementById('chat-' + id_commande).classList.contains('show')) btn.click();
                                btn.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            } else {
                                window.location.href = '/controller/user/mes_commandes.php#chat-' + id_commande;
                            }
                        }, key, false);
                    }
                });
            }

            // Animate unread chat buttons
            if (data.unread_commandes) {
                data.unread_commandes.forEach(id_commande => {
                    const btn = document.getElementById('btn-chat-' + id_commande);
                    if (btn) {
                        btn.classList.add('btn-msg-unread');
                        btn.classList.remove('btn-outline-dark');
                        const badge = document.getElementById('badge-chat-' + id_commande);
                        if (badge) { badge.textContent = data.counts[id_commande] || ''; badge.classList.remove('d-none'); }
                    }
                });
                _toastedMessages.forEach(key => {
                    if (key.startsWith('msg-') && !data.unread_commandes.includes(parseInt(key.replace('msg-', ''))))
                        _toastedMessages.delete(key);
                });
            } else {
                _toastedMessages.forEach(key => { if (key.startsWith('msg-')) _toastedMessages.delete(key); });
            }
        }).catch(() => { });
}

verifierNonLus();
setInterval(verifierNonLus, 30000);

// ── Notifications (likes + order status) ──────────────────────────────────
function verifierNotifications() {
    fetch('/controller/notification/get.php')
        .then(r => r.json())
        .then(data => {
            // Client badge
            const badge = document.getElementById('navNotifBadge');
            const badgeMenu = document.getElementById('navNotifBadgeMenu');
            if (badge) {
                badge.textContent = data.count;
                badge.style.display = data.count > 0 ? '' : 'none';
                if (badgeMenu) { badgeMenu.textContent = data.count; badgeMenu.style.display = data.count > 0 ? '' : 'none'; }
            }

            // Vendor heart badge
            const heartBadge = document.getElementById('navHeartBadge');
            if (heartBadge) {
                heartBadge.textContent = data.count;
                heartBadge.style.display = data.count > 0 ? '' : 'none';
            }

            // Toasts
            if (data.items) {
                data.items.forEach(n => {
                    if (document.getElementById('notif-' + n.id)) return;
                    const isVendeur = !!document.getElementById('navBellBadge');
                    if (isVendeur) {
                        // Vendor like notification — not clickable
                        afficherToast(n.contenu, null, 'notif-' + n.id);
                    } else {
                        // Client: clicking goes to mes commandes
                        afficherToast(n.contenu, () => {
                            window.location.href = '/controller/user/mes_commandes.php';
                        }, 'notif-' + n.id);
                    }
                });
            }
        }).catch(() => { });
}

// ── Simple toast ───────────────────────────────────────────────────────────
function afficherToast(contenu, onClick, id, autoClose = true) {
    if (id && document.getElementById('toast-' + id)) return;
    let container = document.getElementById('toastContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toastContainer';
        container.style.cssText = 'position:fixed;bottom:60px;right:20px;z-index:9999;display:flex;flex-direction:column;gap:10px;';
        document.body.appendChild(container);
    }
    const toast = document.createElement('div');
    if (id) toast.id = 'toast-' + id;
    toast.style.cssText = 'background:#1a1a1a;color:white;padding:12px 40px 12px 16px;border-radius:12px;max-width:300px;font-size:13px;box-shadow:0 4px 15px rgba(0,0,0,0.3);position:relative;';

    const closeBtn = document.createElement('button');
    closeBtn.innerHTML = '✕';
    closeBtn.style.cssText = 'position:absolute;top:8px;right:10px;background:none;border:none;color:#aaa;font-size:14px;cursor:pointer;';
    closeBtn.onclick = (e) => { e.stopPropagation(); toast.remove(); };

    const text = document.createElement('div');
    text.innerHTML = contenu;
    if (onClick) {
        text.style.cursor = 'pointer';
        text.onclick = () => { toast.remove(); onClick(); };
    }

    toast.appendChild(closeBtn);
    toast.appendChild(text);
    container.appendChild(toast);
    if (autoClose) setTimeout(() => { if (toast.parentNode) toast.remove(); }, 6000);
}

// ── Auto-open chat from hash ───────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const hash = window.location.hash;
    const match = hash.match(/^#chat-(\d+)$/);
    if (match) {
        setTimeout(() => {
            const btn = document.getElementById('btn-chat-' + match[1]);
            if (btn) { btn.click(); btn.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
        }, 400);
    }
});

verifierNotifications();
setInterval(verifierNotifications, 15000);
