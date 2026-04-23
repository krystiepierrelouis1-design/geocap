<?php
session_start();
require_once 'includes/db.php';
$erreur = "";
if(isset($_POST['pseudo'])){
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $email = htmlspecialchars($_POST['email']);
    $mdp = $_POST['mdp'];
    $stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE pseudo = ? OR email = ?');
    $stmt->execute([$pseudo, $email]);
    $existe = $stmt->fetch();
    if($existe){
        $erreur = "Ce pseudo ou email existe déjà 😊 Essaie un autre !";
    } else {
        $mdp_chiffre = password_hash($mdp, PASSWORD_DEFAULT);
        $stmt2 = $pdo->prepare('INSERT INTO utilisateurs (nom, prenom, pseudo, email, mot_de_passe, role, statut, derniere_connexion) VALUES (?, ?, ?, ?, ?, "enfant", "actif", NOW())');
        $stmt2->execute([$nom, $prenom, $pseudo, $email, $mdp_chiffre]);
        $new_id = $pdo->lastInsertId();
        $stmt3 = $pdo->prepare('INSERT INTO scores (utilisateur_id, points, continent, badges) VALUES (?, 0, "", "")');
        $stmt3->execute([$new_id]);
        $_SESSION['user_id'] = $new_id;
        $_SESSION['pseudo'] = $pseudo;
        $_SESSION['role'] = 'enfant';
        header('Location: accueil.php');
        exit;
    }
}
?>
<?php require_once 'includes/header.php'; ?>
<main class="formulaire">
    <h2>🌍 Créer mon compte GEOCAP</h2>
    <?php if($erreur): ?>
        <p style="color:red"><?= $erreur ?></p>
    <?php endif; ?>
    <form method="POST">
        <input type="text" name="nom" placeholder="Ton nom" required>
        <input type="text" name="prenom" placeholder="Ton prénom" required>
        <input type="text" name="pseudo" placeholder="Choisis un pseudo" required>
        <input type="email" name="email" placeholder="Ton email" required>
        <input type="password" name="mdp" placeholder="Choisis un mot de passe" required>
        <button type="submit">C'est parti pour l'aventure ! 🚀</button>
    </form>
    <p>Tu as déjà un compte ? <a href="index.php">Connecte-toi ici !</a></p>
</main>
<?php require_once 'includes/footer.php'; ?>