<?php
session_start();
require_once 'includes/db.php';
$erreur = "";
$etape = $_POST['etape'] ?? 1;

if(isset($_POST['pseudo']) && $_POST['etape'] == 2){
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $email = htmlspecialchars($_POST['email']);
    $mdp = $_POST['mdp'];
    $couleur = htmlspecialchars($_POST['couleur'] ?? '');
    $animal = htmlspecialchars($_POST['animal'] ?? '');
    $plat = htmlspecialchars($_POST['plat'] ?? '');
    $avatar = htmlspecialchars($_POST['avatar'] ?? '🌍');

    $stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE pseudo = ? OR email = ?');
    $stmt->execute([$pseudo, $email]);
    $existe = $stmt->fetch();

    if($existe){
        $erreur = "Ce pseudo ou email existe déjà 😊 Essaie un autre !";
        $etape = 1;
    } else {
        $mdp_chiffre = password_hash($mdp, PASSWORD_DEFAULT);
        $stmt2 = $pdo->prepare(
            'INSERT INTO utilisateurs (nom, prenom, pseudo, email, mot_de_passe, couleur, animal, plat, role, statut, derniere_connexion)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, "enfant", "actif", NOW())'
        );
        $stmt2->execute([$nom, $prenom, $pseudo, $email, $mdp_chiffre, $couleur, $animal, $plat]);
        $new_id = $pdo->lastInsertId();
        $stmt3 = $pdo->prepare('INSERT INTO scores (utilisateur_id, score, continent, badges) VALUES (?, 0, "", "")');
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

<style>
.inscription-page {
    max-width: 600px;
    margin: 40px auto;
    padding: 20px;
}
.inscription-card {
    background: white;
    border-radius: 24px;
    padding: 40px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.08);
    text-align: center;
    border-top: 5px solid #1B6CA8;
}
.inscription-card h2 {
    color: #1B6CA8;
    font-size: 26px;
    margin-bottom: 8px;
}
.inscription-card p.desc {
    color: #888;
    margin-bottom: 25px;
}
.inscription-card input,
.inscription-card select {
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
    text-align: left;
}
.inscription-card input:focus,
.inscription-card select:focus {
    border-color: #1B6CA8;
}
.section-titre {
    color: #1B6CA8;
    font-weight: 800;
    font-size: 16px;
    margin: 20px 0 10px;
    text-align: left;
}
.avatars-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    margin-bottom: 20px;
}
.avatar-option {
    font-size: 35px;
    cursor: pointer;
    padding: 10px;
    border-radius: 12px;
    border: 3px solid transparent;
    transition: all 0.2s;
}
.avatar-option:hover,
.avatar-option.selected {
    border-color: #1B6CA8;
    background: #E8F4FD;
    transform: scale(1.1);
}
.btn-inscription {
    background: linear-gradient(135deg, #1B6CA8, #0D4A7A);
    color: white;
    padding: 16px 30px;
    border: none;
    border-radius: 25px;
    font-size: 17px;
    font-weight: 700;
    cursor: pointer;
    font-family: 'Nunito', sans-serif;
    width: 100%;
    transition: all 0.3s;
    margin-top: 10px;
}
.btn-inscription:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(27,108,168,0.4);
}
</style>

<div class="inscription-page">
    <div class="inscription-card">
        <div style="font-size:50px; margin-bottom:15px;">🌍</div>
        <h2>Rejoins GEOCAP !</h2>
        <p class="desc">Crée ton compte et explore le monde !</p>

        <?php if($erreur): ?>
            <p style="background:#FEE2E2; color:#E74C3C; padding:12px; border-radius:10px; margin-bottom:20px; font-weight:700;">
                <?= $erreur ?>
            </p>
        <?php endif; ?>

        <form method="POST" id="form-inscription">
            <input type="hidden" name="etape" value="2">
            <input type="hidden" name="avatar" id="avatar-choisi" value="🌍">

            <!-- Infos personnelles -->
            <p class="section-titre">👤 Tes informations</p>
            <input type="text" name="nom" placeholder="Ton nom" required>
            <input type="text" name="prenom" placeholder="Ton prénom" required>
            <input type="text" name="pseudo" placeholder="🎮 Choisis un pseudo" required>
            <input type="email" name="email" placeholder="📧 Ton email" required>
            <input type="password" name="mdp" placeholder="🔑 Choisis un mot de passe" required>

            <!-- Choix avatar -->
            <p class="section-titre">🎭 Choisis ton avatar !</p>
            <div class="avatars-grid">
                <?php
                $avatars = ['🌍','🦁','🐼','🦊','🐬','🦋','🐉','🦄','🐸','🦅','🐨','🦀'];
                foreach($avatars as $av):
                ?>
                    <span class="avatar-option" onclick="choisirAvatar('<?= $av ?>', this)">
                        <?= $av ?>
                    </span>
                <?php endforeach; ?>
            </div>

            <!-- Questions amusantes -->
            <p class="section-titre">🎨 Pour personnaliser ton profil !</p>

            <select name="couleur">
                <option value="">🌈 Ta couleur préférée ?</option>
                <option value="bleu">💙 Bleu</option>
                <option value="rouge">❤️ Rouge</option>
                <option value="vert">💚 Vert</option>
                <option value="jaune">💛 Jaune</option>
                <option value="violet">💜 Violet</option>
                <option value="orange">🧡 Orange</option>
                <option value="rose">🩷 Rose</option>
            </select>

            <select name="animal">
                <option value="">🐾 Ton animal préféré ?</option>
                <option value="chien">🐶 Chien</option>
                <option value="chat">🐱 Chat</option>
                <option value="lion">🦁 Lion</option>
                <option value="dauphin">🐬 Dauphin</option>
                <option value="elephant">🐘 Éléphant</option>
                <option value="panda">🐼 Panda</option>
                <option value="renard">🦊 Renard</option>
            </select>

            <select name="plat">
                <option value="">🍽️ Ton plat préféré ?</option>
                <option value="pizza">🍕 Pizza</option>
                <option value="pates">🍝 Pâtes</option>
                <option value="sushi">🍣 Sushi</option>
                <option value="burger">🍔 Burger</option>
                <option value="crepes">🥞 Crêpes</option>
                <option value="couscous">🫕 Couscous</option>
                <option value="riz">🍚 Riz</option>
            </select>

            <button type="submit" class="btn-inscription">
                🚀 C'est parti pour l'aventure !
            </button>

        </form>

        <p style="margin-top:20px; color:#888; font-size:14px;">
            Tu as déjà un compte ?
            <a href="index.php" style="color:#1B6CA8; font-weight:700;">
                Connecte-toi ici !
            </a>
        </p>
    </div>
</div>

<script>
function choisirAvatar(avatar, element) {
    document.getElementById('avatar-choisi').value = avatar;
    document.querySelectorAll('.avatar-option').forEach(function(el) {
        el.classList.remove('selected');
    });
    element.classList.add('selected');
}
</script>

<?php require_once 'includes/footer.php'; ?>