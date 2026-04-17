<?php
require 'connexion.php';

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM Livres2 WHERE id = ?");
$stmt->execute([$id]);
$livre = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$livre) die("Livre introuvable.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $auteur = trim($_POST['auteur']);
    $date = $_POST['date_publication'];

    if (!empty($titre) && !empty($auteur) && !empty($date)) {
        $stmt = $pdo->prepare("UPDATE Livres2 SET titre=?, auteur=?, date_publication=? WHERE id=?");
        $stmt->execute([$titre, $auteur, $date, $id]);
        header("Location: liste.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un livre</title>
</head>
<body>
    <h1>Modifier le livre</h1>
    <form method="POST">
        <label>Titre : <input type="text" name="titre" value="<?= htmlspecialchars($livre['titre']) ?>" required></label><br><br>
        <label>Auteur : <input type="text" name="auteur" value="<?= htmlspecialchars($livre['auteur']) ?>" required></label><br><br>
        <label>Date : <input type="date" name="date_publication" value="<?= $livre['date_publication'] ?>" required></label><br><br>
        <button type="submit">💾 Enregistrer</button>
        <a href="liste.php">Annuler</a>
    </form>
</body>
</html>