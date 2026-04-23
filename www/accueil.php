<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header('Location:index.php'); exit;
}

// Message saisonnier
$mois = date('n');
$jour = date('j');
$heure = date('H');

if($mois == 12 && $jour >= 24) {
    $msg_saison = "🎄 Joyeux Noël ! Content de te revoir parmi nous !";
    $couleur_saison = "#E74C3C";
} elseif($mois == 1 && $jour == 1) {
    $msg_saison = "🎆 Bonne année ! Que cette année soit remplie d'aventures !";
    $couleur_saison = "#8E44AD";
} elseif($mois == 10 && $jour == 31) {
    $msg_saison = "🎃 Joyeux Halloween ! Attention aux questions effrayantes !";
    $couleur_saison = "#E67E22";
} elseif($mois == 2 && $jour == 14) {
    $msg_saison = "💝 Bonne Saint-Valentin ! L'amour de la géographie !";
    $couleur_saison = "#E74C3C";
} else {
    $msg_saison = null;
}

// Salutation selon heure
if($heure >= 5 && $heure < 12) {
    $salut = "☀️ Bonjour";
} elseif($heure >= 12 && $heure < 18) {
    $salut = "🌤️ Bon après-midi";
} else {
    $salut = "🌙 Bonsoir";
}
?>
<?php require_once 'includes/header.php'; ?>

<style>
.hero-accueil {
    background: linear-gradient(135deg, #1B6CA8, #0D4A7A);
    color: white;
    text-align: center;
    padding: 60px 20px 40px;
}
.hero-accueil h1 {
    font-size: 38px;
    font-weight: 900;
    margin-bottom: 10px;
}
.hero-accueil p {
    font-size: 18px;
    opacity: 0.9;
}
.msg-saison {
    display: inline-block;
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: 700;
    font-size: 16px;
    margin-bottom: 20px;
    color: white;
}
.sections-grid {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 25px;
    padding: 50px 20px;
    max-width: 1000px;
    margin: auto;
}
.section-card {
    background: white;
    border-radius: 24px;
    padding: 40px 30px;
    text-align: center;
    text-decoration: none;
    color: #2D2D2D;
    width: 200px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    transition: all 0.3s;
    border: 3px solid transparent;
    position: relative;
    overflow: hidden;
}
.section-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 5px;
    background: linear-gradient(135deg, #1B6CA8, #10B981);
}
.section-card:hover {
    transform: translateY(-10px);
    border-color: #1B6CA8;
    box-shadow: 0 15px 40px rgba(27,108,168,0.2);
}
.section-card .icon {
    font-size: 50px;
    display: block;
    margin-bottom: 15px;
}
.section-card h2 {
    font-size: 17px;
    color: #1B6CA8;
    font-weight: 800;
    margin-bottom: 8px;
}
.section-card p {
    font-size: 13px;
    color: #888;
}
.stats-bar {
    background: white;
    padding: 20px 40px;
    display: flex;
    justify-content: center;
    gap: 40px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    flex-wrap: wrap;
}
.stat-item {
    text-align: center;
}
.stat-item .nombre {
    font-size: 28px;
    font-weight: 900;
    color: #1B6CA8;
}
.stat-item .label {
    font-size: 13px;
    color: #888;
}
</style>

<!-- Hero -->
<div class="hero-accueil">
    <?php if($msg_saison): ?>
        <div class="msg-saison" style="background:<?= $couleur_saison ?>;">
            <?= $msg_saison ?>
        </div><br>
    <?php endif; ?>
    <h1><?= $salut ?>, <?= htmlspecialchars($_SESSION['pseudo']) ?> ! 🌍</h1>
    <p>C'est parti pour l'aventure ! Explore le monde et apprends les capitales !</p>
</div>

<!-- 4 sections -->
<div class="sections-grid">
    <a href="carte.php" class="section-card">
        <span class="icon">🗺️</span>
        <h2>Carte interactive</h2>
        <p>Clique sur un pays et explore !</p>
    </a>
    <a href="quiz.php" class="section-card">
        <span class="icon">🧠</span>
        <h2>Quiz</h2>
        <p>Teste tes connaissances !</p>
    </a>
    <a href="profil.php" class="section-card">
        <span class="icon">⭐</span>
        <h2>Mon Profil</h2>
        <p>Tes scores et badges !</p>
    </a>
    <a href="aide.php" class="section-card">
        <span class="icon">💬</span>
        <h2>Aide</h2>
        <p>Pose tes questions !</p>
    </a>
</div>

<?php require_once 'includes/footer.php'; ?>