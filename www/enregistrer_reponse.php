<?php
session_start();
require_once 'includes/db.php';
if(!isset($_SESSION['user_id'])){
    echo json_encode(['erreur' => 'Non connecté']); exit;
}
$correct = isset($_GET['correct']) ? (int)$_GET['correct'] : 0;
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare('INSERT INTO reponses (utilisateur_id, quiz_id, est_correcte) VALUES (?, 0, ?)');
$stmt->execute([$user_id, $correct]);
if($correct == 1){
    $stmt2 = $pdo->prepare('SELECT id FROM scores WHERE utilisateur_id = ?');
    $stmt2->execute([$user_id]);
    if($stmt2->fetch()){
        $stmt3 = $pdo->prepare('UPDATE scores SET score = score + 10 WHERE utilisateur_id = ?');
        $stmt3->execute([$user_id]);
    } else {
        $stmt3 = $pdo->prepare('INSERT INTO scores (utilisateur_id, score, continent, badges) VALUES (?, 10, "", "")');
        $stmt3->execute([$user_id]);
    }
}
echo json_encode(['ok' => true]);
?>