<?php
require_once 'includes/db.php';
$nom = $_GET['nom'] ?? '';
$traductions = array(
    'France'=>'France','Germany'=>'Allemagne','Italy'=>'Italie','Spain'=>'Espagne',
    'Portugal'=>'Portugal','United Kingdom'=>'Royaume-Uni','Belgium'=>'Belgique',
    'Netherlands'=>'Pays-Bas','Switzerland'=>'Suisse','Morocco'=>'Maroc',
    'Senegal'=>'Senegal','Nigeria'=>'Nigeria','Egypt'=>'Egypte',
    'South Africa'=>'Afrique du Sud','Kenya'=>'Kenya','Japan'=>'Japon',
    'China'=>'Chine','India'=>'Inde','South Korea'=>'Coree du Sud',
    'Thailand'=>'Thailande','Vietnam'=>'Vietnam','Brazil'=>'Bresil',
    'Argentina'=>'Argentine','Mexico'=>'Mexique','Canada'=>'Canada',
    'United States of America'=>'Etats-Unis','Australia'=>'Australie',
    'Russia'=>'Russie','Turkey'=>'Turquie','Indonesia'=>'Indonesie'
);
$nom_fr = isset($traductions[$nom]) ? $traductions[$nom] : $nom;
$stmt = $pdo->prepare("SELECT * FROM pays WHERE nom_pays=?");
$stmt->execute([$nom_fr]);
$pays = $stmt->fetch(PDO::FETCH_ASSOC);
if($pays){ header('Content-Type: application/json'); echo json_encode($pays); }
else { echo json_encode(array('erreur'=>'Pays non trouve')); }
?>