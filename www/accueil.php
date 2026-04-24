<?php
session_start();
if(!isset($_SESSION['user_id'])){ header("Location: index.php"); exit; }

$heure = date('H');
if($heure >= 5 && $heure < 12){ $salut = "☀️ Bonjour"; }
elseif($heure >= 12 && $heure < 18){ $salut = "🌤️ Bon après-midi"; }
else { $salut = "🌙 Bonsoir"; }

$mois = date('n'); $jour = date('j');
$msg_saison = null;
if($mois == 12 && $jour >= 24){ $msg_saison = "🎄 Joyeux Noël !"; }
elseif($mois == 1 && $jour == 1){ $msg_saison = "🎆 Bonne année !"; }
elseif($mois == 10 && $jour == 31){ $msg_saison = "🎃 Joyeux Halloween !"; }
elseif($mois == 2 && $jour == 14){ $msg_saison = "💝 Bonne Saint-Valentin !"; }
?>
<?php require_once 'includes/header.php'; ?>

<div class="hero">
    <?php if($msg_saison): ?>
        <p style="font-size:20px; margin-bottom:10px;"><?= $msg_saison ?></p>
    <?php endif; ?>
    <h1><?= $salut ?>, <?= htmlspecialchars($_SESSION['pseudo']) ?> ! 🌍</h1>
    <p>Explore le monde et apprends les capitales !</p>
</div>

<div style="display:flex; justify-content:center; gap:20px; padding:40px 20px; flex-wrap:wrap; max-width:900px; margin:auto;">
    <a href="carte.php" style="background:white; padding:30px 20px; border-radius:15px; text-decoration:none; color:#333; width:170px; text-align:center; box-shadow:0 4px 15px rgba(0,0,0,0.08); transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
        <p style="font-size:45px;">🗺️</p>
        <h3 style="color:#1B6CA8; margin:8px 0;">Carte</h3>
        <p style="font-size:13px; color:#888;">Clique sur un pays !</p>
    </a>
    <a href="quiz.php" style="background:white; padding:30px 20px; border-radius:15px; text-decoration:none; color:#333; width:170px; text-align:center; box-shadow:0 4px 15px rgba(0,0,0,0.08); transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
        <p style="font-size:45px;">🧠</p>
        <h3 style="color:#1B6CA8; margin:8px 0;">Quiz</h3>
        <p style="font-size:13px; color:#888;">Teste tes connaissances !</p>
    </a>
    <a href="profil.php" style="background:white; padding:30px 20px; border-radius:15px; text-decoration:none; color:#333; width:170px; text-align:center; box-shadow:0 4px 15px rgba(0,0,0,0.08); transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
        <p style="font-size:45px;">⭐</p>
        <h3 style="color:#1B6CA8; margin:8px 0;">Profil</h3>
        <p style="font-size:13px; color:#888;">Tes scores !</p>
    </a>
    <a href="classement.php" style="background:white; padding:30px 20px; border-radius:15px; text-decoration:none; color:#333; width:170px; text-align:center; box-shadow:0 4px 15px rgba(0,0,0,0.08); transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
        <p style="font-size:45px;">🏆</p>
        <h3 style="color:#1B6CA8; margin:8px 0;">Classement</h3>
        <p style="font-size:13px; color:#888;">Les meilleurs !</p>
    </a>
    <a href="aide.php" style="background:white; padding:30px 20px; border-radius:15px; text-decoration:none; color:#333; width:170px; text-align:center; box-shadow:0 4px 15px rgba(0,0,0,0.08); transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
        <p style="font-size:45px;">💬</p>
        <h3 style="color:#1B6CA8; margin:8px 0;">Aide</h3>
        <p style="font-size:13px; color:#888;">Pose tes questions !</p>
    </a>
</div>

<?php require_once 'includes/footer.php'; ?>