<style>
@keyframes pulse-orange {
    0%   { box-shadow: 0 0 0 0 rgba(255,165,0,0.6); }
    70%  { box-shadow: 0 0 0 8px rgba(255,165,0,0); }
    100% { box-shadow: 0 0 0 0 rgba(255,165,0,0); }
}
.btn-msg-unread {
    background: #fff3cd !important;
    border-color: #ffc107 !important;
    color: #856404 !important;
    animation: pulse-orange 1.5s infinite;
}
</style>
<?php $cmd_id = $cmd['id']; ?>
<div class="mt-3">
    <button class="btn btn-sm btn-outline-dark w-100" id="btn-chat-<?= $cmd_id ?>"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#chat-<?= $cmd_id ?>"
            onclick="chargerMessages(<?= $cmd_id ?>)">
        <i class="bi bi-chat-dots me-1"></i>Messages
        <span class="badge bg-danger ms-1 d-none" id="badge-chat-<?= $cmd_id ?>"></span>
    </button>

    <div class="collapse mt-2" id="chat-<?= $cmd_id ?>">
        <div class="border rounded" style="background:#fff;">
            <!-- Zone messages -->
            <div id="messages-<?= $cmd_id ?>"
                 style="height:200px; overflow-y:auto; padding:12px; display:flex; flex-direction:column; gap:8px;">
                <div class="text-muted small text-center">Chargement...</div>
            </div>

            <!-- Saisie -->
            <div class="border-top p-2 d-flex gap-2">
                <input type="text"
                       id="input-<?= $cmd_id ?>"
                       class="form-control form-control-sm"
                       placeholder="Écrire un message..."
                       onkeydown="if(event.key==='Enter') envoyerMessage(<?= $cmd_id ?>)">
                <button class="btn btn-dark btn-sm px-3" onclick="envoyerMessage(<?= $cmd_id ?>)">
                    <i class="bi bi-send"></i>
                </button>
            </div>
        </div>
    </div>
</div>





