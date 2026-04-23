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
    $couleur = htmlspecialchars($_POST['couleur'] ?? '');
    $animal = htmlspecialchars($_POST['animal'] ?? '');
    $plat = htmlspecialchars($_POST['plat'] ?? '');

    // Vérifie si pseudo ou email déjà pris
    $stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE pseudo = ? OR email = ?');
    $stmt->execute([$pseudo, $email]);
    $existe = $stmt->fetch();

    if($existe){
        $erreur = "Ce pseudo ou email existe déjà 😊 Essaie un autre !";
    } else {
        $mdp_chiffre = password_hash($mdp, PASSWORD_DEFAULT);
        $stmt2 = $pdo->prepare(
            'INSERT INTO utilisateurs (nom, prenom, pseudo, email, mot_de_passe, couleur, animal, plat, role, statut, derniere_connexion)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, "enfant", "actif", NOW())'
        );
        $stmt2->execute([$nom, $prenom, $pseudo, $email, $mdp_chiffre, $couleur, $animal, $plat]);
        $new_id = $pdo->lastInsertId();

        // Crée un score vide
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

<main>
<div class="formulaire">
    <h2>🌍 Rejoins GEOCAP !</h2>
    <p style="color:#666; margin-bottom:20px;">
        Crée ton compte et explore le monde ! 🚀
    </p>

    <?php if($erreur): ?>
        <p class="message-erreur"><?= $erreur ?></p>
    <?php endif; ?>

    <form method="POST">

        <!-- Infos personnelles -->
        <input type="text" name="nom" placeholder="👤 Ton nom" required>
        <input type="text" name="prenom" placeholder="😊 Ton prénom" required>
        <input type="text" name="pseudo" placeholder="🎮 Choisis un pseudo" required>
        <input type="email" name="email" placeholder="📧 Ton email" required>
        <input type="password" name="mdp" placeholder="🔑 Choisis un mot de passe" required>

        <!-- Questions amusantes -->
        <p style="color:#1B6CA8; font-weight:800; margin:20px 0 10px; font-size:16px;">
            🎨 Pour personnaliser ton profil :
        </p>

        <select name="couleur" style="width:100%; padding:14px; border:2px solid #E0E0E0; border-radius:12px; font-size:15px; font-family:'Nunito',sans-serif; margin-bottom:15px;">
            <option value="">🌈 Ta couleur préférée ?</option>
            <option value="bleu">💙 Bleu</option>
            <option value="rouge">❤️ Rouge</option>
            <option value="vert">💚 Vert</option>
            <option value="jaune">💛 Jaune</option>
            <option value="violet">💜 Violet</option>
            <option value="orange">🧡 Orange</option>
            <option value="rose">🩷 Rose</option>
        </select>

        <select name="animal" style="width:100%; padding:14px; border:2px solid #E0E0E0; border-radius:12px; font-size:15px; font-family:'Nunito',sans-serif; margin-bottom:15px;">
            <option value="">🐾 Ton animal préféré ?</option>
            <option value="chien">🐶 Chien</option>
            <option value="chat">🐱 Chat</option>
            <option value="lion">🦁 Lion</option>
            <option value="dauphin">🐬 Dauphin</option>
            <option value="elephant">🐘 Éléphant</option>
            <option value="panda">🐼 Panda</option>
            <option value="renard">🦊 Renard</option>
        </select>

        <select name="plat" style="width:100%; padding:14px; border:2px solid #E0E0E0; border-radius:12px; font-size:15px; font-family:'Nunito',sans-serif; margin-bottom:15px;">
            <option value="">🍽️ Ton plat préféré ?</option>
            <option value="pizza">🍕 Pizza</option>
            <option value="pates">🍝 Pâtes</option>
            <option value="sushi">🍣 Sushi</option>
            <option value="burger">🍔 Burger</option>
            <option value="crepes">🥞 Crêpes</option>
            <option value="couscous">🫕 Couscous</option>
            <option value="riz">🍚 Riz</option>
        </select>

        <button type="submit">
            🚀 C'est parti pour l'aventure !
        </button>

    </form>

    <p style="margin-top:20px; color:#666;">
        Tu as déjà un compte ? 
        <a href="index.php" style="color:#1B6CA8; font-weight:700;">Connecte-toi ici !</a>
    </p>

</div>
</main>

<?php require_once 'includes/footer.php'; ?>