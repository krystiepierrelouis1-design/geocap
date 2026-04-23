<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header('Location:index.php'); exit;
}
?>
<?php require_once 'includes/header.php'; ?>

<!-- Section héro -->
<div class="hero">
    <h1>Ah enfin de retour, <?= htmlspecialchars($_SESSION['pseudo']) ?> ! 🌍</h1>
    <p>C'est parti pour l'aventure ! Explore le monde et apprends les capitales !</p>
</div>

<!-- Les 4 sections -->
<div class="sections-accueil">

    <a href="carte.php" class="carte-section">
        <span class="emoji">🗺️</span>
        <h2>Carte interactive</h2>
        <p>Explore le monde !</p>
    </a>

    <a href="quiz.php" class="carte-section">
        <span class="emoji">🧠</span>
        <h2>Quiz</h2>
        <p>Teste tes connaissances !</p>
    </a>

    <a href="profil.php" class="carte-section">
        <span class="emoji">⭐</span>
        <h2>Mon Profil</h2>
        <p>Tes scores et badges !</p>
    </a>

    <a href="aide.php" class="carte-section">
        <span class="emoji">💬</span>
        <h2>Aide</h2>
        <p>Pose tes questions !</p>
    </a>

</div>

<?php require_once 'includes/footer.php'; ?>