<?php
session_start();
include 'header.php';
require 'config.php'; // inclut connexion DB et clé OpenAI

// Initialiser l'historique
if (!isset($_SESSION['chat_agri'])) {
    $_SESSION['chat_agri'] = [];
}

// Supprimer l'historique si demandé
if (isset($_POST['clear_chat'])) {
    $_SESSION['chat_agri'] = [];
}

// Initialiser la réponse
$reponse = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['question']) && !empty(trim($_POST['question']))) {
    $question = trim($_POST['question']);

    // Préparer la requête pour GPT-3.5-turbo
    $data = [
        "model" => "gpt-3.5-turbo",
        "messages" => [
            ["role" => "system", "content" => "Tu es un assistant agricole intelligent. Réponds de manière claire et précise."],
            ["role" => "user", "content" => $question]
        ],
        "max_tokens" => 200
    ];

    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: ' . 'Bearer ' . OPENAI_API_KEY
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    // Vérifier si la réponse est disponible
    if (isset($result['choices'][0]['message']['content'])) {
        $reponse = trim($result['choices'][0]['message']['content']);
    } else {
        $reponse = "⚠️ IA non disponible pour le moment. Vérifiez votre clé API ou votre quota.";
    }

    // Sauvegarder dans la session
    $_SESSION['chat_agri'][] = [
        'question' => $question,
        'reponse' => $reponse
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
    color: #2e7d32;
}

.form-container form {
    display: flex;
    flex-direction: column;
    gap: 15px;
    align-items: center;
}

.form-container textarea,
.form-container button {
    width: 80%;
    padding: 10px;
    font-size: 16px;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

.form-container textarea {
    height: 120px;
    resize: vertical;
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

.chat-log {
    margin-top: 30px;
    text-align: left;
    max-width: 700px;
    margin-left: auto;
    margin-right: auto;
}

.chat-log p {
    background-color: #f1f8e9;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 10px;
}

.chat-log .question {
    font-weight: bold;
}

.chat-log .answer {
    color: #2e7d32;
}

.clear-button {
    margin-top: 10px;
    background-color: #b71c1c;
}

.clear-button:hover {
    background-color: #7f0000;
}
</style>

<div class="form-container">
    <h2>🤖 Assistance agricole intelligente</h2>

    <form method="post">
        <textarea name="question" placeholder="Posez votre question..." required></textarea>
        <button type="submit">Envoyer</button>
    </form>

    <!-- Bouton pour supprimer l'historique -->
    <form method="post">
        <input type="hidden" name="clear_chat" value="1">
        <button type="submit" class="clear-button">🗑 Supprimer l'historique</button>
    </form>
</div>

<div class="chat-log">
    <?php if (!empty($_SESSION['chat_agri'])): ?>
        <?php foreach ($_SESSION['chat_agri'] as $chat): ?>
            <p class="question">❓ Question : <?= htmlspecialchars($chat['question']) ?></p>
            <p class="answer">📝 Réponse : <?= htmlspecialchars($chat['reponse']) ?></p>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
