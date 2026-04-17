<?php
require 'connexion.php';

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("DELETE FROM Livres2 WHERE id = ?");
$stmt->execute([$id]);

header("Location: liste.php");
exit;
?>