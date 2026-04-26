<?php
require_once __DIR__ . "/../../model/CRUD_user.php";

$crud = new CRUD_user();

if (isset($_POST['ok'])) {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $tel = trim($_POST['tel']);
    $adresse = trim($_POST['adresse']);
    $email = trim($_POST['email']);
    $mdp = $_POST['mdp'];
    $c_mdp = $_POST['c_mdp'];

    if ($mdp !== $c_mdp) {
        echo "<script>alert('Les mots de passe ne correspondent pas'); window.history.back();</script>";
        exit();
    }

    if (!preg_match("/^[A-Za-z\s]+$/", $nom)) {
        echo "<script>alert('Le nom doit contenir uniquement des lettres'); window.history.back();</script>";
        exit();
    }

    if (!preg_match("/^[A-Za-z\s]+$/", $prenom)) {
        echo "<script>alert('Le prenom doit contenir uniquement des lettres'); window.history.back();</script>";
        exit();
    }

    if (!preg_match("/^\d{8}$/", $tel)) {
        echo "<script>alert('Le telephone doit contenir exactement 8 chiffres'); window.history.back();</script>";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Email invalide'); window.history.back();</script>";
        exit();
    }

    if ($crud->emailExists($email)) {
        echo "<script>alert('Cet email est deja utilise'); window.history.back();</script>";
        exit();
    }

    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d).{6,}$/", $mdp)) {
        echo "<script>alert('Le mot de passe doit contenir au moins 6 caracteres, lettres et chiffres'); window.history.back();</script>";
        exit();
    }

    $obj = new User(null, $nom, $prenom, $tel, $adresse, $email, $mdp);

    if ($crud->add($obj)) {
        header("Location: ../../controller/user/login.php");
        exit();
    } else {
        echo "<script>alert('Erreur lors de inscription.'); window.history.back();</script>";
    }
}

include __DIR__ . "/../../view/inscription.php";
