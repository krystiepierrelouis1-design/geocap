<?php
session_start();
require_once 'includes/db.php';
if(!isset($_SESSION['user_id'])){ header("Location: index.php"); exit; }
$stmt = $pdo->query("SELECT * FROM quiz ORDER BY RAND() LIMIT 1");
$question = $stmt->fetch();
$options = array($question['bonne_reponse'], $question['fausse_reponse1'], $question['fausse_reponse2']);
shuffle($options);
?>
<?php require_once 'includes/header.php'; ?>

<div style="max-width:600px; margin:40px auto; padding:20px; text-align:center;">
    <h1 style="color:#1B6CA8; margin-bottom:5px;">Quiz GEOCAP 🧠</h1>
    <p style="color:#888; margin-bottom:25px;">Bonne réponse = +10 points !</p>

    <div class="carte-style">
        <p style="font-size:19px; font-weight:800; margin-bottom:25px; color:#333;"><?= htmlspecialchars($question['question']) ?></p>
        <div style="display:flex; flex-direction:column; gap:12px;">
            <?php foreach($options as $option): ?>
                <button class="btn-option"
                    data-bonne="<?= htmlspecialchars($question['bonne_reponse']) ?>"
                    onclick="verifier(this)"
                    style="padding:14px; border:2px solid #e0e0e0; border-radius:12px; font-size:16px; cursor:pointer; background:white; font-family:'Nunito',sans-serif; font-weight:600; transition:all 0.3s;">
                    <?= htmlspecialchars($option) ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="resultat" style="display:none; margin-top:20px; padding:25px; border-radius:15px;">
        <p id="emoji-res" style="font-size:55px; margin-bottom:10px;"></p>
        <p id="message" style="font-size:20px; font-weight:800; margin-bottom:8px;"></p>
        <p id="sous-message" style="font-size:14px; color:#666; margin-bottom:20px;"></p>
        <a href="quiz.php" style="background:linear-gradient(135deg,#1B6CA8,#6C3AB5); color:white; padding:12px 25px; border-radius:25px; text-decoration:none; font-weight:700;">Question suivante ➡️</a>
    </div>
</div>

<script>
var messagesBien = ["Génial ! Tu es trop fort !","Bravo ! Continue comme ça !","Incroyable ! Tu maîtrises !","Super ! Tu es un génie !"];
var messagesPasBien = ["Pas grave ! Tu apprendras !","Oups ! Réessaie, tu vas y arriver !","Ce n'est pas grave ! La prochaine fois !","Courage ! Tu vas t'en souvenir !"];
var emojiBien = ["🎉","🌟","🏆","🎊","⭐"];
var emojiPasBien = ["😊","💪","🌈","🤗","👍"];

function verifier(bouton){
    var choix = bouton.textContent.trim();
    var bonne = bouton.dataset.bonne;
    document.querySelectorAll('.btn-option').forEach(function(b){
        b.disabled = true;
        if(b.textContent.trim() == bonne){ b.style.background='#10B981'; b.style.color='white'; b.style.borderColor='#10B981'; }
    });
    var i = Math.floor(Math.random() * 4);
    if(choix == bonne){
        bouton.style.background='#10B981'; bouton.style.color='white';
        document.getElementById('resultat').style.background='#EAFAF1';
        document.getElementById('resultat').style.border='2px solid #10B981';
        document.getElementById('emoji-res').textContent = emojiBien[i];
        document.getElementById('message').textContent = messagesBien[i];
        document.getElementById('message').style.color = '#10B981';
        document.getElementById('sous-message').textContent = '+10 points dans ton sac !';
        fetch('enregistrer_reponse.php?correct=1');
    } else {
        bouton.style.background='#EF4444'; bouton.style.color='white';
        document.getElementById('resultat').style.background='#FEE2E2';
        document.getElementById('resultat').style.border='2px solid #EF4444';
        document.getElementById('emoji-res').textContent = emojiPasBien[i];
        document.getElementById('message').textContent = messagesPasBien[i];
        document.getElementById('message').style.color = '#EF4444';
        document.getElementById('sous-message').textContent = 'La bonne réponse était : ' + bonne;
        fetch('enregistrer_reponse.php?correct=0');
    }
    document.getElementById('resultat').style.display = 'block';
}
</script>

<?php require_once 'includes/footer.php'; ?>