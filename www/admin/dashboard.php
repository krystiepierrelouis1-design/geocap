<?php
session_start();
require_once '../includes/db.php';

if(!isset($_SESSION['user_id']) ||
($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'professeur')){
    header('Location: ../accueil.php'); exit;
}

// Tous les utilisateurs
$stmt = $pdo->query('SELECT * FROM utilisateurs ORDER BY id DESC');
$users = $stmt->fetchAll();

// Messages non lus
$stmt2 = $pdo->query('SELECT M.*, U.pseudo FROM messages M JOIN utilisateurs U ON M.expediteur_id = U.id WHERE M.lu = 0 ORDER BY M.date_envoi DESC');
$messages = $stmt2->fetchAll();

// Nombre total de questions répondues
$stmt3 = $pdo->query('SELECT COUNT(*) as total FROM reponses');
$stats = $stmt3->fetch();
?>
<?php require_once '../includes/header.php'; ?>

<main class="dashboard">
    <h1>⚙️ Tableau de bord</h1>
    <p style="color:#666; margin-bottom:30px;">
        Bienvenue, <strong><?= $_SESSION['pseudo'] ?></strong> !
    </p>

    <!-- Stats rapides -->
    <div style="display:flex; gap:20px; flex-wrap:wrap; margin-bottom:30px;">
        <div style="background:white; border-radius:15px; padding:20px; box-shadow:0 2px 10px rgba(0,0,0,0.05); flex:1; text-align:center;">
            <p style="font-size:35px; font-weight:900; color:#1B6CA8;">
                <?= count($users) ?>
            </p>
            <p style="color:#666;">👥 Utilisateurs</p>
        </div>
        <div style="background:white; border-radius:15px; padding:20px; box-shadow:0 2px 10px rgba(0,0,0,0.05); flex:1; text-align:center;">
            <p style="font-size:35px; font-weight:900; color:#10B981;">
                <?= count($messages) ?>
            </p>
            <p style="color:#666;">📩 Messages non lus</p>
        </div>
        <div style="background:white; border-radius:15px; padding:20px; box-shadow:0 2px 10px rgba(0,0,0,0.05); flex:1; text-align:center;">
            <p style="font-size:35px; font-weight:900; color:#F59E0B;">
                <?= $stats['total'] ?>
            </p>
            <p style="color:#666;">🎯 Questions répondues</p>
        </div>
    </div>

    <!-- Messages non lus -->
    <h2 style="color:#1B6CA8; margin-bottom:15px;">
        📩 Messages non lus (<?= count($messages) ?>)
    </h2>

    <?php if(empty($messages)): ?>
        <p style="color:#666; margin-bottom:30px;">Aucun nouveau message 😊</p>
    <?php else: ?>
        <?php foreach($messages as $msg): ?>
            <div style="background:white; border-radius:15px; padding:20px; margin-bottom:10px; box-shadow:0 2px 10px rgba(0,0,0,0.05); border-left:4px solid #1B6CA8;">
                <p><strong><?= htmlspecialchars($msg['pseudo']) ?></strong>
                <small style="color:#999;"> — <?= $msg['date_envoi'] ?></small></p>
                <p style="margin-top:8px;"><?= htmlspecialchars($msg['contenu']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Liste utilisateurs -->
    <h2 style="color:#1B6CA8; margin-bottom:15px;">
        👥 Tous les utilisateurs (<?= count($users) ?>)
    </h2>

    <?php foreach($users as $u): ?>
        <div class="user-card">
            <div>
                <strong><?= htmlspecialchars($u['pseudo']) ?></strong>
                <span style="color:#666; margin-left:10px;"><?= $u['email'] ?></span>
            </div>
            <span style="background:<?= $u['role'] === 'admin' ? '#1B6CA8' : ($u['role'] === 'professeur' ? '#10B981' : '#F59E0B') ?>; color:white; padding:5px 12px; border-radius:20px; font-size:13px; font-weight:700;">
                <?= $u['role'] ?>
            </span>
        </div>
    <?php endforeach; ?>

</main>

<?php require_once '../includes/footer.php'; ?>