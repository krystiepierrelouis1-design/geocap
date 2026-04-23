<?php
session_start();
require_once 'includes/db.php';
if(!isset($_SESSION['user_id'])){
    header('Location:index.php'); exit;
}
$succes = "";
$erreur = "";
if(isset($_POST['envoyer'])){
    $question = htmlspecialchars($_POST['question']);
    if(empty($question)){
        $erreur = "Écris ta question avant d'envoyer ! 😊";
    } else {
        $stmt = $pdo->prepare('INSERT INTO messages (expediteur_id, contenu, date_envoi, lu) VALUES (?, ?, NOW(), 0)');
        $stmt->execute([$_SESSION['user_id'], $question]);
        $succes = "✅ Ta question a été envoyée ! Le professeur te répond bientôt 😊";
    }
}
?>
<?php require_once 'includes/header.php'; ?>

<style>
.page-aide {
    max-width: 700px;
    margin: 40px auto;
    padding: 20px;
}
.aide-titre {
    text-align: center;
    margin-bottom: 35px;
}
.aide-titre h1 {
    color: #1B6CA8;
    font-size: 32px;
    font-weight: 900;
}
.aide-titre p {
    color: #888;
    margin-top: 8px;
}
.faq-card {
    background: white;
    border-radius: 16px;
    margin-bottom: 15px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.06);
    overflow: hidden;
    cursor: pointer;
}
.faq-question {
    padding: 20px 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-weight: 700;
    font-size: 16px;
    color: #2D2D2D;
    border-left: 5px solid #1B6CA8;
}
.faq-question:hover {
    background: #F8F9FA;
}
.faq-reponse {
    padding: 0 25px;
    max-height: 0;
    overflow: hidden;
    transition: all 0.3s;
    color: #666;
    line-height: 1.7;
}
.faq-reponse.ouverte {
    padding: 15px 25px 20px;
    max-height: 200px;
}
.contact-card {
    background: white;
    border-radius: 20px;
    padding: 35px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.08);
    margin-top: 30px;
    border-top: 5px solid #10B981;
}
.contact-card h2 {
    color: #10B981;
    font-size: 22px;
    margin-bottom: 20px;
    text-align: center;
}
.contact-card textarea {
    width: 100%;
    padding: 15px;
    border: 2px solid #E0E0E0;
    border-radius: 12px;
    font-size: 15px;
    font-family: 'Nunito', sans-serif;
    height: 130px;
    outline: none;
    resize: none;
    color: #2D2D2D;
    transition: border 0.3s;
}
.contact-card textarea:focus {
    border-color: #10B981;
}
.btn-contact {
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
    margin-top: 15px;
    transition: all 0.3s;
}
.btn-contact:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(16,185,129,0.4);
}
</style>

<main class="page-aide">

    <div class="aide-titre">
        <h1>💬 Aide & Contact</h1>
        <p>Tu as une question ? On est là pour t'aider !</p>
    </div>

    <!-- FAQ -->
    <h2 style="color:#1B6CA8; margin-bottom:15px; font-size:20px;">
        ❓ Questions fréquentes
    </h2>

    <div class="faq-card" onclick="toggleFAQ(this)">
        <div class="faq-question">
            🗺️ Comment utiliser la carte interactive ?
            <span>▼</span>
        </div>
        <div class="faq-reponse">
            Clique sur n'importe quel pays sur la carte pour voir sa capitale, son continent, sa superficie et son climat. Tu peux ensuite cliquer sur "Jouer avec ce pays" pour lancer un quiz sur ce pays !
        </div>
    </div>

    <div class="faq-card" onclick="toggleFAQ(this)">
        <div class="faq-question">
            🧠 Comment fonctionne le quiz ?
            <span>▼</span>
        </div>
        <div class="faq-reponse">
            Une question s'affiche avec 3 propositions de réponse. Clique sur la bonne réponse pour gagner 10 points ! Si tu te trompes, la bonne réponse s'affiche en vert pour que tu puisses apprendre.
        </div>
    </div>

    <div class="faq-card" onclick="toggleFAQ(this)">
        <div class="faq-question">
            🏆 Comment gagner des badges ?
            <span>▼</span>
        </div>
        <div class="faq-reponse">
            Accumule des points en répondant correctement aux questions du quiz ! 100 points = badge Explorer 🥉, 500 points = Aventurier 🥈, 1000 points = Géographe Expert 🥇 !
        </div>
    </div>

    <div class="faq-card" onclick="toggleFAQ(this)">
        <div class="faq-question">
            🔑 J'ai oublié mon mot de passe, que faire ?
            <span>▼</span>
        </div>
        <div class="faq-reponse">
            Pas de panique ! Contacte ton professeur via le formulaire ci-dessous en expliquant ta situation. Il t'aidera à récupérer ton compte rapidement !
        </div>
    </div>

    <div class="faq-card" onclick="toggleFAQ(this)">
        <div class="faq-question">
            📊 Comment voir mes progrès ?
            <span>▼</span>
        </div>
        <div class="faq-reponse">
            Clique sur "Mon Profil" dans le menu pour voir ton score total, tes badges débloqués, le nombre de questions répondues et ton taux de réussite !
        </div>
    </div>

    <!-- Formulaire contact -->
    <div class="contact-card">
        <h2>📩 Contacter un professeur</h2>

        <?php if($succes): ?>
            <p style="background:#D1FAE5; color:#10B981; padding:12px; border-radius:10px; margin-bottom:20px; font-weight:700; text-align:center;">
                <?= $succes ?>
            </p>
        <?php endif; ?>

        <?php if($erreur): ?>
            <p style="background:#FEE2E2; color:#E74C3C; padding:12px; border-radius:10px; margin-bottom:20px; font-weight:700; text-align:center;">
                <?= $erreur ?>
            </p>
        <?php endif; ?>

        <form method="POST">
            <textarea name="question" placeholder="✍️ Écris ta question ici... Le professeur te répondra bientôt !" required></textarea>
            <button type="submit" name="envoyer" class="btn-contact">
                📨 Envoyer ma question
            </button>
        </form>
    </div>

</main>

<script>
function toggleFAQ(card) {
    var reponse = card.querySelector('.faq-reponse');
    var fleche = card.querySelector('.faq-question span');
    reponse.classList.toggle('ouverte');
    fleche.textContent = reponse.classList.contains('ouverte') ? '▲' : '▼';
}
</script>

<?php require_once 'includes/footer.php'; ?>