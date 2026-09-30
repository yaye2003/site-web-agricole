<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=projet_agri", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $pdo->prepare("
    INSERT INTO commande (acheteur_id, produit_id)
    VALUES (?, ?)
");
$stmt->execute([
    $_SESSION['user_id'],
    $_POST['produit_id']
]);

header("Location: acheteur.php?page=commandes");
exit;
