<?php
session_start();
require_once 'includes/db.php';
if(!isset($_SESSION['user_id'])){
    header('Location:index.php'); exit;
}

// Récupère une question aléatoire
$stmt = $pdo->query('SELECT * FROM quiz ORDER BY RAND() LIMIT 1');
$question = $stmt->fetch();

// Mélange les réponses
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
    <p style="color:#666; margin-bottom:25px;">
        Bonne réponse = +10 points ! 🌟
    </p>

    <div class="quiz-carte">

        <!-- Question -->
        <p><?= htmlspecialchars($question['question']) ?></p>

        <!-- 3 boutons réponses -->
        <div class="options">
            <?php foreach($options as $option): ?>
                <button
                    class="btn-option"
                    data-bonne="<?= htmlspecialchars($question['bonne_reponse']) ?>"
                    onclick="verifierReponse(this)">
                    <?= htmlspecialchars($option) ?>
                </button>
            <?php endforeach; ?>
        </div>

    </div>

    <!-- Résultat caché au départ -->
    <div id="resultat" style="display:none; margin-top:25px;">
        <p id="message-resultat"></p>
        <a href="quiz.php" class="btn-principal" style="margin-top:15px; display:inline-block;">
            Question suivante →
        </a>
    </div>

</main>

<script>
function verifierReponse(bouton) {

    var choix = bouton.textContent.trim();
    var bonne = bouton.dataset.bonne;

    // Désactive tous les boutons
    document.querySelectorAll(".btn-option").forEach(function(b) {
        b.disabled = true;
        if(b.textContent.trim() === bonne) {
            b.classList.add("btn-vert");
        }
    });

    var msg = document.getElementById("message-resultat");

    if(choix === bonne) {
        bouton.classList.add("btn-vert");
        msg.innerHTML = "🎉 Bravo ! Bonne réponse ! +10 points !";
        msg.style.color = "#10B981";
        fetch("enregistrer_reponse.php?correct=1");
    } else {
        bouton.classList.add("btn-rouge");
        msg.innerHTML = "😢 Raté ! La bonne réponse était : <strong>" + bonne + "</strong>";
        msg.style.color = "#EF4444";
        fetch("enregistrer_reponse.php?correct=0");
    }

    document.getElementById("resultat").style.display = "block";
}
</script>

<?php require_once 'includes/footer.php'; ?>