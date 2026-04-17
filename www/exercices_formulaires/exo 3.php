<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>
    <h1>Traitement de l'Inscription</h1>
    
    <?php
    // Vérification que tous les champs sont remplis
    if (empty($_POST['username']) || empty($_POST['email']) || 
        empty($_POST['password']) || empty($_POST['password_confirm'])) {
        echo "<p style='color: red;'>Erreur : Tous les champs doivent être remplis !</p>";
        echo "<a href='inscription.html'>Retour au formulaire</a>";
    } else {
        // Récupération des données
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];
        
        $erreurs = array();
        
        // Validation de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurs[] = "L'adresse email n'est pas valide.";
        }
        
        // Vérification que les mots de passe correspondent
        if ($password !== $password_confirm) {
            $erreurs[] = "Les mots de passe ne correspondent pas.";
        }
        
        // Vérification de la longueur du mot de passe
        if (strlen($password) < 6) {
            $erreurs[] = "Le mot de passe doit contenir au moins 6 caractères.";
        }
        
        // Si des erreurs existent, les afficher
        if (!empty($erreurs)) {
            echo "<p style='color: red;'>Erreur(s) détectée(s) :</p>";
            echo "<ul style='color: red;'>";
            foreach ($erreurs as $erreur) {
                echo "<li>" . htmlspecialchars($erreur) . "</li>";
            }
            echo "</ul>";
            echo "<a href='inscription.html'>Retour au formulaire</a>";
        } else {
            // Tout est valide, on enregistre dans un fichier
            $fichier = "utilisateurs.txt";
            
            // Préparation des données à enregistrer
            // ATTENTION : Dans un vrai projet, il faudrait hasher le mot de passe !
            $donnees = date("Y-m-d H:i:s") . " | " . $username . " | " . $email . " | " . $password . "\n";
            
            // Écriture dans le fichier
            if (file_put_contents($fichier, $donnees, FILE_APPEND)) {
                echo "<p style='color: green;'>Inscription réussie !</p>";
                echo "<p><strong>Nom d'utilisateur :</strong> " . htmlspecialchars($username) . "</p>";
                echo "<p><strong>Email :</strong> " . htmlspecialchars($email) . "</p>";
                echo "<p>Vos informations ont été enregistrées.</p>";
                echo "<a href='inscription.html'>Nouvelle inscription</a>";
            } else {
                echo "<p style='color: red;'>Erreur : Impossible d'enregistrer les données.</p>";
                echo "<a href='inscription.html'>Retour au formulaire</a>";
            }
        }
    }
    ?>
</body>
</html>