<?php
session_start();
require_once __DIR__ . '/../../connexion.php';

if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

$pdo     = (new Connexion())->getConnexion();
$user_id = $_SESSION['user_id'] ?? null;

function syncPanierDB($pdo, $user_id, $panier) {
    if (!$user_id) return;
    // Supprimer les articles retirés
    $pdo->prepare("DELETE FROM panier WHERE id_user = ?")->execute([$user_id]);
    $upsert = $pdo->prepare("INSERT INTO panier (id_user, id_produit, quantite) VALUES (?, ?, ?)");
    foreach ($panier as $id_produit => $qte) {
        $upsert->execute([$user_id, $id_produit, $qte]);
    }
}

// Ajouter au panier
if (isset($_POST['action']) && $_POST['action'] === 'ajouter') {
    $id  = (int)$_POST['id_produit'];
    $qte = max(1, (int)($_POST['quantite'] ?? 1));

    if (isset($_SESSION['panier'][$id])) {
        $_SESSION['panier'][$id] += $qte;
    } else {
        $_SESSION['panier'][$id] = $qte;
    }

    if ($user_id) {
        $pdo->prepare("INSERT INTO panier (id_user, id_produit, quantite) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantite = VALUES(quantite)")
            ->execute([$user_id, $id, $_SESSION['panier'][$id]]);
    }

    echo json_encode(['success' => true, 'total' => array_sum($_SESSION['panier'])]);
    exit;
}

// Augmenter quantité
if (isset($_POST['action']) && $_POST['action'] === 'augmenter') {
    $id        = (int)$_POST['id_produit'];
    $stock_max = (int)($_POST['stock_max'] ?? 999);
    if (isset($_SESSION['panier'][$id]) && $_SESSION['panier'][$id] < $stock_max) {
        $_SESSION['panier'][$id]++;
        if ($user_id) {
            $pdo->prepare("INSERT INTO panier (id_user, id_produit, quantite) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantite = VALUES(quantite)")
                ->execute([$user_id, $id, $_SESSION['panier'][$id]]);
        }
    }
    header("Location: /controller/panier/voir.php");
    exit;
}

// Diminuer quantité
if (isset($_POST['action']) && $_POST['action'] === 'diminuer') {
    $id = (int)$_POST['id_produit'];
    if (isset($_SESSION['panier'][$id])) {
        $_SESSION['panier'][$id]--;
        if ($_SESSION['panier'][$id] <= 0) {
            unset($_SESSION['panier'][$id]);
            if ($user_id) {
                $pdo->prepare("DELETE FROM panier WHERE id_user = ? AND id_produit = ?")->execute([$user_id, $id]);
            }
        } else {
            if ($user_id) {
                $pdo->prepare("INSERT INTO panier (id_user, id_produit, quantite) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantite = VALUES(quantite)")
                    ->execute([$user_id, $id, $_SESSION['panier'][$id]]);
            }
        }
    }
    header("Location: /controller/panier/voir.php");
    exit;
}

// Supprimer un article
if (isset($_POST['action']) && $_POST['action'] === 'supprimer') {
    $id = (int)$_POST['id_produit'];
    unset($_SESSION['panier'][$id]);
    if ($user_id) {
        $pdo->prepare("DELETE FROM panier WHERE id_user = ? AND id_produit = ?")->execute([$user_id, $id]);
    }
    header("Location: /controller/panier/voir.php");
    exit;
}

// Vider le panier
if (isset($_POST['action']) && $_POST['action'] === 'vider') {
    $_SESSION['panier'] = [];
    if ($user_id) {
        $pdo->prepare("DELETE FROM panier WHERE id_user = ?")->execute([$user_id]);
    }
    header("Location: /controller/panier/voir.php");
    exit;
}
