<?php
session_start();
require_once 'includes/db.php';
$erreur = "";
$etape = "connexion";

if(isset($_POST['action']) && $_POST['action'] == 'connexion'){
    $pseudo = $_POST['pseudo'];
    $mdp = $_POST['mdp'];
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE pseudo=? OR email=?");
    $stmt->execute([$pseudo, $pseudo]);
    $user = $stmt->fetch();
    if($user){
        if($user['role'] == 'bloque'){
            $erreur = "😔 Ton compte est bloqué. Contacte ton professeur.";
        } elseif($user['tentatives'] >= 3){
            $pdo->prepare("UPDATE utilisateurs SET role='bloque' WHERE id=?")->execute([$user['id']]);
            $erreur = "🔒 Trop de tentatives ! Compte bloqué.";
        } elseif(password_verify($mdp, $user['mot_de_passe'])){
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['pseudo'] = $user['pseudo'];
            $pdo->prepare("UPDATE utilisateurs SET tentatives=0, derniere_connexion=NOW() WHERE id=?")->execute([$user['id']]);
            if($user['role'] == 'admin' || $user['role'] == 'professeur'){
                header("Location: admin/dashboard.php");
            } else {
                header("Location: accueil.php");
            }
            exit;
        } else {
            $tentatives = $user['tentatives'] + 1;
            $pdo->prepare("UPDATE utilisateurs SET tentatives=? WHERE id=?")->execute([$tentatives, $user['id']]);
            $restantes = 3 - $tentatives;
            $erreur = "❌ Mauvais mot de passe. Il te reste $restantes tentative(s).";
        }
    } else {
        $erreur = "❓ Identifiant inconnu.";
    }
}

if(isset($_POST['action']) && $_POST['action'] == 'mot_de_passe_oublie'){
    $pseudo = $_POST['pseudo'];
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE pseudo=? OR email=?");
    $stmt->execute([$pseudo, $pseudo]);
    $user = $stmt->fetch();
    if($user){
        $_SESSION['reset_user_id'] = $user['id'];
        $_SESSION['question_secrete'] = $user['question_secrete'];
        $etape = "question";
    } else {
        $erreur = "Identifiant inconnu.";
    }
}

if(isset($_POST['action']) && $_POST['action'] == 'verifier_reponse'){
    $reponse = strtolower(trim($_POST['reponse_secrete']));
    $user_id = $_SESSION['reset_user_id'];
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id=?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    if(strtolower(trim($user['reponse_secrete'])) == $reponse){
        $_SESSION['peut_reset'] = true;
        $etape = "nouveau_mdp";
    } else {
        $erreur = "Mauvaise réponse !";
        $etape = "question";
    }
}

