<?php
session_start();
// démarre la session (permet de savoir qui est connecté)

require_once 'includes/db.php';
// connecte la base de données

if(!isset($_SESSION['user_id'])){
    // si l'utilisateur n'est pas connecté

    header('Location:index.php'); 
    // redirige vers la page de connexion

    exit;
    // arrête le code
}

/* 
   UTILISATEUR
*/

// récupère les infos de l'utilisateur connecté
$stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE id = ?');
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
// stocke les infos utilisateur

/* 
   SCORES
 */

// récupère les points de l'utilisateur
$stmt2 = $pdo->prepare('SELECT * FROM scores WHERE utilisateur_id = ?');
$stmt2->execute([$_SESSION['user_id']]);
$scores = $stmt2->fetch();
// stocke les points

/* 
   STATISTIQUES
 */

// récupère les statistiques de réponses
$stmt3 = $pdo->prepare('SELECT COUNT(*) as total, SUM(est_correcte) as bonnes FROM reponses WHERE utilisateur_id = ?');
$stmt3->execute([$_SESSION['user_id']]);
$stats = $stmt3->fetch();
// total questions + bonnes réponses
?>

<?php require_once 'includes/header.php'; ?>
// affiche le haut du site

<main class="page-profil">

    <h1>👤 Mon Profil</h1>
    // titre de la page

    <p><?= htmlspecialchars($user['pseudo']) ?></p>
    // affiche le pseudo (sécurisé)

    <p class="score-total"><?= $scores['points'] ?? 0 ?> points</p>
    // affiche les points (0 si rien)

    <div>

    <?php if($scores && $scores['points'] >= 100): ?>
        // badge niveau 1
        <span class="badge">🥉 Explorer</span>
    <?php endif; ?>

    <?php if($scores && $scores['points'] >= 500): ?>
        // badge niveau 2
        <span class="badge">🥈 Aventurier</span>
    <?php endif; ?>

    <?php if($scores && $scores['points'] >= 1000): ?>
        // badge niveau 3
        <span class="badge">🥇 Expert</span>
    <?php endif; ?>

    </div>

    <p>Total questions : <?= $stats['total'] ?></p>
    // nombre total de questions répondues

    <p>Bonnes réponses : <?= $stats['bonnes'] ?></p>
    // nombre de bonnes réponses

    <?php if($stats['total'] > 0): ?>
        // évite division par 0

        <p>Taux : <?= round($stats['bonnes'] / $stats['total'] * 100) ?>%</p>
        // pourcentage de réussite
    <?php endif; ?>

</main>

<?php require_once 'includes/footer.php'; ?>
// affiche le bas du site