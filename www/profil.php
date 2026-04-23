<?php
session_start();
require_once 'includes/db.php';
if(!isset($_SESSION['user_id'])){
    header('Location:index.php'); exit;
}

// Infos utilisateur
$stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Scores
$stmt2 = $pdo->prepare('SELECT * FROM scores WHERE utilisateur_id = ?');
$stmt2->execute([$_SESSION['user_id']]);
$scores = $stmt2->fetch();

// Statistiques
$stmt3 = $pdo->prepare('SELECT COUNT(*) as total, SUM(est_correcte) as bonnes FROM reponses WHERE utilisateur_id = ?');
$stmt3->execute([$_SESSION['user_id']]);
$stats = $stmt3->fetch();

$points = $scores['score'] ?? 0;
$total = $stats['total'] ?? 0;
$bonnes = $stats['bonnes'] ?? 0;
?>
<?php require_once 'includes/header.php'; ?>

<main class="page-profil">

    <!-- Carte profil -->
    <div class="profil-card">
        <h1>👤 Mon Profil</h1>
        <p style="font-size:20px; color:#666;">
            Bonjour <strong><?= htmlspecialchars($user['pseudo']) ?></strong> ! 🌍
        </p>

        <!-- Score -->
        <p class="score-total"><?= $points ?> ⭐</p>
        <p style="color:#666;">points accumulés</p>

        <!-- Badges -->
        <div style="margin-top:20px;">
            <h3 style="color:#1B6CA8; margin-bottom:10px;">🏆 Mes badges</h3>
            <?php if($points >= 100): ?>
                <span class="badge">🥉 Explorer</span>
            <?php endif; ?>
            <?php if($points >= 500): ?>
                <span class="badge">🥈 Aventurier</span>
            <?php endif; ?>
            <?php if($points >= 1000): ?>
                <span class="badge">🥇 Géographe Expert</span>
            <?php endif; ?>
            <?php if($points < 100): ?>
                <p style="color:#999;">Réponds à des questions pour débloquer des badges !</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="profil-card">
        <h2 style="color:#1B6CA8; margin-bottom:20px;">📊 Mes statistiques</h2>

        <div class="stat-box">
            <p>🎯 Questions répondues : <strong><?= $total ?></strong></p>
            <p>✅ Bonnes réponses : <strong><?= $bonnes ?></strong></p>
            <?php if($total > 0): ?>
                <p>📈 Taux de réussite : <strong><?= round($bonnes / $total * 100) ?>%</strong></p>
            <?php endif; ?>
        </div>

        <div style="margin-top:20px;">
            <a href="quiz.php" class="btn-principal">
                🧠 Continuer à jouer !
            </a>
        </div>
    </div>

</main>

<?php require_once 'includes/footer.php'; ?>