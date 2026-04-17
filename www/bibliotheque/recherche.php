<?php
require 'connexion.php';

$livres = [];
$terme = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $terme = trim($_POST['terme']);
    if (!empty($terme)) {
        $stmt = $pdo->prepare("SELECT * FROM Livres2 WHERE titre LIKE ? OR auteur LIKE ?");
        $recherche = '%' . $terme . '%';
        $stmt->execute([$recherche, $recherche]);
        $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Recherche</title>
</head>
<body>
    <h1>🔍 Rechercher un livre</h1>
    <form method="POST">
        <input type="text" name="terme" placeholder="Titre ou auteur..." value="<?= htmlspecialchars($terme) ?>" required>
        <button type="submit">Rechercher</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <h2>Résultats pour "<?= htmlspecialchars($terme) ?>"</h2>
        <?php if ($livres): ?>
        <table border="1" cellpadding="8">
            <tr>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Date de publication</th>
            </tr>
            <?php foreach ($livres as $livre): ?>
            <tr>
                <td><?= htmlspecialchars($livre['titre']) ?></td>
                <td><?= htmlspecialchars($livre['auteur']) ?></td>
                <td><?= $livre['date_publication'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
            <p>Aucun résultat trouvé.</p>
        <?php endif; ?>
    <?php endif; ?>

    <br><a href="liste.php">⬅️ Retour à la liste</a>
</body>
</html>