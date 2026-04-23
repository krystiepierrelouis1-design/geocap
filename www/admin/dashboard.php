<?php
session_start();
require_once '../includes/db.php';
if(!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'professeur')){
    header('Location: ../accueil.php'); exit;
}
$stmt = $pdo->query('SELECT * FROM utilisateurs ORDER BY id DESC');
$users = $stmt->fetchAll();
?>
<?php require_once '../includes/header.php'; ?>
<main class="dashboard">
    <h1>⚙️ Dashboard Admin</h1>
    <h2>👥 Utilisateurs (<?= count($users) ?>)</h2>
    <?php foreach($users as $u): ?>
        <p>
            <strong><?= htmlspecialchars($u['pseudo']) ?></strong>
            — <?= $u['role'] ?>
            — <?= $u['email'] ?>
        </p>
    <?php endforeach; ?>
</main>
<?php require_once '../includes/footer.php'; ?>