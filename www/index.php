<?php
session_start();
// démarre la session (permet de garder l'utilisateur connecté)

require_once 'includes/db.php';
// connecte la base de données

$erreur = "";
// variable pour afficher un message d'erreur

if(isset($_POST['pseudo'])){
    // vérifie si le formulaire a été envoyé

    $pseudo = $_POST['pseudo'];
    // récupère le pseudo ou email

    $mdp = $_POST['mdp'];
    // récupère le mot de passe

    $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE pseudo=? OR email=?');
    // cherche l'utilisateur dans la base

    $stmt->execute([$pseudo, $pseudo]);
    // envoie les valeurs dans la requête

    $user = $stmt->fetch();
    // récupère l'utilisateur

    if($user && password_verify($mdp, $user['mot_de_passe'])){
        // vérifie si le mot de passe est correct

        $_SESSION['user_id'] = $user['id'];
        // stocke l'id utilisateur

        $_SESSION['role'] = $user['role'];
        // stocke le rôle

        $_SESSION['pseudo'] = $user['pseudo'];
        // stocke le pseudo

        header('Location: accueil.php');
        // redirige vers accueil

        exit;
        // arrête le script

    } else {
        // erreur de connexion

        $erreur = "Mince ! Réessaie, ne t'inquiète pas !";
    }
}
?>

<?php require_once 'includes/header.php'; ?>

<h2>Connexion</h2>

<!-- Message erreur -->
<?php if($erreur): ?>
    <p style="color:red"><?= $erreur ?></p>
<?php endif; ?>

<!-- Formulaire de connexion -->
<form method="POST">
    <input name="pseudo" placeholder="Pseudo ou email">
    <input type="password" name="mdp" placeholder="Mot de passe">
    <button>Connexion</button>
</form>

<!-- Lien inscription -->
<p>Pas de compte ?
    <a href="inscription.php">Inscris-toi ici !</a>
</p>

<?php require_once 'includes/footer.php'; ?>