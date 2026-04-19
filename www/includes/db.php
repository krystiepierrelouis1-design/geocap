<?php
// Paramètres de connexion à la base de données
$host = 'db';        // nom du serveur (ici un conteneur ou serveur local)
$dbname = 'geocap';  // nom de la base de données
$user = 'user';      // nom d'utilisateur MySQL
$password = 'user';  // mot de passe MySQL

// essaie de me connecter à ma base de données
try {
    // Création de la connexion PDO (PHP Data Objects)
    $pdo = new PDO( //outil PHP pour se connecter à une base MySQL
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $user,
        $password
    );

// Affiche les erreurs clairement
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// Si la connexion rate, on passe ici
} catch (PDOException $e) {
    // En cas d'erreur de connexion, on arrête le script et on affiche le message
    die("Erreur de connexion : " . $e->getMessage());
}
?>