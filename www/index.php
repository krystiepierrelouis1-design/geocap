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
    
    if($user){
        // Vérifie si compte bloqué
        if($user['role'] === 'bloque'){
            $erreur = "😔 Ton compte est bloqué. Contacte ton professeur pour le débloquer.";
        }
        // Vérifie si trop de tentatives
        elseif($user['tentatives'] >= 3){
            // Bloque le compte
            $pdo->prepare('UPDATE utilisateurs SET role="bloque" WHERE id=?')->execute([$user['id']]);
            $erreur = "🔒 Trop de tentatives ! Ton compte est bloqué. Contacte ton professeur.";
        }
        // Vérifie le mot de passe
        elseif($user && password_verify($mdp, $user['mot_de_passe'])){
            // Remet tentatives à 0
            $pdo->prepare('UPDATE utilisateurs SET tentatives=0, derniere_connexion=NOW() WHERE id=?')->execute([$user['id']]);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['pseudo'] = $user['pseudo'];
            if($user['role'] === 'admin' || $user['role'] === 'professeur'){
                header('Location: admin/dashboard.php');
            } else {
                header('Location: accueil.php');
            }
            exit;
        } else {
            // Mauvais mot de passe → augmente tentatives
            $tentatives = $user['tentatives'] + 1;
            $pdo->prepare('UPDATE utilisateurs SET tentatives=? WHERE id=?')->execute([$tentatives, $user['id']]);
            $restantes = 3 - $tentatives;
            if($restantes > 0){
                $erreur = "😕 Mince ! Mauvais mot de passe. Il te reste $restantes tentative(s) !";
            } else {
                $pdo->prepare('UPDATE utilisateurs SET role="bloque" WHERE id=?')->execute([$user['id']]);
                $erreur = "🔒 Trop de tentatives ! Ton compte est bloqué. Contacte ton professeur.";
            }
        }
    } else {
        $erreur = "😕 Identifiant inconnu. Vérifie ton pseudo ou email !";
    }
}
?>
<?php require_once 'includes/header.php'; ?>

<style>
.connexion-page {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 20px;
}
.connexion-container {
    width: 100%;
    max-width: 900px;
}
.connexion-titre {
    text-align: center;
    margin-bottom: 40px;
}
.connexion-titre h1 {
    font-size: 36px;
    font-weight: 900;
    color: #1B6CA8;
    margin-bottom: 10px;
}
.connexion-titre p {
    color: #888;
    font-size: 16px;
}
.deux-colonnes {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
    justify-content: center;
}
.colonne-connexion {
    background: white;
    border-radius: 24px;
    padding: 40px;
    flex: 1;
    min-width: 280px;
    max-width: 400px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    text-align: center;
}
.colonne-connexion.enfant { border-top: 5px solid #1B6CA8; }
.colonne-connexion.admin { border-top: 5px solid #10B981; }
.colonne-connexion h2 { font-size: 22px; font-weight: 800; margin-bottom: 8px; }
.colonne-connexion.enfant h2 { color: #1B6CA8; }
.colonne-connexion.admin h2 { color: #10B981; }
.colonne-connexion p.desc { color: #888; font-size: 14px; margin-bottom: 25px; }
.colonne-connexion input {
    width: 100%;
    padding: 14px 18px;
    margin-bottom: 15px;
    border: 2px solid #E0E0E0;
    border-radius: 12px;
    font-size: 15px;
    font-family: 'Nunito', sans-serif;
    outline: none;
    color: #2D2D2D;
    transition: border 0.3s;
}
.colonne-connexion input:focus { border-color: #1B6CA8; }
.btn-enfant {
    background: linear-gradient(135deg, #1B6CA8, #0D4A7A);
    color: white;
    padding: 14px 30px;
    border: none;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    font-family: 'Nunito', sans-serif;
    width: 100%;
    transition: all 0.3s;
}
.btn-admin {
    background: linear-gradient(135deg, #10B981, #059669);
    color: white;
    padding: 14px 30px;
    border: none;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    font-family: 'Nunito', sans-serif;
    width: 100%;
    transition: all 0.3s;
}
.btn-enfant:hover, .btn-admin:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
.icone-grande { font-size: 50px; margin-bottom: 15px; }
</style>

<div class="connexion-page">
    <div class="connexion-container">
        <div class="connexion-titre">
            <h1>🌍 Bienvenue sur GEOCAP !</h1>
            <p>Apprends les capitales du monde en t'amusant !</p>
        </div>

        <?php if($erreur): ?>
            <p style="text-align:center; background:#FEE2E2; color:#E74C3C; padding:12px; border-radius:10px; margin-bottom:20px; font-weight:700;">
                <?= $erreur ?>
            </p>
        <?php endif; ?>

        <div class="deux-colonnes">
            <div class="colonne-connexion enfant">
                <div class="icone-grande">👦</div>
                <h2>Espace Élève</h2>
                <p class="desc">Tu es un enfant ? Connecte-toi ici pour jouer !</p>
                <form method="POST">
                    <input type="text" name="pseudo" placeholder="👤 Ton pseudo ou email" required>
                    <input type="password" name="mdp" placeholder="🔑 Ton mot de passe" required>
                    <button type="submit" class="btn-enfant">🚀 C'est parti !</button>
                </form>
                <p style="margin-top:15px; color:#888; font-size:14px;">
                    Pas de compte ?
                    <a href="inscription.php" style="color:#1B6CA8; font-weight:700;">Inscris-toi ici !</a>
                </p>
            </div>

            <div class="colonne-connexion admin">
                <div class="icone-grande">👨‍🏫</div>
                <h2>Espace Professeur / Admin</h2>
                <p class="desc">Tu es un professeur ou un administrateur ?</p>
                <form method="POST">
                    <input type="text" name="pseudo" placeholder="📧 Ton email" required>
                    <input type="password" name="mdp" placeholder="🔑 Ton mot de passe" required>
                    <button type="submit" class="btn-admin">🔐 Accéder au tableau de bord</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>