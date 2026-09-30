<?php
require 'config.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom       = $_POST['nom'];
    $prenom    = $_POST['prenom'];
    $email     = $_POST['email'];
    $role      = $_POST['role'];
    $extra     = $_POST['identification'] ?? null;

    $stmt = $pdo->prepare(
        "INSERT INTO utilisateur(nom, prenom, email, role, identification)
         VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->execute([$nom, $prenom, $email, $role, $extra]);
    $message = "Inscription réussie !";

    if ($role === 'acheteur') {
        header('Location: acheteur.php');
        exit;
    } elseif ($role === 'fournisseur') {
        header('Location: fournisseur.php');
        exit;
    }
}


include 'header.php';
include 'form_register.php';
include 'footer.php';
