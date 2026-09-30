<?php
session_start();
require 'config.php';

$error = "";

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $error = "Veuillez entrer votre email";
    } else {
        // Chercher l'utilisateur dans la base
        $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Créer la session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['date_inscription'] = $user['date_inscription'];

            // Redirection selon rôle
            if ($user['role'] === 'fournisseur') {
                header('Location: fournisseur.php');
            } else {
                header('Location: acheteur.php');
            }
            exit;
        } else {
            $error = "Utilisateur introuvable ! Vérifiez votre email.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - AgriSmart</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f0f0f0; }
        .login-container { max-width: 400px; margin: 100px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.2);}
        h2 { text-align: center; margin-bottom: 20px; color: #2e7d32;}
        input[type="email"] { width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 5px; border: 1px solid #ccc;}
        input[type="submit"] { width: 100%; padding: 10px; background: #2e7d32; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;}
        input[type="submit"]:hover { background: #1b5e20; }
        .error { color: red; text-align: center; margin-bottom: 15px; }
        .register-link { text-align: center; margin-top: 10px; }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Connexion AgriSmart</h2>

    <?php if (!empty($error)) : ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="email" name="email" placeholder="Votre email" required>
        <input type="submit" value="Se connecter">
    </form>

    <div class="register-link">
        <p>Pas encore de compte ? <a href="register.php">Inscrivez-vous ici</a></p>
    </div>
</div>

</body>
</html>
