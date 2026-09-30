<?php
session_start();
require 'config.php';
include 'header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'fournisseur') {
    header('Location: login.php');
    exit;
}

// Récupérer les catégories
$categories = $pdo->query("SELECT * FROM categorie")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $categorie_id = $_POST['categorie_id'];
    $image = $_POST['image']; // chemin image
    $fournisseur_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare(
        "INSERT INTO produit (nom, description, prix, categorie_id, fournisseur_id, image)
         VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([$nom, $description, $prix, $categorie_id, $fournisseur_id, $image]);

    $success = "Produit ajouté avec succès ✅";
}
?>

<!-- Styles pour centrer le formulaire et uniformiser les champs -->
<style>
    .form-container {
        max-width: 850px;       /* un peu plus large */
    margin: 30px auto;      /* centré horizontalement */
    padding: 15px 20px;     /* hauteur réduite : padding vertical moins grand */
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .form-container h2 {
        text-align: center;
        color: #2e7d32;
        margin-bottom: 20px;
    }

    .form-container form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .form-container input[type="text"],
    .form-container input[type="number"],
    .form-container select,
    .form-container textarea {
        width: 100%;           /* même largeur pour tous les champs */
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 16px;
        box-sizing: border-box;
    }

    .form-container textarea {
        height: 120px;         /* textarea plus grand */
        resize: vertical;      /* peut redimensionner verticalement */
    }

    .form-container button {
        padding: 12px;
        background-color: #2e7d32;
        color: #fff;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .form-container button:hover {
        background-color: #1b5e20;
    }

    .success {
        text-align: center;
        color: #2e7d32;
        margin-bottom: 15px;
        font-weight: bold;
    }
</style>

<div class="form-container">
    <h2>➕ Ajouter un produit</h2>

    <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>

    <form method="post">
        <input type="text" name="nom" placeholder="Nom du produit" required>
        <textarea name="description" placeholder="Description"></textarea>
        <input type="number" step="0.01" name="prix" placeholder="Prix" required>

        <select name="categorie_id" required>
            <option value="">-- Catégorie --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= $cat['nom'] ?></option>
            <?php endforeach; ?>
        </select>

        <input type="text" name="image" placeholder="Chemin image (ex: ../images/produit.jpg)">
        <button type="submit">Ajouter</button>
    </form>
</div>

<?php include 'footer.php'; ?>
