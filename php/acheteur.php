<?php
session_start();

// ⚠️ Simulation (à enlever si tu as déjà une connexion)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // ID acheteur connecté
}

// Connexion DB
$pdo = new PDO("mysql:host=localhost;dbname=projet_agri;charset=utf8", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Catégories
$categories = $pdo->query("SELECT id, nom FROM categorie")->fetchAll(PDO::FETCH_ASSOC);

// Navigation
$page = $_GET['page'] ?? 'produits';
$categorie_sel = $_GET['categorie'] ?? '';

// ================= PRODUITS =================
$produits = [];
if ($page === 'produits' && $categorie_sel !== '') {
    $stmt = $pdo->prepare("
        SELECT 
            p.id,
            p.nom,
            p.prix,
            p.image,
            CONCAT(u.nom,' ',u.prenom) AS proprietaire_nom,
            CONCAT(
                'Nom : ',u.nom,' ',u.prenom,'\n',
                'Email : ',u.email,'\n',
                'Identification : ',IFNULL(u.identification,'Non renseignée'),'\n',
                'Inscrit le : ',u.date_inscription
            ) AS proprietaire_desc_longue
        FROM produit p
        JOIN utilisateur u ON p.fournisseur_id = u.id
        JOIN categorie c ON p.categorie_id = c.id
        WHERE c.nom = ?
    ");
    $stmt->execute([$categorie_sel]);
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ================= COMMANDES =================
$commandes = [];
if ($page === 'commandes') {
    $stmt = $pdo->prepare("
        SELECT 
            p.nom,
            p.prix,
            p.image,
            c.date_commande,
            u.nom AS fournisseur_nom,
            u.prenom AS fournisseur_prenom
        FROM commande c
        JOIN produit p ON c.produit_id = p.id
        JOIN utilisateur u ON p.fournisseur_id = u.id
        WHERE c.acheteur_id = ?
        ORDER BY c.date_commande DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ================= SUIVI =================
$suivis = [];
if ($page === 'suivi') {
    $stmt = $pdo->prepare("
        SELECT c.id AS commande_id, u.nom AS acheteur_nom, u.prenom AS acheteur_prenom, 
               p.nom AS produit_nom, p.prix AS produit_prix, c.date_commande, c.statut
        FROM commande c
        JOIN utilisateur u ON c.acheteur_id = u.id
        JOIN produit p ON c.produit_id = p.id
        WHERE c.acheteur_id = ?
        ORDER BY c.date_commande DESC
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $suivis = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Acheteur</title>
    <link rel="stylesheet" href="../css/acheteur.css">
</head>
<body>

<nav>
    <div class="logo">AGRI SMART</div>
    <ul>
        <li>
            <form method="get">
                <input type="hidden" name="page" value="produits">
                <select name="categorie" onchange="this.form.submit()">
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['nom'] ?>" <?= $cat['nom']===$categorie_sel?'selected':'' ?>>
                            <?= $cat['nom'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </li>
        <li><a href="acheteur.php?page=commandes">Commandes</a></li>
        <li><a href="acheteur.php?page=suivi">Suivi</a></li>
    </ul>
</nav>

<div class="content">

<?php if ($page === 'produits'): ?>

    <?php if (!empty($produits)): ?>
        <h2>Produits : <?= htmlspecialchars($categorie_sel) ?></h2>
        <div class="produits-grid">
        <?php foreach ($produits as $prod): ?>
            <div class="produit-card">
                <img src="<?= $prod['image'] ?>" alt="<?= htmlspecialchars($prod['nom']) ?>">
                <h3><?= htmlspecialchars($prod['nom']) ?></h3>
                <p>Prix : <?= $prod['prix'] ?> FCFA</p>
                <p><strong>Propriétaire :</strong> <?= htmlspecialchars($prod['proprietaire_nom']) ?></p>
                <div class="proprietaire-longue" style="display:none;">
                    <?= nl2br(htmlspecialchars($prod['proprietaire_desc_longue'])) ?>
                </div>
                <button class="btn-action toggle-desc">Plus</button>
                <form method="post" action="commande.php">
                    <input type="hidden" name="produit_id" value="<?= $prod['id'] ?>">
                    <button class="btn-action">Commander</button>
                </form>
            </div>
        <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Sélectionnez une catégorie.</p>
    <?php endif; ?>

<?php elseif ($page === 'commandes'): ?>

    <h2>Mes commandes</h2>
    <?php if (empty($commandes)): ?>
        <p>Aucune commande.</p>
    <?php else: ?>
        <div class="produits-grid">
        <?php foreach ($commandes as $cmd): ?>
            <div class="produit-card">
                <img src="<?= htmlspecialchars($cmd['image']) ?>" alt="<?= htmlspecialchars($cmd['nom']) ?>">
                <h3><?= htmlspecialchars($cmd['nom']) ?></h3>
                <p>Prix : <?= $cmd['prix'] ?> FCFA</p>
                <p><strong>Propriétaire :</strong> <?= htmlspecialchars($cmd['fournisseur_nom'] . ' ' . $cmd['fournisseur_prenom']) ?></p>
                <div class="proprietaire-longue" style="display:none;">
                    Nom : <?= $cmd['fournisseur_nom'] ?> <?= $cmd['fournisseur_prenom'] ?><br>
                    Commandé le : <?= $cmd['date_commande'] ?>
                </div>
                <button type="button" class="btn-action toggle-desc">Plus</button>
                <label><input type="checkbox" class="produit-commandé" checked disabled> Commandé</label>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php elseif ($page === 'suivi'): ?>

<h2>Suivi des commandes</h2>

<?php if (!empty($suivis)): ?>
    <?php foreach ($suivis as $s): ?>
        <div class="suivi-container" style="border:1px solid #ccc; padding:15px; margin-bottom:15px; border-radius:5px;">
            <p><strong>Commande n°</strong> <?= $s['commande_id'] ?> :</p>
            <p><strong>Acheteur :</strong> <?= $s['acheteur_nom'] ?> <?= $s['acheteur_prenom'] ?></p>
            <p><strong>Produit :</strong> <?= $s['produit_nom'] ?> (<?= $s['produit_prix'] ?> FCFA)</p>
            <p><strong>Date :</strong> <?= $s['date_commande'] ?></p>
            <p><strong>Statut :</strong> <span class="statut"><?= $s['statut'] ?></span></p>

            <?php
            if ($s['statut'] == 'en attente') {
                echo "<p>Message : Votre commande est en attente de traitement.</p>";
            } elseif ($s['statut'] == 'en cours') {
                echo "<p>Message : Votre commande est en cours de préparation.</p>";
            } elseif ($s['statut'] == 'livrée') {
                echo "<p>Message : Votre commande a été livrée avec succès.</p>";
            } else {
                echo "<p>Message : Statut inconnu.</p>";
            }
            ?>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Aucune commande pour le moment.</p>
<?php endif; ?>



<?php endif; ?>

</div>

<script src="../js/acheteur.js"></script>
</body>
</html>


  <!-- .env ligne 1 -->