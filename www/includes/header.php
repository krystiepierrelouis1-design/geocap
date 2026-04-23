<!DOCTYPE html> <!-- permet de dire que c'est une page html -->
<html lang="fr">   <!-- permet de dire que la page est en français -->
<head>
    <!-- Encodage des caractères : mot avec accent correctement -->
    <meta charset="UTF-8">
    <!-- Responsive téléphone : permet de réduire la taille du site -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Titre de la page -->
    <title>Geocap</title>
    <!-- Police Nunito -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">
    <!-- Lien CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <header>
        <nav class="navbar">
            <!-- Logo du site internet -->
            <div class="logo">
                <img src="assets/img/logo.png" alt="Logo GEOCAP" height="50">
                <span>GEOCAP</span>
            </div>
            <!-- Menu : permet d'accéder au diffèrenete page du site -->
            <ul class="menu">
                <li><a href="accueil.php">Accueil</a></li>
                <li><a href="carte.php">Carte</a></li>
                <li><a href="quiz.php">Quiz</a></li>
                <li><a href="profil.php">Profil</a></li>
                <li><a href="aide.php">Aide</a></li>
            </ul>
        </nav>
    </header>