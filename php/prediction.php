<?php
require 'config.php';
include 'header.php';

$resultat = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categorie_id = $_POST['categorie_id'];
    $surface = $_POST['surface'];

    // Prix moyen de la catégorie
    $stmt = $pdo->prepare(
        "SELECT AVG(prix) AS prix_moyen FROM produit WHERE categorie_id=?"
    );
    $stmt->execute([$categorie_id]);
    $data = $stmt->fetch();

    // IA heuristique simple
    $rendement = round(($data['prix_moyen'] / 100) * $surface, 2);
}

$categories = $pdo->query("SELECT * FROM categorie")->fetchAll();
?>

<style>
    .form-container {
        max-width: 700px;
        margin: 40px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        text-align: center;
    }

    .form-container h2 {
        margin-bottom: 25px;
        color: #2e7d32;
    }

    .form-container form {
        display: flex;
        flex-direction: column;
        gap: 15px;
        align-items: center;
    }

    .form-container select,
    .form-container input[type="number"],
    .form-container button {
        width: 80%;
        padding: 10px;
        font-size: 16px;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .form-container button {
        background-color: #2e7d32;
        color: #fff;
        border: none;
        cursor: pointer;
        transition: background 0.3s;
    }

    .form-container button:hover {
        background-color: #1b5e20;
    }

    .resultat {
        margin-top: 20px;
        font-size: 18px;
        font-weight: bold;
        color: #2e7d32;
    }
</style>

<div class="form-container">
    <h2>📈 Prédiction du rendement agricole</h2>

    <form method="post">
        <select name="categorie_id" required>
            <option value="">-- Choisir une catégorie --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= $cat['nom'] ?></option>
            <?php endforeach; ?>
        </select>

        <input type="number" name="surface" placeholder="Surface cultivée (ha)" required>

        <button type="submit">Prédire</button>
    </form>

    <?php if ($resultat !== null): ?>
    <p class="resultat">
        🌾 Rendement estimé : <strong><?= $rendement ?> tonnes</strong>
    </p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
