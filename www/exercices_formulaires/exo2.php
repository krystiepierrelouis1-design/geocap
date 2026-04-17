<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>
    <h1>Résultat de la Connexion</h1>
    
    <?php
    // Valeurs prédéfinies
    $username_correct = "admin";
    $password_correct = "password123";
    
    // Vérification que les champs ne sont pas vides
    if (empty($_POST['username']) || empty($_POST['password'])) {
        echo "<p style='color: red;'>Erreur : Veuillez remplir tous les champs !</p>";
        echo "<a href='login.html'>Retour au formulaire</a>";
    } else {
        // Récupération des données
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        // Vérification des identifiants
        if ($username === $username_correct && $password === $password_correct) {
            echo "<p style='color: green;'>Bienvenue " . htmlspecialchars($username) . " ! Vous êtes connecté avec succès.</p>";
        } else {
            echo "<p style='color: red;'>Erreur : Nom d'utilisateur ou mot de passe incorrect !</p>";
            echo "<a href='login.html'>Retour au formulaire</a>";
        }
    }
    ?>
</body>
</html>