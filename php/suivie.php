<?php
require_once 'config.php'; // inclut $pdo

// Récupérer toutes les commandes avec infos sur le produit et l'acheteur
$sql = "
SELECT c.id AS commande_id, u.nom AS acheteur_nom, u.prenom AS acheteur_prenom,
       p.nom AS produit_nom, p.prix AS produit_prix, c.date_commande, c.statut
FROM commande c
JOIN utilisateur u ON c.acheteur_id = u.id
JOIN produit p ON c.produit_id = p.id
ORDER BY c.date_commande DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Suivi des commandes</h2>

<?php if (!empty($commandes)): ?>
    <?php foreach ($commandes as $row): ?>
        <div style="border:1px solid #ccc; padding:15px; margin-bottom:10px; border-radius:5px;">
            <p>Commande n° <?= $row['commande_id'] ?> :</p>
            <p>Acheteur : <?= $row['acheteur_nom'] ?> <?= $row['acheteur_prenom'] ?></p>
            <p>Produit : <?= $row['produit_nom'] ?> (<?= $row['produit_prix'] ?> FCFA)</p>
            <p>Date : <?= $row['date_commande'] ?></p>
            <p>Statut : <strong><?= $row['statut'] ?></strong></p>

            <?php
            if ($row['statut'] == 'en attente') {
                echo "<p>Message : Votre commande est en attente de traitement.</p>";
            } elseif ($row['statut'] == 'en cours') {
                echo "<p>Message : Votre commande est en cours de préparation.</p>";
            } elseif ($row['statut'] == 'livrée') {
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
