
<?php

// clé OpenAI

 if(!defined('OPENAI_API_KEY')){
define('OPENAI_API_KEY', '.env ligne 7');} // remplace 'ta_clef_ici' par ta vraie clé 

// includes/config.php

// Paramètres de connexion à la base de données
$host = "localhost";      // serveur de base de données (souvent localhost)
$dbname = "projet_agri";  // nom de ta base de données
$user = "root";           // nom d'utilisateur MySQL
$pass = "";               // mot de passe MySQL (souvent vide en local)

// Connexion à la base de données avec PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    // Pour afficher les erreurs PDO (très utile pour le debug)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Si la connexion échoue, on arrête le script et on affiche l'erreur
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}


?>
