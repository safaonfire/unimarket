<?php
session_start();
require_once __DIR__ . "/../../model/CRUD_user.php";

$crud = new CRUD_user();

if (isset($_POST['ok'])) {
    $email = trim($_POST['email']);
    $mdp = $_POST['mdp'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Email invalide'); window.history.back();</script>";
        exit();
    }

    $user = $crud->login($email);

    if ($user && trim($mdp) === trim($user['mot_de_passe'])) {
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_nom']   = $user['nom'];
        $_SESSION['user_prenom']= $user['prenom'];
        $_SESSION['user_role']  = $user['role'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_tel']   = $user['tel'] ?? '';
        $_SESSION['user_adresse']= $user['adresse'] ?? '';
        $_SESSION['user_photo'] = $user['photo'] ?? null;

        require_once __DIR__ . '/../../connexion.php';
        $pdo = (new Connexion())->getConnexion();

        // Recharger les favoris depuis la DB
        $stmt = $pdo->prepare("SELECT id_produit FROM favoris WHERE id_user = ?");
        $stmt->execute([$user['id']]);
        $_SESSION['favoris'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Fusionner le panier session avec le panier DB
        $sessionPanier = $_SESSION['panier'] ?? [];
        $stmt = $pdo->prepare("SELECT id_produit, quantite FROM panier WHERE id_user = ?");
        $stmt->execute([$user['id']]);
        $dbPanier = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

        // Fusionner : la session prend priorité sur la DB
        foreach ($sessionPanier as $id_produit => $qte) {
            $dbPanier[$id_produit] = ($dbPanier[$id_produit] ?? 0) + $qte;
        }
        $_SESSION['panier'] = $dbPanier;

        // Sauvegarder le panier fusionné en DB
        $upsert = $pdo->prepare("INSERT INTO panier (id_user, id_produit, quantite) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE quantite = VALUES(quantite)");
        foreach ($_SESSION['panier'] as $id_produit => $qte) {
            $upsert->execute([$user['id'], $id_produit, $qte]);
        }

        if ($user['role'] === 'supadm') {
            header("Location: /controller/admin/dashboard.php");
        } elseif ($user['role'] === 'admin') {
            header("Location: /controller/vendeur/dashboard.php");
        } else {
            $redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '/controller/home.php';
            header("Location: " . $redirect);
        }
        exit();
    } else {
        echo "<script>alert('Email ou mot de passe incorrect'); window.history.back();</script>";
    }
}

include __DIR__ . "/../../view/login.php";
