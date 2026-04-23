<?php
session_start();
// démarre la session (savoir qui est connecté)

require_once 'includes/db.php';
// connecte la base de données


if(!isset($_SESSION['user_id'])){
    // si l'utilisateur n'est pas connecté

    echo json_encode(['erreur' => 'Non connecté']);
    // renvoie une erreur en format JSON (pour JavaScript)

    exit;
    // arrête le code
}


/* =======================
   RÉPONSE DU QUIZ
======================= */

$correct = isset($_GET['correct']) ? (int)$_GET['correct'] : 0;
// récupère si la réponse est correcte (1) ou fausse (0)

$user_id = $_SESSION['user_id'];
// récupère l'id de l'utilisateur connecté


$stmt = $pdo->prepare('INSERT INTO reponses (utilisateur_id, quiz_id, est_correcte) VALUES (?, 0, ?)');
/* enregistre la réponse dans la base :
   - utilisateur
   - quiz
   - si c'est correct ou pas */

$stmt->execute([$user_id, $correct]);
// envoie les données


/* =======================
   SI RÉPONSE CORRECTE
======================= */

if($correct == 1){
    // si la réponse est bonne

    $stmt2 = $pdo->prepare('SELECT id FROM scores WHERE utilisateur_id = ?');
    // cherche si l'utilisateur a déjà un score

    $stmt2->execute([$user_id]);
    // exécute la requête

    $score_existe = $stmt2->fetch();
    // vérifie si un score existe


    if($score_existe){
        // si le score existe déjà

        $stmt3 = $pdo->prepare('UPDATE scores SET points = points + 10 WHERE utilisateur_id = ?');
        // ajoute 10 points

        $stmt3->execute([$user_id]);
        // met à jour la base

    } else {
        // si aucun score n'existe

        $stmt3 = $pdo->prepare('INSERT INTO scores (utilisateur_id, points, continent, badges) VALUES (?, 10, "", "")');
        // crée un score avec 10 points

        $stmt3->execute([$user_id]);
        // enregistre
    }
}


/* =======================
   RÉPONSE AU FRONT (JS)
======================= */

echo json_encode(['ok' => true]);
// dit au JavaScript : "tout s'est bien passé"
?>