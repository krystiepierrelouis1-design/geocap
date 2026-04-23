<?php
require_once 'includes/db.php';

// Récupère le nom du pays
$nom = $_GET['nom'] ?? '';

if(empty($nom)){
    echo json_encode(['erreur' => 'Pas de nom']);
    exit;
}

// Traduction noms anglais → français pour les pays courants
$traductions = [
    'France' => 'France',
    'Germany' => 'Allemagne',
    'Italy' => 'Italie',
    'Spain' => 'Espagne',
    'Portugal' => 'Portugal',
    'United Kingdom' => 'Royaume-Uni',
    'Belgium' => 'Belgique',
    'Netherlands' => 'Pays-Bas',
    'Switzerland' => 'Suisse',
    'Greece' => 'Grèce',
    'Morocco' => 'Maroc',
    'Senegal' => 'Sénégal',
    'Nigeria' => 'Nigeria',
    'Egypt' => 'Égypte',
    'South Africa' => 'Afrique du Sud',
    'Ethiopia' => 'Éthiopie',
    'Kenya' => 'Kenya',
    'Japan' => 'Japon',
    'China' => 'Chine',
    'India' => 'Inde',
    'South Korea' => 'Corée du Sud',
    'Thailand' => 'Thaïlande',
    'Vietnam' => 'Vietnam',
    'Brazil' => 'Brésil',
    'Argentina' => 'Argentine',
    'Mexico' => 'Mexique',
    'Canada' => 'Canada',
    'United States of America' => 'États-Unis',
    'Australia' => 'Australie',
    "Côte d'Ivoire" => 'Côte d Ivoire',
];

// Traduit le nom
$nom_fr = $traductions[$nom] ?? $nom;

// Cherche dans la BDD
$stmt = $pdo->prepare('SELECT * FROM pays WHERE nom_pays = ?');
$stmt->execute([$nom_fr]);
$pays = $stmt->fetch(PDO::FETCH_ASSOC);

if($pays){
    header('Content-Type: application/json');
    echo json_encode($pays);
} else {
    echo json_encode(['erreur' => 'Pays non trouvé : ' . $nom_fr]);
}
?>