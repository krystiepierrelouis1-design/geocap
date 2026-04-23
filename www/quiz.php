<?php
session_start();
// session utilisateur

require_once 'includes/db.php';
// connexion base

if(!isset($_SESSION['user_id'])){
// sécurité accès

header("Location:index.php");
// redirection login

exit;
// arrêt script

}

$stmt = $pdo->query("SELECT * FROM quiz ORDER BY RAND() LIMIT 1");
// récupère question aléatoire

$question = $stmt->fetch();
// stocke question

$options = [
// tableau réponses

$question['bonne_reponse'],
// bonne réponse

$question['fausse_reponse1'],
// mauvaise réponse 1

$question['fausse_reponse2']
// mauvaise réponse 2

];

shuffle($options);
// mélange les réponses
?>

<?php require_once 'includes/header.php'; ?>

<h1>Quiz GEOCAP</h1>
// titre

<p><?= htmlspecialchars($question['question']) ?></p>
// affiche question

<div class="options">
// conteneur réponses

<?php foreach($options as $option): ?>
// boucle sur réponses

<button
class="btn-option"
data-bonne="<?= $question['bonne_reponse'] ?>"
onclick="verifierReponse(this)">
// bouton réponse + stockage bonne réponse

<?= htmlspecialchars($option) ?>
// affiche texte réponse

</button>
// fin bouton

<?php endforeach; ?>
// fin boucle

</div>
// fin options

<div id="resultat" style="display:none;">
// bloc résultat caché

<p id="message-resultat"></p>
// message résultat

<a href="quiz.php">Question suivante</a>
// bouton suivant

</div>
// fin bloc

<script>
// JavaScript

function verifierReponse(bouton){
// fonction clic

let choix = bouton.textContent.trim();
// réponse choisie

let bonne = bouton.dataset.bonne;
// bonne réponse

document.querySelectorAll(".btn-option").forEach(b=>{
// désactive tous boutons

b.disabled = true;
// bloque clic

if(b.textContent.trim() === bonne){
// si bonne réponse

b.className = "btn-vert";
// color vert

}

});

let msg = document.getElementById("message-resultat");
// récup message

if(choix === bonne){
// si correct

bouton.className = "btn-vert";
// vert

msg.textContent = "Bravo 🎉";
// texte

msg.style.color = "green";
// couleur

} else {
// sinon

bouton.className = "btn-rouge";
// rouge

msg.textContent = "Raté 😢 Bonne réponse : " + bonne;
// message erreur

msg.style.color = "red";
// rouge

}

document.getElementById("resultat").style.display = "block";
// affiche résultat

}

</script>

<?php require_once 'includes/footer.php'; ?>
