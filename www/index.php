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
        $erreur = "Mince ! Réessaie, ne t'inquiète pas !";
    }
}
?>
<?php require_once 'includes/header.php'; ?>
<main class="formulaire">
    <h2>🌍 Connexion GEOCAP</h2>
    <?php if($erreur): ?>
        <p style="color:red"><?= $erreur ?></p>
    <?php endif; ?>
    <form method="POST">
        <input name="pseudo" placeholder="Pseudo ou email">
        <input type="password" name="mdp" placeholder="Mot de passe">
        <button>Connexion</button>
    </form>
    <p>Pas de compte ? <a href="inscription.php">Inscris-toi ici !</a></p>
</main>
<?php require_once 'includes/footer.php'; ?>