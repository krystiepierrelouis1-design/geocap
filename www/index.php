<?php
session_start();
require_once 'includes/db.php';
$erreur = "";

if(isset($_POST['pseudo'])){
    $pseudo = $_POST['pseudo'];
    $mdp = $_POST['mdp'];
    $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE pseudo=? OR email=?');
    $stmt->execute([$pseudo, $pseudo]);
    $user = $stmt->fetch();
    if($user && password_verify($mdp, $user['mot_de_passe'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['pseudo'] = $user['pseudo'];
        header('Location: accueil.php');
        exit;
    } else {
        $erreur = "Mince ! Réessaie, ne t'inquiète pas ! 😊";
    }
}
?>
<?php require_once 'includes/header.php'; ?>

<!-- Section héro -->
<div class="hero">
    <h1>🌍 Bienvenue sur GEOCAP !</h1>
    <p>Apprends les capitales du monde en t'amusant !</p>
</div>

<main class="formulaire">
    <h2>🔐 Connexion</h2>

    <?php if($erreur): ?>
        <p class="message-erreur"><?= $erreur ?></p>
    <?php endif; ?>

    <form method="POST">
        <input 
            type="text" 
            name="pseudo" 
            placeholder="👤 Pseudo ou email" 
            required>
        <input 
            type="password" 
            name="mdp" 
            placeholder="🔑 Mot de passe" 
            required>
        <button type="submit">
            🚀 C'est parti pour l'aventure !
        </button>
    </form>

    <p style="margin-top:20px; color:#666;">
        Pas encore de compte ?
        <a href="inscription.php" style="color:#1B6CA8; font-weight:700;">
            Inscris-toi ici !
        </a>
    </p>
</main>

<?php require_once 'includes/footer.php'; ?>