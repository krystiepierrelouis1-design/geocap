<?php
require 'connexion.php';

$stmt = $pdo->query("SELECT * FROM Livres2 ORDER BY id DESC");
$livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des livres</title>
</head>
<body>
    <h1>Liste des livres</h1>
    <a href="ajouter.php">➕ Ajouter un livre</a> |
    <a href="recherche.php">🔍 Rechercher</a>
    <br><br>
    <table border="1" cellpadding="8">
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Auteur</th>
            <th>Date de publication</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($livres as $livre): ?>
        <tr>
            <td><?= $livre['id'] ?></td>
            <td><?= htmlspecialchars($livre['titre']) ?></td>
            <td><?= htmlspecialchars($livre['auteur']) ?></td>
            <td><?= $livre['date_publication'] ?></td>
            <td>
                <a href="modifier.php?id=<?= $livre['id'] ?>">✏️ Modifier</a> |
                <a href="supprimer.php?id=<?= $livre['id'] ?>" onclick="return confirm('Supprimer ce livre ?')">🗑️ Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>