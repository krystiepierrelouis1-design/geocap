<?php
session_start();
// démarre la session (permet de savoir qui est connecté)

require_once 'includes/db.php';
// connecte la base de données

if(!isset($_SESSION['user_id'])){
    // si l'utilisateur n'est pas connecté

    header('Location:index.php');
    // redirige vers la page de connexion

    exit;
    // arrête le code
}

/* =======================
   ENVOI DU MESSAGE
======================= */

if(isset($_POST['envoyer'])){
    // vérifie si le formulaire a été envoyé

    $stmt = $pdo->prepare('INSERT INTO messages (expediteur_id, contenu, date_envoi, lu) VALUES (?, ?, NOW(), 0)');
    // prépare l'enregistrement du message dans la base

    $stmt->execute([
        $_SESSION['user_id'],
        htmlspecialchars($_POST['question'])
    ]);
    // envoie :
    // - l'id de l'utilisateur connecté
    // - la question (sécurisée)

    $succes = "Message envoyé !";
    // message de confirmation
}
?>

<?php require_once 'includes/header.php'; ?>
// affiche le haut du site

<h1>💬 Aide</h1>
// titre de la page

<?php if(isset($succes)): ?>
    // si le message a bien été envoyé

    <p style="color:green"><?= $succes ?></p>
    // affiche message vert
<?php endif; ?>

<form method="POST">
    // début du formulaire

    <input name="nom" placeholder="Ton nom">
    // champ pour le nom (pas utilisé dans PHP ici)

    <textarea name="question" placeholder="Ta question"></textarea>
    // champ pour écrire la question

    <button name="envoyer">Envoyer</button>
    // bouton pour envoyer le message

</form>

<?php require_once 'includes/footer.php'; ?>
// affiche le bas du site