<?php
session_start();
require_once 'includes/db.php';
if(!isset($_SESSION['user_id'])){ header("Location: index.php"); exit; }

$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id=?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$succes = "";
$erreur = "";

if(isset($_POST['sauvegarder'])){
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $couleur = $_POST['couleur'];
    $animal = $_POST['animal'];
    $plat = $_POST['plat'];
    $avatar = $_POST['avatar'] ?? $user['avatar'];

    $stmt2 = $pdo->prepare("SELECT id FROM utilisateurs WHERE pseudo=? AND id!=?");
    $stmt2->execute([$pseudo, $_SESSION['user_id']]);
    if($stmt2->fetch()){
        $erreur = "Ce pseudo est déjà pris !";
    } else {
        if(!empty($_POST['nouveau_mdp'])){
            $nouveau_mdp = password_hash($_POST['nouveau_mdp'], PASSWORD_DEFAULT);
            $pdo->prepare("UPDATE utilisateurs SET pseudo=?, couleur=?, animal=?, plat=?, avatar=?, mot_de_passe=? WHERE id=?")->execute([$pseudo, $couleur, $animal, $plat, $avatar, $nouveau_mdp, $_SESSION['user_id']]);
        } else {
            $pdo->prepare("UPDATE utilisateurs SET pseudo=?, couleur=?, animal=?, plat=?, avatar=? WHERE id=?")->execute([$pseudo, $couleur, $animal, $plat, $avatar, $_SESSION['user_id']]);
        }
        $_SESSION['pseudo'] = $pseudo;
        $succes = "✅ Paramètres sauvegardés !";
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
    }
}
?>
<?php require_once 'includes/header.php'; ?>

<div class="hero">
    <h1>⚙️ Mes Paramètres</h1>
    <p>Modifie ton profil !</p>
</div>

<div style="max-width:520px; margin:40px auto; padding:20px;">
    <div class="carte-style">
        <?php if($succes): ?><p class="succes"><?= $succes ?></p><?php endif; ?>
        <?php if($erreur): ?><p class="erreur"><?= $erreur ?></p><?php endif; ?>

        <form method="POST">
            <input type="hidden" name="avatar" id="avatar-choisi" value="<?= $user['avatar'] ?? '🌍' ?>">

            <h3 style="color:#1B6CA8; margin-bottom:15px;">👤 Mon pseudo</h3>
            <input type="text" name="pseudo" value="<?= htmlspecialchars($user['pseudo']) ?>" class="champ" required>

            <h3 style="color:#1B6CA8; margin:20px 0 15px;">🎭 Mon avatar</h3>
            <div class="avatar-grid">
                <?php foreach(['🌍','🦁','🐼','🦊','🐬','🦋','🐉','🦄','🐸','🦅','🐨','🦀'] as $av): ?>
                    <span class="avatar-option <?= ($user['avatar'] ?? '🌍') == $av ? 'choisi' : '' ?>" onclick="choisirAvatar('<?= $av ?>', this)"><?= $av ?></span>
                <?php endforeach; ?>
            </div>

            <h3 style="color:#1B6CA8; margin:20px 0 15px;">🎨 Mes préférences</h3>
            <select name="couleur" class="champ">
                <option value="bleu" <?= $user['couleur']=='bleu'?'selected':'' ?>>💙 Bleu</option>
                <option value="rouge" <?= $user['couleur']=='rouge'?'selected':'' ?>>❤️ Rouge</option>
                <option value="vert" <?= $user['couleur']=='vert'?'selected':'' ?>>💚 Vert</option>
                <option value="jaune" <?= $user['couleur']=='jaune'?'selected':'' ?>>💛 Jaune</option>
                <option value="violet" <?= $user['couleur']=='violet'?'selected':'' ?>>💜 Violet</option>
                <option value="rose" <?= $user['couleur']=='rose'?'selected':'' ?>>🩷 Rose</option>
            </select>
            <select name="animal" class="champ">
                <option value="chien" <?= $user['animal']=='chien'?'selected':'' ?>>🐶 Chien</option>
                <option value="chat" <?= $user['animal']=='chat'?'selected':'' ?>>🐱 Chat</option>
                <option value="lion" <?= $user['animal']=='lion'?'selected':'' ?>>🦁 Lion</option>
                <option value="dauphin" <?= $user['animal']=='dauphin'?'selected':'' ?>>🐬 Dauphin</option>
                <option value="panda" <?= $user['animal']=='panda'?'selected':'' ?>>🐼 Panda</option>
            </select>
            <select name="plat" class="champ">
                <option value="pizza" <?= $user['plat']=='pizza'?'selected':'' ?>>🍕 Pizza</option>
                <option value="pates" <?= $user['plat']=='pates'?'selected':'' ?>>🍝 Pâtes</option>
                <option value="burger" <?= $user['plat']=='burger'?'selected':'' ?>>🍔 Burger</option>
                <option value="sushi" <?= $user['plat']=='sushi'?'selected':'' ?>>🍣 Sushi</option>
                <option value="couscous" <?= $user['plat']=='couscous'?'selected':'' ?>>🫕 Couscous</option>
            </select>

            <h3 style="color:#1B6CA8; margin:20px 0 15px;">🔑 Changer mon mot de passe</h3>
            <input type="password" name="nouveau_mdp" placeholder="Laisser vide si pas de changement" class="champ">

            <button type="submit" name="sauvegarder" class="bouton" style="width:100%; margin-top:10px;">💾 Sauvegarder</button>
        </form>
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