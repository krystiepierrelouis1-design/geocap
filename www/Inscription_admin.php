<?php
session_start();
require_once 'includes/db.php';
$erreur = "";

if(isset($_POST['pseudo'])){
    if($_POST['code_secret'] != 'GEOCAP2026'){
        $erreur = "❌ Code secret incorrect !";
    } else {
        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $email = htmlspecialchars($_POST['email']);
        $mdp = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
        $role = $_POST['role'];

        $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE pseudo=? OR email=?");
        $stmt->execute([$pseudo, $email]);
        if($stmt->fetch()){
            $erreur = "❌ Pseudo ou email déjà utilisé !";
        } else {
            $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, pseudo, email, mot_de_passe, role, statut, derniere_connexion) VALUES (?,?,?,?,?,?,'actif',NOW())")->execute([$nom, $prenom, $pseudo, $email, $mdp, $role]);
            header("Location: index.php");
            exit;
        }
    }
}
?>
<?php require_once 'includes/header.php'; ?>

<div class="hero">
    <h1>👨‍🏫 Créer un compte Professeur</h1>
    <p>Réservé aux professeurs et administrateurs</p>
</div>

<div style="max-width:480px; margin:40px auto; padding:20px;">
    <div class="carte-style">
        <?php if($erreur): ?>
            <p class="erreur"><?= $erreur ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="nom" placeholder="Ton nom" class="champ" required>
            <input type="text" name="prenom" placeholder="Ton prénom" class="champ" required>
            <input type="text" name="pseudo" placeholder="Pseudo" class="champ" required>
            <input type="email" name="email" placeholder="Email" class="champ" required>
            <input type="password" name="mdp" placeholder="Mot de passe" class="champ" required>
            <select name="role" class="champ" required>
                <option value="professeur">Professeur</option>
                <option value="admin">Administrateur</option>
            </select>
            <input type="password" name="code_secret" placeholder="🔑 Code secret" class="champ" required>
            <button type="submit" class="bouton-vert" style="width:100%;">Créer le compte</button>
        </form>
        <p style="text-align:center; margin-top:15px; font-size:13px;">
            <a href="index.php" style="color:#1B6CA8;">Retour à la connexion</a>
        </p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>