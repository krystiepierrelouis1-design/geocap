<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header('Location:index.php'); exit;
}
?>
<?php require_once 'includes/header.php'; ?>
<main style="text-align:center; padding:40px;">
    <h1>Bienvenue <?= htmlspecialchars($_SESSION['pseudo']) ?> ! 🌍</h1>
    <p>C'est parti pour l'aventure !</p>
    <div style="display:flex; justify-content:center; gap:20px; margin-top:30px; flex-wrap:wrap;">
        <a href="carte.php" style="background:#1B6CA8; color:white; padding:30px; border-radius:12px; text-decoration:none;">
            🗺️ Carte
        </a>
        <a href="quiz.php" style="background:#1B6CA8; color:white; padding:30px; border-radius:12px; text-decoration:none;">
            🧠 Quiz
        </a>
        <a href="profil.php" style="background:#1B6CA8; color:white; padding:30px; border-radius:12px; text-decoration:none;">
            ⭐ Profil
        </a>
        <a href="aide.php" style="background:#1B6CA8; color:white; padding:30px; border-radius:12px; text-decoration:none;">
            💬 Aide
        </a>
    </div>
</main>
<?php require_once 'includes/footer.php'; ?>