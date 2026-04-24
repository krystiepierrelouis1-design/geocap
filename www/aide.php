<?php
session_start();
require_once 'includes/db.php';
if(!isset($_SESSION['user_id'])){ header("Location: index.php"); exit; }
$succes = ""; $erreur = "";
if(isset($_POST['envoyer'])){
    $question = htmlspecialchars($_POST['question']);
    if(empty($question)){ $erreur = "Ecris ta question !"; }
    else { $pdo->prepare("INSERT INTO messages (expediteur_id, contenu, date_envoi, lu) VALUES (?,?,NOW(),0)")->execute([$_SESSION['user_id'], $question]); $succes = "✅ Message envoyé !"; }
}
?>
<?php require_once 'includes/header.php'; ?>

<div class="hero">
    <h1>💬 Aide</h1>
    <p>On est là pour t'aider !</p>
</div>

<div style="max-width:600px; margin:40px auto; padding:20px;">

    <h2 style="color:#1B6CA8; margin-bottom:15px;">❓ Questions fréquentes</h2>

    <?php
    $faqs = [
        ['Comment utiliser la carte ?', 'Clique sur un pays coloré pour voir sa capitale, son continent et son climat !'],
        ['Comment fonctionne le quiz ?', 'Choisis la bonne réponse parmi 3 propositions pour gagner 10 points !'],
        ['Comment avoir des badges ?', '100 points = Explorer, 500 points = Aventurier, 1000 points = Expert !'],
        ["J'ai oublié mon mot de passe ?", "Clique sur 'Mot de passe oublié' sur la page de connexion !"],
        ['Comment voir mes progrès ?', 'Clique sur Profil dans le menu pour voir ton score et tes badges !'],
    ];
    foreach($faqs as $i => $faq): ?>
        <div class="carte-style" style="cursor:pointer; border-left:4px solid #1B6CA8; margin-bottom:10px;" onclick="afficher('faq<?= $i ?>')">
            <p style="font-weight:700;"><?= $faq[0] ?></p>
            <p id="faq<?= $i ?>" style="display:none; color:#666; margin-top:8px;"><?= $faq[1] ?></p>
        </div>
    <?php endforeach; ?>

    <h2 style="color:#1B6CA8; margin:25px 0 15px;">📩 Contacter un professeur</h2>
    <?php if($succes): ?><p class="succes"><?= $succes ?></p><?php endif; ?>
    <?php if($erreur): ?><p class="erreur"><?= $erreur ?></p><?php endif; ?>
    <div class="carte-style">
        <form method="POST">
            <textarea name="question" placeholder="Écris ta question ici..." style="width:100%; padding:10px; height:120px; border:2px solid #e0e0e0; border-radius:10px; font-family:'Nunito',sans-serif; font-size:14px; resize:none; outline:none;" required></textarea>
            <button type="submit" name="envoyer" class="bouton" style="width:100%; margin-top:10px;">📨 Envoyer</button>
        </form>
    </div>
</div>

<script>
function afficher(id){
    var e = document.getElementById(id);
    e.style.display = e.style.display=='none' ? 'block' : 'none';
}
</script>

<?php require_once 'includes/footer.php'; ?>