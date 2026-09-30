<?php
include 'header.php';

$meteo = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $region = $_POST['region'];

    // Simulation météo
    $meteo = [
        'temperature' => rand(25, 38),
        'humidite' => rand(50, 90),
        'pluie' => rand(0, 1) ? 'Oui' : 'Non'
    ];
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

    .resultat li {
        margin-bottom: 5px;
    }
</style>

<div class="form-container">
    <h2>🌦️ Service météo régional</h2>

    <form method="post">
        <input type="text" name="region" placeholder="Région (ex: Dakar)" required>
        <button type="submit">Afficher</button>
    </form>

    <?php if (!empty($meteo)): ?>
    <ul class="resultat">
        <li>🌡 Température : <?= $meteo['temperature'] ?> °C</li>
        <li>💧 Humidité : <?= $meteo['humidite'] ?> %</li>
        <li>🌧 Pluie : <?= $meteo['pluie'] ?></li>
    </ul>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
