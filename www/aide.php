<?php
session_start();
require_once 'includes/db.php';
if(!isset($_SESSION['user_id'])){
    header('Location:index.php'); exit;
}

$succes = "";
$erreur = "";

// Si formulaire envoyé
if(isset($_POST['envoyer'])){
    $question = htmlspecialchars($_POST['question']);
    
    if(empty($question)){
        $erreur = "Écris ta question avant d'envoyer ! 😊";
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO messages (expediteur_id, contenu, date_envoi, lu) 
             VALUES (?, ?, NOW(), 0)'
        );
        $stmt->execute([$_SESSION['user_id'], $question]);
        $succes = "Ta question a été envoyée ! Le professeur te répond bientôt 😊";
    }
}
?>
<?php require_once 'includes/header.php'; ?>

<main class="page-aide">

    <h1>💬 Aide & Contact</h1>

    <!-- FAQ -->
    <h2 style="color:#1B6CA8; margin-bottom:15px;">❓ Questions fréquentes</h2>

    <div class="faq-item">
        <h3>🗺️ Comment utiliser la carte ?</h3>
        <p>Clique sur un pays sur la carte pour voir sa capitale, son continent et son climat. Tu peux ensuite jouer au quiz de ce pays !</p>
    </div>

    <div class="faq-item">
        <h3>🧠 Comment jouer au quiz ?</h3>
        <p>Clique sur "Quiz" dans le menu, choisis une réponse parmi les 3 propositions. Si tu as bon tu gagnes 10 points !</p>
    </div>

    <div class="faq-item">
        <h3>🏆 Comment gagner des badges ?</h3>
        <p>Accumule des points en répondant aux questions : 100 points = badge Explorer, 500 points = Aventurier, 1000 points = Expert !</p>
    </div>

    <div class="faq-item">
        <h3>🔑 J'ai oublié mon mot de passe, que faire ?</h3>
        <p>Contacte ton professeur via le formulaire ci-dessous, il t'aidera à récupérer ton compte !</p>
    </div>

    <!-- Formulaire contact -->
    <h2 style="color:#1B6CA8; margin:30px 0 15px;">📩 Contacter un professeur</h2>

    <?php if($succes): ?>
        <p class="message-succes"><?= $succes ?></p>
    <?php endif; ?>

    <?php if($erreur): ?>
        <p class="message-erreur"><?= $erreur ?></p>
    <?php endif; ?>

    <div class="formulaire" style="margin:0;">
        <form method="POST">
            <textarea 
                name="question" 
                placeholder="Écris ta question ici... 📝" 
                style="height:120px;" 
                required>
            </textarea>
            <button name="envoyer" type="submit">
                📨 Envoyer ma question
            </button>
        </form>
    </div>

</main>

<?php require_once 'includes/footer.php'; ?>