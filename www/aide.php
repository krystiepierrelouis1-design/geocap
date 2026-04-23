<?php
session_start();
require_once 'includes/db.php';
if(!isset($_SESSION['user_id'])){
    header('Location:index.php'); exit;
}
if(isset($_POST['envoyer'])){
    $stmt = $pdo->prepare('INSERT INTO messages (expediteur_id, contenu, date_envoi, lu) VALUES (?, ?, NOW(), 0)');
    $stmt->execute([$_SESSION['user_id'], htmlspecialchars($_POST['question'])]);
    $succes = "Message envoyé ! Le prof répond bientôt 😊";
}
?>
<?php require_once 'includes/header.php'; ?>
<main style="padding:40px; max-width:600px; margin:auto;">
    <h1>💬 Aide & Contact</h1>
    <?php if(isset($succes)): ?>
        <p style="color:green"><?= $succes ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="nom" placeholder="Ton nom" style="width:100%; padding:10px; margin-bottom:10px;">
        <textarea name="question" placeholder="Ta question..." style="width:100%; padding:10px; height:100px; margin-bottom:10px;"></textarea>
        <button name="envoyer" style="background:#1B6CA8; color:white; padding:10px 20px; border:none; border-radius:8px; cursor:pointer;">
            Envoyer ma question
        </button>
    </form>
</main>
<?php require_once 'includes/footer.php'; ?>