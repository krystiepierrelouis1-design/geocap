<?php
session_start();
require_once 'includes/db.php';
if(!isset($_SESSION['user_id'])){ header("Location: index.php"); exit; }

$stmt = $pdo->query("SELECT U.pseudo, U.avatar, S.score FROM scores S JOIN utilisateurs U ON S.utilisateur_id=U.id ORDER BY S.score DESC LIMIT 10");
$classement = $stmt->fetchAll();
?>
<?php require_once 'includes/header.php'; ?>

<div class="hero">
    <h1>🏆 Classement</h1>
    <p>Les meilleurs explorateurs de GEOCAP !</p>
</div>

<div style="max-width:500px; margin:40px auto; padding:20px;">
    <div class="carte-style">
        <?php if(empty($classement)): ?>
            <p style="text-align:center; color:#888;">Aucun score pour le moment !</p>
        <?php else: ?>
            <?php foreach($classement as $i => $joueur): ?>
                <div style="display:flex; align-items:center; padding:12px 0; border-bottom:1px solid #f5f5f5; gap:15px;">
                    <span style="font-size:24px; font-weight:800; color:<?= $i==0?'#F59E0B':($i==1?'#9CA3AF':($i==2?'#CD7C2F':'#1B6CA8')) ?>; width:35px;">
                        <?= $i==0?'🥇':($i==1?'🥈':($i==2?'🥉':'#'.($i+1))) ?>
                    </span>
                    <span style="font-size:28px;"><?= $joueur['avatar'] ?? '🌍' ?></span>
                    <span style="font-weight:700; flex:1;"><?= htmlspecialchars($joueur['pseudo']) ?></span>
                    <span style="font-weight:800; color:#F59E0B;"><?= $joueur['score'] ?> ⭐</span>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>