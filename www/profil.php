<?php
session_start();
require_once 'includes/db.php';
if(!isset($_SESSION['user_id'])){
    header('Location:index.php'); exit;
}
$stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
$stmt2 = $pdo->prepare('SELECT * FROM scores WHERE utilisateur_id = ?');
$stmt2->execute([$_SESSION['user_id']]);
$scores = $stmt2->fetch();
$stmt3 = $pdo->prepare('SELECT COUNT(*) as total, SUM(est_correcte) as bonnes FROM reponses WHERE utilisateur_id = ?');
$stmt3->execute([$_SESSION['user_id']]);
$stats = $stmt3->fetch();
?>
<?php require_once 'includes/header.php'; ?>
<main class="page-profil">
    <h1>👤 Mon Profil</h1>
    <p><strong><?= htmlspecialchars($user['pseudo']) ?></strong></p>
    <p class="score-total"><?= $scores['score'] ?? 0 ?> points</p>
    <div>
        <?php if($scores && $scores['score'] >= 100): ?>
            <span style="background:#F59E0B; padding:8px; border-radius:8px;">🥉 Explorer</span>
        <?php endif; ?>
        <?php if($scores && $scores['score'] >= 500): ?>
            <span style="background:#F59E0B; padding:8px; border-radius:8px;">🥈 Aventurier</span>
        <?php endif; ?>
        <?php if($scores && $scores['score'] >= 1000): ?>
            <span style="background:#F59E0B; padding:8px; border-radius:8px;">🥇 Expert</span>
        <?php endif; ?>
    </div>
    <p>Questions répondues : <?= $stats['total'] ?? 0 ?></p>
    <p>Bonnes réponses : <?= $stats['bonnes'] ?? 0 ?></p>
    <?php if(!empty($stats['total']) && $stats['total'] > 0): ?>
        <p>Taux de réussite : <?= round($stats['bonnes'] / $stats['total'] * 100) ?>%</p>
    <?php endif; ?>
</main>
<?php require_once 'includes/footer.php'; ?>