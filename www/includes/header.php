<!DOCTYPE html> <!-- pour dire que c'est une page HTML -->

<!-- Langue du site -->
<html lang="fr">

<head>
    <!-- Encodage des caractères permet d'écrire correctement sans bug (accents ...) -->
    <meta charset="UTF-8">

    <!-- Responsive (adapté téléphone) -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Titre de la page -->
    <title>Geocap</title>

    <!-- Police Google Fonts (Nunito) téléchargée depuis Google -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito&display=swap" rel="stylesheet">

    <!-- Lien vers mon fichier CSS (dans assets/css/) -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body> <!-- corps du site internet -->

    <!-- La barre du haut en arrivant sur mon site internet (logo etc) -->
    <header>
        
        <!-- Barre de navigation de mon site internet -->
        <nav class="navbar">

           <!-- Logo de mon site internet -->
            <div class="logo">

                <!-- Image de mon logo -->
                <!-- Permet d'afficher mon logo correctement -->
                <img src="../logo.png" alt="Logo GEOCAP">

                <!-- Nom de mon site internet -->
                <span>GEOCAP</span>
            </div>

            <!-- Menu de mon site internet Geocap -->
            <!-- Permet d'accéder à chaque page du site -->
            <ul class="menu"> 

                <!-- Lien vers la page d'accueil -->
                <li><a href="../index.php">Accueil</a></li>

                <!-- Lien vers ma page carte -->
                <li><a href="../carte.php">Carte</a></li>

                <!-- Lien vers la page quiz -->
                <li><a href="../quiz.php">Quiz</a></li>

                <!-- Lien vers ma page profil -->
                <li><a href="../profil.php">Profil</a></li>

                <!-- Lien vers ma page aide -->
                <li><a href="../aide.php">Aide</a></li>

            </ul>

        </nav>

    </header>
  