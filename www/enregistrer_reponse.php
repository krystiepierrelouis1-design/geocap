<?php
session_start();
require_once 'includes/db.php';
if(!isset($_SESSION['user_id'])){ exit; }
$correct = (int)$_GET['correct'];
$user_id = $_SESSION['user_id'];
$pdo->prepare("INSERT INTO reponses (utilisateur_id, quiz_id, est_correcte) VALUES (?,0,?)")->execute([$user_id, $correct]);
if($correct == 1){
    $stmt = $pdo->prepare("SELECT id FROM scores WHERE utilisateur_id=?");
    $stmt->execute([$user_id]);
    if($stmt->fetch()){
        $pdo->prepare("UPDATE scores SET score = score + 10 WHERE utilisateur_id=?")->execute([$user_id]);
    } else {
        $pdo->prepare("INSERT INTO scores (utilisateur_id, score, continent, badges) VALUES (?,10,'','')")->execute([$user_id]);
    }
}
?>