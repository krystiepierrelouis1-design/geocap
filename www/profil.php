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

// Avatar selon couleur préférée
$couleurs = [
    'bleu' => '#1B6CA8',
    'rouge' => '#EF4444',
    'vert' => '#10B981',
    'jaune' => '#F59E0B',
    'violet' => '#8E44AD',
    'orange' => '#F97316',
    'rose' => '#EC4899',
];
$couleur_profil = $couleurs[$user['couleur'] ?? 'bleu'] ?? '#1B6CA8';
?>
<?php require_once 'includes/header.php'; ?>

<style>
.page-profil {
    max-width: 650px;
    margin: 40px auto;
    padding: 20px;
}
.profil-card {
    background: white;
    border-radius: 24px;
    padding: 40px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    margin-bottom: 20px;
    text-align: center;
}
.avatar-cercle {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 60px;
    margin: 0 auto 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
    border: 5px solid white;
}
.profil-pseudo {
    font-size: 26px;
    font-weight: 900;
    margin-bottom: 5px;
}
.profil-infos {
    color: #888;
    font-size: 14px;
    margin-bottom: 20px;
}
.score-total {
    font-size: 55px;
    font-weight: 900;
    color: #F59E0B;
    line-height: 1;
    margin: 15px 0 5px;
}
.score-label {
    color: #888;
    font-size: 14px;
    margin-bottom: 20px;
}
.badges-zone {
    display: flex;
    gap: 10px;
    justify-content: center;
    flex-wrap: wrap;
    margin: 15px 0;
}
.badge-item {
    background: linear-gradient(135deg, #F59E0B, #F97316);
    color: white;
    padding: 10px 20px;
    border-radius: 25px;
    font-weight: 700;
    font-size: 15px;
    box-shadow: 0 3px 10px rgba(245,158,11,0.3);
}
.badge-vide {
    color: #CCC;
    font-size: 14px;
    font-style: italic;
}
.stats-card {
    background: white;
    border-radius: 24px;
    padding: 30px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}
.stats-card h2 {
    color: #1B6CA8;
    font-size: 20px;
    margin-bottom: 20px;
    font-weight: 800;
}
.stat-ligne {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #F5F5F5;
    font-size: 16px;
}
.stat-ligne:last-child { border: none; }
.stat-valeur {
    font-weight: 800;
    font-size: 18px;
}
.barre-progression {
    background: #F0F0F0;
    border-radius: 10px;
    height: 12px;
    margin-top: 8px;
    overflow: hidden;
}
.barre-remplie {
    height: 100%;
    border-radius: 10px;
    background: linear-gradient(135deg, #1B6CA8, #10B981);
    transition: width 1s ease;
}
.prefs-card {
    background: white;
    border-radius: 24px;
    padding: 30px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}
.prefs-card h2 {
    color: #1B6CA8;
    font-size: 20px;
    margin-bottom: 20px;
    font-weight: 800;
}
.pref-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid #F5F5F5;
    font-size: 15px;
}
.pref-item:last-child { border: none; }
.pref-emoji { font-size: 25px; }
.btn-jouer {
    background: linear-gradient(135deg, #1B6CA8, #0D4A7A);
    color: white;
    padding: 16px 35px;
    border: none;
    border-radius: 25px;
    font-size: 17px;
    font-weight: 700;
    cursor: pointer;
    font-family: 'Nunito', sans-serif;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(27,108,168,0.4);
}
.btn-jouer:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(27,108,168,0.5);
}
</style>

<main class="page-profil">

    <!-- Carte profil principale -->
    <div class="profil-card">

        <!-- Avatar avec couleur préférée -->
        <div class="avatar-cercle" style="background: <?= $couleur_profil ?>;">
            <?= $user['couleur'] === 'bleu' ? '🌍' :
               ($user['couleur'] === 'rouge' ? '🔥' :
               ($user['couleur'] === 'vert' ? '🌿' :
               ($user['couleur'] === 'jaune' ? '⭐' :
               ($user['couleur'] === 'violet' ? '🔮' :
               ($user['couleur'] === 'orange' ? '🦊' :
               ($user['couleur'] === 'rose' ? '🌸' : '🌍')))))) ?>
        </div>

        <!-- Pseudo et infos -->
        <div class="profil-pseudo"><?= htmlspecialchars($user['pseudo']) ?></div>
        <div class="profil-infos">
            <?= htmlspecialchars($user['prenom']) ?> <?= htmlspecialchars($user['nom']) ?>
        </div>

        <!-- Score -->
        <div class="score-total"><?= $points ?> ⭐</div>
        <div class="score-label">points accumulés</div>

        <!-- Barre de progression vers prochain badge -->
        <?php
        if($points < 100) {
            $prochain = 100;
            $pourcent = round($points / 100 * 100);
            $prochain_badge = "🥉 Explorer";
        } elseif($points < 500) {
            $prochain = 500;
            $pourcent = round(($points - 100) / 400 * 100);
            $prochain_badge = "🥈 Aventurier";
        } elseif($points < 1000) {
            $prochain = 1000;
            $pourcent = round(($points - 500) / 500 * 100);
            $prochain_badge = "🥇 Expert";
        } else {
            $pourcent = 100;
            $prochain_badge = null;
        }
        ?>
        <?php if($prochain_badge): ?>
            <p style="color:#888; font-size:13px; margin-top:10px;">
                Prochain badge : <?= $prochain_badge ?> (<?= $pourcent ?>%)
            </p>
            <div class="barre-progression" style="margin: 8px auto; max-width:300px;">
                <div class="barre-remplie" style="width:<?= $pourcent ?>%;"></div>
            </div>
        <?php endif; ?>

        <!-- Badges -->
        <div class="badges-zone" style="margin-top:20px;">
            <?php if($points >= 100): ?>
                <span class="badge-item">🥉 Explorer</span>
            <?php endif; ?>
            <?php if($points >= 500): ?>
                <span class="badge-item">🥈 Aventurier</span>
            <?php endif; ?>
            <?php if($points >= 1000): ?>
                <span class="badge-item">🥇 Géographe Expert</span>
            <?php endif; ?>
            <?php if($points < 100): ?>
                <span class="badge-vide">Réponds à des questions pour débloquer des badges !</span>
            <?php endif; ?>
        </div>

    </div>

    <!-- Statistiques -->
    <div class="stats-card">
        <h2>📊 Mes statistiques</h2>

        <div class="stat-ligne">
            <span>🎯 Questions répondues</span>
            <span class="stat-valeur" style="color:#1B6CA8;"><?= $total ?></span>
        </div>
        <div class="stat-ligne">
            <span>✅ Bonnes réponses</span>
            <span class="stat-valeur" style="color:#10B981;"><?= $bonnes ?></span>
        </div>
        <div class="stat-ligne">
            <span>❌ Mauvaises réponses</span>
            <span class="stat-valeur" style="color:#EF4444;"><?= $total - $bonnes ?></span>
        </div>
        <?php if($total > 0): ?>
        <div class="stat-ligne">
            <span>📈 Taux de réussite</span>
            <span class="stat-valeur" style="color:#F59E0B;"><?= round($bonnes / $total * 100) ?>%</span>
        </div>
        <?php endif; ?>

        <div style="margin-top:20px; text-align:center;">
            <a href="quiz.php" class="btn-jouer">
                🧠 Continuer à jouer !
            </a>
        </div>
    </div>

    <!-- Préférences -->
    <div class="prefs-card">
        <h2>🎨 Mes préférences</h2>

        <?php if($user['couleur']): ?>
        <div class="pref-item">
            <span class="pref-emoji">🌈</span>
            <span>Couleur préférée : <strong><?= htmlspecialchars($user['couleur']) ?></strong></span>
        </div>
        <?php endif; ?>

        <?php if($user['animal']): ?>
        <div class="pref-item">
            <span class="pref-emoji">🐾</span>
            <span>Animal préféré : <strong><?= htmlspecialchars($user['animal']) ?></strong></span>
        </div>
        <?php endif; ?>

        <?php if($user['plat']): ?>
        <div class="pref-item">
            <span class="pref-emoji">🍽️</span>
            <span>Plat préféré : <strong><?= htmlspecialchars($user['plat']) ?></strong></span>
        </div>
        <?php endif; ?>
    </div>

</main>

<?php require_once 'includes/footer.php'; ?>