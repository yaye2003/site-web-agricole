<?php
session_start();
require 'config.php';

// Sécurité : uniquement les fournisseurs
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'fournisseur') {
    header('Location: login.php');
    exit;
}

// Infos fournisseur connecté
$stmt = $pdo->prepare("SELECT nom, prenom, date_inscription FROM utilisateur WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$fournisseur = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Espace Fournisseur - AgriSmart</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }

        h2 {
            color: #2e7d32;
            margin: 0;
        }

        .welcome {
            margin: 5px 0 20px 0;
            font-size: 18px;
        }

        /* ----- Menu horizontal ----- */
        .menu-fournisseur {
            background-color: #2e7d32;
            display: flex;            /* menu horizontal */
            justify-content: center;  /* centré */
            padding: 0;
            margin-bottom: 30px;
            border-radius: 5px;
        }

        .menu-fournisseur a {
            display: block;
            padding: 15px 25px;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
        }

        .menu-fournisseur a:hover {
            background-color: #1b5e20;
        }

        /* ----- Dashboard cards ----- */
        .dashboard-info {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .card {
            background-color: #fff;
            flex: 1 1 250px;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            text-align: center;
        }

        .card h3 {
            color: #2e7d32;
            margin-bottom: 10px;
        }

        .card p {
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container fournisseur-dashboard">

    <!-- TITRE + BIENVENUE -->
 <div class="header-center">
    <h2>👨‍🌾 Espace Fournisseur</h2>
    <p class="welcome">
        Bienvenue <strong><?= htmlspecialchars($fournisseur['prenom'].' '.$fournisseur['nom']) ?></strong>
    </p>
</div>
    <!-- MENU HORIZONTAL SUR UNE SEULE LIGNE -->
    <nav class="menu-fournisseur">
        <a href="ajout_produit.php">➕ Ajouter des produits</a>
        <a href="prediction.php">📈 Prédictions du rendement agricole</a>
        <a href="meteo.php">🌦️ Service météo régional</a>
        <a href="conservation.php">❄️ Conservation intelligente</a>
        <a href="assistance.php">🤖 Assistance agricole</a>
    </nav>

    <!-- CONTENU DASHBOARD -->
    <section class="dashboard-info">

        <?php
        // Statistiques rapides
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM produit WHERE fournisseur_id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $nbProduits = $stmt->fetchColumn();
        ?>

        <div class="card">
            <h3>📦 Produits publiés</h3>
            <p><?= $nbProduits ?></p>
        </div>

        <div class="card">
            <h3>📅 Date d’inscription</h3>
            <p><?= date('d/m/Y', strtotime($fournisseur['date_inscription'])) ?></p>
        </div>

    </section>

</div>

<?php include 'footer.php'; ?>

</body>
</html>
