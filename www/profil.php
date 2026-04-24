<?php
session_start();
require_once 'includes/db.php';
if(!isset($_SESSION['user_id'])){ header("Location: index.php"); exit; }

$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id=?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$stmt2 = $pdo->prepare("SELECT * FROM scores WHERE utilisateur_id=?");
$stmt2->execute([$_SESSION['user_id']]);
$scores = $stmt2->fetch();

$stmt3 = $pdo->prepare("SELECT COUNT(*) as total, SUM(est_correcte) as bonnes FROM reponses WHERE utilisateur_id=?");
$stmt3->execute([$_SESSION['user_id']]);
$stats = $stmt3->fetch();

$points = $scores['score'] ?? 0;
$total = $stats['total'] ?? 0;
$bonnes = $stats['bonnes'] ?? 0;
$avatar = $user['avatar'] ?? '🌍';
?>
<?php require_once 'includes/header.php'; ?>

<div class="hero">
    <p style="font-size:70px; margin-bottom:10px;"><?= $avatar ?></p>
    <h1><?= htmlspecialchars($user['pseudo']) ?></h1>
    <p><?= htmlspecialchars($user['prenom']) ?> <?= htmlspecialchars($user['nom']) ?></p>
</div>

<div style="max-width:600px; margin:30px auto; padding:20px;">

    <div class="carte-style" style="text-align:center;">
        <p style="font-size:50px; font-weight:800; color:#F59E0B;"><?= $points ?> ⭐</p>
        <p style="color:#888; margin-bottom:15px;">points accumulés</p>
        <?php if($points >= 100): ?><span style="background:#F59E0B; color:white; padding:8px 18px; border-radius:20px; margin:4px; display:inline-block; font-weight:700;">🥉 Explorer</span><?php endif; ?>
        <?php if($points >= 500): ?><span style="background:#F59E0B; color:white; padding:8px 18px; border-radius:20px; margin:4px; display:inline-block; font-weight:700;">🥈 Aventurier</span><?php endif; ?>
        <?php if($points >= 1000): ?><span style="background:#F59E0B; color:white; padding:8px 18px; border-radius:20px; margin:4px; display:inline-block; font-weight:700;">🥇 Expert</span><?php endif; ?>
        <?php if($points < 100): ?><p style="color:#999; margin-top:10px;">Réponds à des questions pour avoir des badges !</p><?php endif; ?>
    </div>

    <div class="carte-style">
        <h2 style="color:#1B6CA8; margin-bottom:15px;">📊 Mes statistiques</h2>
        <p style="margin-bottom:8px;">Questions répondues : <strong><?= $total ?></strong></p>
        <p style="margin-bottom:8px;">Bonnes réponses : <strong style="color:#10B981;"><?= $bonnes ?></strong></p>
        <p style="margin-bottom:8px;">Mauvaises réponses : <strong style="color:#EF4444;"><?= $total - $bonnes ?></strong></p>
        <?php if($total > 0): ?>
            <p>Taux de réussite : <strong style="color:#F59E0B;"><?= round($bonnes / $total * 100) ?>%</strong></p>
        <?php endif; ?>
    </div>

    <div class="carte-style">
        <h2 style="color:#1B6CA8; margin-bottom:15px;">🎨 Mes préférences</h2>
        <?php if($user['couleur']): ?><p>Couleur : <strong><?= $user['couleur'] ?></strong></p><?php endif; ?>
        <?php if($user['animal']): ?><p>Animal : <strong><?= $user['animal'] ?></strong></p><?php endif; ?>
        <?php if($user['plat']): ?><p>Plat : <strong><?= $user['plat'] ?></strong></p><?php endif; ?>
        <p style="margin-top:15px;"><a href="parametres.php" class="bouton" style="font-size:14px;">⚙️ Modifier mes paramètres</a></p>
    </div>

    <div style="text-align:center;">
        <a href="quiz.php" class="bouton">🧠 Continuer à jouer !</a>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>