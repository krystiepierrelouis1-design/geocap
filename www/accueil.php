<?php
session_start();
// démarre session

if(!isset($_SESSION['user_id'])){
// si utilisateur pas connecté

header("Location:index.php");
// redirection login

exit;
// arrêt script

}
?>

<?php require_once 'includes/header.php'; ?>

<h1>Bienvenue <?= $_SESSION['pseudo'] ?></h1>
// affiche pseudo utilisateur

<a href="carte.php">Carte</a>
// lien carte

<a href="quiz.php">Quiz</a>
// lien quiz

<a href="profil.php">Profil</a>
// lien profil

<a href="aide.php">Aide</a>
// lien aide

<?php require_once 'includes/footer.php'; ?>
