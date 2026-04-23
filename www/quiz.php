<?php
session_start();
require_once 'includes/db.php';
if(!isset($_SESSION['user_id'])){
    header('Location:index.php'); exit;
}
$stmt = $pdo->query('SELECT * FROM quiz ORDER BY RAND() LIMIT 1');
$question = $stmt->fetch();
$options = [
    $question['bonne_reponse'],
    $question['fausse_reponse1'],
    $question['fausse_reponse2']
];
shuffle($options);
?>
<?php require_once 'includes/header.php'; ?>
<main class="page-quiz">
    <h1>🧠 Quiz GEOCAP</h1>
    <div class="quiz-carte">
        <p><?= htmlspecialchars($question['question']) ?></p>
        <div class="options">
            <?php foreach($options as $option): ?>
                <button class="btn-option"
                    data-bonne="<?= htmlspecialchars($question['bonne_reponse']) ?>"
                    onclick="verifierReponse(this)">
                    <?= htmlspecialchars($option) ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
    <div id="resultat" style="display:none; margin-top:20px;">
        <p id="message-resultat"></p>
        <a href="quiz.php" style="background:#1B6CA8; color:white; padding:10px 20px; border-radius:8px; text-decoration:none;">
            Question suivante →
        </a>
    </div>
</main>
<script>
function verifierReponse(bouton){
    var choix = bouton.textContent.trim();
    var bonne = bouton.dataset.bonne;
    document.querySelectorAll(".btn-option").forEach(function(b){
        b.disabled = true;
        if(b.textContent.trim() === bonne){
            b.style.background = "#10B981";
            b.style.color = "white";
        }
    });
    var msg = document.getElementById("message-resultat");
    if(choix === bonne){
        bouton.style.background = "#10B981";
        bouton.style.color = "white";
        msg.textContent = "Bravo ! 🎉 +10 points !";
        msg.style.color = "green";
        fetch("enregistrer_reponse.php?correct=1");
    } else {
        bouton.style.background = "#EF4444";
        bouton.style.color = "white";
        msg.textContent = "Raté 😢 La bonne réponse était : " + bonne;
        msg.style.color = "red";
        fetch("enregistrer_reponse.php?correct=0");
    }
    document.getElementById("resultat").style.display = "block";
}
</script>
<?php require_once 'includes/footer.php'; ?>