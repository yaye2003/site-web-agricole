<?php
include 'header.php';

$conseil = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produit = strtolower($_POST['produit']);

    if (str_contains($produit, 'tomate') || str_contains($produit, 'mangue')) {
        $conseil = "Conserver à température ambiante et bien ventilé.";
    } elseif (str_contains($produit, 'riz') || str_contains($produit, 'maïs')) {
        $conseil = "Stocker dans un endroit sec et hermétique.";
    } else {
        $conseil = "Conservation au frais recommandée.";
    }
}
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
        color: #2e7d32; /* texte vert */
    }

    .form-container form {
        display: flex;
        flex-direction: column;
        gap: 15px;
        align-items: center;
    }

    .form-container input[type="text"],
    .form-container button {
        width: 80%;
        padding: 10px;
        font-size: 16px;
        border-radius: 5px;
        border: 1px solid #ccc;
        box-sizing: border-box;
    }

    .form-container button {
        background-color: #2e7d32; /* bouton vert */
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
        color: #2e7d32; /* texte vert */
        text-align: left;
        display: inline-block;
    }
</style>

<div class="form-container">
    <!-- TITRE CENTRÉ AU-DESSUS DU FORMULAIRE -->
    <h2>❄️ Système intelligent de conservation</h2>

    <form method="post">
        <input type="text" name="produit" placeholder="Nom du produit" required>
        <button type="submit">Analyser</button>
    </form>

    <?php if (!empty($conseil)): ?>
        <p class="resultat">🧠 <?= $conseil ?></p>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
