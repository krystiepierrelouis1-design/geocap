<?php
session_start();
// démarre la session (savoir qui est connecté)

require_once '../includes/db.php';
// connecte la base de données


if(!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'professeur')){
    // vérifie :
    // - si pas connecté
    // OU
    // - si ce n'est pas admin ou professeur

    header('Location: ../accueil.php');
    // renvoie à l'accueil (interdit d'accès)

    exit;
    // arrête le code
}


/* =======================
   UTILISATEURS
======================= */

$stmt = $pdo->query('SELECT * FROM utilisateurs');
// récupère tous les utilisateurs de la base

$users = $stmt->fetchAll();
// met tous les utilisateurs dans une liste
?>

<?php require_once '../includes/header.php'; ?>
// affiche le haut du site

<main class="dashboard">

<h1>⚙️ Dashboard Admin</h1>
// titre de la page admin

<h2>👥 Utilisateurs</h2>
// sous-titre

<?php foreach($users as $u): ?>
    // boucle : on affiche chaque utilisateur un par un

    <p><?= htmlspecialchars($u['pseudo']) ?> — <?= $u['role'] ?></p>
    // affiche :
    // pseudo + rôle (admin, professeur, enfant...)

<?php endforeach; ?>
// fin de la boucle

</main>

<?php require_once '../includes/footer.php'; ?>
// affiche le bas du site