if(isset($_POST['action']) && $_POST['action'] == 'nouveau_mdp'){
    if($_SESSION['peut_reset']){
        $nouveau_mdp = password_hash($_POST['nouveau_mdp'], PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE utilisateurs SET mot_de_passe=?, tentatives=0, role='enfant' WHERE id=?")->execute([$nouveau_mdp, $_SESSION['reset_user_id']]);
        unset($_SESSION['reset_user_id'], $_SESSION['question_secrete'], $_SESSION['peut_reset']);
        $succes = "✅ Mot de passe changé ! Tu peux te connecter.";
    }
}
?>
<?php require_once 'includes/header.php'; ?>

<div class="hero">
    <h1>🌍 Bienvenue sur GEOCAP !</h1>
    <p>Apprends les capitales du monde en t'amusant !</p>
</div>

<div style="max-width:900px; margin:40px auto; padding:20px;">

    <?php if(isset($succes)): ?>
        <p class="succes"><?= $succes ?></p>
    <?php endif; ?>

    <?php if($erreur): ?>
        <p class="erreur"><?= $erreur ?></p>
    <?php endif; ?>

    <?php if($etape == 'connexion'): ?>

    <div style="display:flex; gap:30px; flex-wrap:wrap; justify-content:center;">

        <div class="carte-style" style="width:300px;">
            <h2 style="color:#1B6CA8; margin-bottom:5px;">👦 Espace Élève</h2>
            <p style="color:#888; margin-bottom:20px; font-size:14px;">Tu es un enfant ? Connecte-toi ici !</p>
            <form method="POST">
                <input type="hidden" name="action" value="connexion">
                <input type="text" name="pseudo" placeholder="Pseudo ou email" class="champ" required>
                <input type="password" name="mdp" placeholder="Mot de passe" class="champ" required>
                <button type="submit" class="bouton" style="width:100%;">🚀 C'est parti !</button>
            </form>
            <p style="margin-top:12px; font-size:13px; text-align:center;">
                Pas de compte ? <a href="inscription.php" style="color:#1B6CA8; font-weight:700;">Inscris-toi !</a>
            </p>
            <p style="margin-top:8px; font-size:13px; text-align:center;">
                <a href="?oublie=1" style="color:#888;">Mot de passe oublié ?</a>
            </p>
        </div>

        <div class="carte-style" style="width:300px; border-top:4px solid #10B981;">
            <h2 style="color:#10B981; margin-bottom:5px;">👨‍🏫 Espace Professeur</h2>
            <p style="color:#888; margin-bottom:20px; font-size:14px;">Tu es professeur ou admin ?</p>
            <form method="POST">
                <input type="hidden" name="action" value="connexion">
                <input type="text" name="pseudo" placeholder="Email" class="champ" required>
                <input type="password" name="mdp" placeholder="Mot de passe" class="champ" required>
                <button type="submit" class="bouton-vert" style="width:100%;">🔐 Accéder</button>
            </form>
            <p style="margin-top:12px; font-size:13px; text-align:center;">
                <a href="inscription_admin.php" style="color:#10B981; font-weight:700;">Créer un compte prof</a>
            </p>
        </div>

    </div>

    <?php elseif($etape == 'question'): ?>
    <div class="carte-style" style="max-width:400px; margin:auto; text-align:center;">
        <h2 style="color:#1B6CA8; margin-bottom:20px;">🔑 Question secrète</h2>
        <p style="margin-bottom:20px; color:#666;"><?= htmlspecialchars($_SESSION['question_secrete']) ?></p>
        <form method="POST">
            <input type="hidden" name="action" value="verifier_reponse">
            <input type="text" name="reponse_secrete" placeholder="Ta réponse..." class="champ" required>
            <button type="submit" class="bouton" style="width:100%;">Vérifier</button>
        </form>
    </div>

    <?php elseif($etape == 'nouveau_mdp'): ?>
    <div class="carte-style" style="max-width:400px; margin:auto; text-align:center;">
        <h2 style="color:#1B6CA8; margin-bottom:20px;">🔒 Nouveau mot de passe</h2>
        <form method="POST">
            <input type="hidden" name="action" value="nouveau_mdp">
            <input type="password" name="nouveau_mdp" placeholder="Ton nouveau mot de passe" class="champ" required>
            <button type="submit" class="bouton" style="width:100%;">Changer le mot de passe</button>
        </form>
    </div>

    <?php endif; ?>

    <?php if(isset($_GET['oublie'])): ?>
    <div class="carte-style" style="max-width:400px; margin:20px auto; text-align:center;">
        <h2 style="color:#1B6CA8; margin-bottom:20px;">🔑 Mot de passe oublié</h2>
        <form method="POST">
            <input type="hidden" name="action" value="mot_de_passe_oublie">
            <input type="text" name="pseudo" placeholder="Ton pseudo ou email" class="champ" required>
            <button type="submit" class="bouton" style="width:100%;">Retrouver mon compte</button>
        </form>
    </div>
    <?php endif; ?>

</div>

<?php require_once 'includes/footer.php'; ?>