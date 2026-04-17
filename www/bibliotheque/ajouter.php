<?php
require 'connexion.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $auteur = trim($_POST['auteur']);
    $date = $_POST['date_publication'];

    if (!empty($titre) && !empty($auteur) && !empty($date)) {
        $stmt = $pdo->prepare("INSERT INTO Livres2 (titre, auteur, date_publication) VALUES (?, ?, ?)");
        $stmt->execute([$titre, $auteur, $date]);
        $message = "✅ Livre ajouté avec succès !";
    } else {
        $message = "❌ Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un livre</title>
</head>
<body>
    <h1>Ajouter un livre</h1>
    <?php if ($message) echo "<p>$message</p>"; ?>
    <form method="POST">
        <label>Titre : <input type="text" name="titre" required></label><br><br>
        <label>Auteur : <input type="text" name="auteur" required></label><br><br>
        <label>Date de publication : <input type="date" name="date_publication" required></label><br><br>
        <button type="submit">Ajouter</button>
        <a href="liste.php">Retour à la liste</a>
    </form>
</body>
</html>