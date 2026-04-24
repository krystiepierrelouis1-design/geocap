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
    $couleur = $_POST['couleur'];
    $animal = $_POST['animal'];
    $plat = $_POST['plat'];
    $avatar = $_POST['avatar'] ?? '🌍';
    $question_secrete = htmlspecialchars($_POST['question_secrete']);
    $reponse_secrete = htmlspecialchars($_POST['reponse_secrete']);

    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE pseudo=? OR email=?");
    $stmt->execute([$pseudo, $email]);

    if($stmt->fetch()){
        $erreur = "❌ Ce pseudo ou email est déjà utilisé !";
    } else {
        $mdp_hash = password_hash($mdp, PASSWORD_DEFAULT);
        $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, pseudo, email, mot_de_passe, couleur, animal, plat, avatar, question_secrete, reponse_secrete, role, statut, derniere_connexion) VALUES (?,?,?,?,?,?,?,?,?,?,?,'enfant','actif',NOW())")->execute([$nom, $prenom, $pseudo, $email, $mdp_hash, $couleur, $animal, $plat, $avatar, $question_secrete, $reponse_secrete]);
        $new_id = $pdo->lastInsertId();
        $pdo->prepare("INSERT INTO scores (utilisateur_id, score, continent, badges) VALUES (?,0,'','')")->execute([$new_id]);
        $_SESSION['user_id'] = $new_id;
        $_SESSION['pseudo'] = $pseudo;
        $_SESSION['role'] = 'enfant';
        header("Location: accueil.php");
        exit;
    }
}
?>
<?php require_once 'includes/header.php'; ?>

<div class="hero">
    <h1>🌍 Rejoins GEOCAP !</h1>
    <p>Crée ton compte et explore le monde !</p>
</div>

<div style="max-width:550px; margin:40px auto; padding:20px;">
    <div class="carte-style">

        <?php if($erreur): ?>
            <p class="erreur"><?= $erreur ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="avatar" id="avatar-choisi" value="🌍">

            <h3 style="color:#1B6CA8; margin-bottom:15px;">👤 Tes informations</h3>
            <input type="text" name="nom" placeholder="Ton nom" class="champ" required>
            <input type="text" name="prenom" placeholder="Ton prénom" class="champ" required>
            <input type="text" name="pseudo" placeholder="Choisis un pseudo" class="champ" required>
            <input type="email" name="email" placeholder="Ton email" class="champ" required>
            <input type="password" name="mdp" placeholder="Ton mot de passe" class="champ" required>

            <h3 style="color:#1B6CA8; margin:20px 0 10px;">🎭 Choisis ton avatar !</h3>
            <div class="avatar-grid">
                <?php foreach(['🌍','🦁','🐼','🦊','🐬','🦋','🐉','🦄','🐸','🦅','🐨','🦀'] as $av): ?>
                    <span class="avatar-option" onclick="choisirAvatar('<?= $av ?>', this)"><?= $av ?></span>
                <?php endforeach; ?>
            </div>

            <h3 style="color:#1B6CA8; margin:20px 0 10px;">🎨 Tes préférences</h3>
            <select name="couleur" class="champ">
                <option value="">Ta couleur préférée ?</option>
                <option value="bleu">💙 Bleu</option>
                <option value="rouge">❤️ Rouge</option>
                <option value="vert">💚 Vert</option>
                <option value="jaune">💛 Jaune</option>
                <option value="violet">💜 Violet</option>
                <option value="rose">🩷 Rose</option>
            </select>
            <select name="animal" class="champ">
                <option value="">Ton animal préféré ?</option>
                <option value="chien">🐶 Chien</option>
                <option value="chat">🐱 Chat</option>
                <option value="lion">🦁 Lion</option>
                <option value="dauphin">🐬 Dauphin</option>
                <option value="panda">🐼 Panda</option>
            </select>
            <select name="plat" class="champ">
                <option value="">Ton plat préféré ?</option>
                <option value="pizza">🍕 Pizza</option>
                <option value="pates">🍝 Pâtes</option>
                <option value="burger">🍔 Burger</option>
                <option value="sushi">🍣 Sushi</option>
                <option value="couscous">🫕 Couscous</option>
            </select>

            <h3 style="color:#1B6CA8; margin:20px 0 10px;">🔑 Question secrète</h3>
            <p style="color:#888; font-size:13px; margin-bottom:10px;">Si tu oublies ton mot de passe, on te posera cette question !</p>
            <select name="question_secrete" class="champ" required>
                <option value="">Choisis une question...</option>
                <option value="C'est quoi le prénom de ta maman ?">C'est quoi le prénom de ta maman ?</option>
                <option value="C'est quoi le nom de ton école ?">C'est quoi le nom de ton école ?</option>
                <option value="C'est quoi ton animal préféré ?">C'est quoi ton animal préféré ?</option>
                <option value="C'est quoi ta ville ?">C'est quoi ta ville ?</option>
                <option value="C'est quoi ton plat préféré ?">C'est quoi ton plat préféré ?</option>
            </select>
            <input type="text" name="reponse_secrete" placeholder="Ta réponse..." class="champ" required>

            <button type="submit" class="bouton" style="width:100%; margin-top:10px; font-size:17px;">🚀 C'est parti !</button>
        </form>

        <p style="text-align:center; margin-top:15px; font-size:14px;">
            Déjà un compte ? <a href="index.php" style="color:#1B6CA8; font-weight:700;">Connecte-toi !</a>
        </p>
    </div>
</div>

<script>
function choisirAvatar(avatar, el){
    document.getElementById('avatar-choisi').value = avatar;
    document.querySelectorAll('.avatar-option').forEach(function(e){ e.classList.remove('choisi'); });
    el.classList.add('choisi');
}
</script>

<?php require_once 'includes/footer.php'; ?>