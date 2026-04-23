<?php
require_once 'includes/db.php';
$lat = $_GET['lat'] ?? 0;
$lng = $_GET['lng'] ?? 0;
$pays_coords = [
    ['nom' => 'France', 'lat_min' => 42, 'lat_max' => 51, 'lng_min' => -5, 'lng_max' => 8],
    ['nom' => 'Allemagne', 'lat_min' => 47, 'lat_max' => 55, 'lng_min' => 6, 'lng_max' => 15],
    ['nom' => 'Italie', 'lat_min' => 36, 'lat_max' => 47, 'lng_min' => 6, 'lng_max' => 19],
    ['nom' => 'Espagne', 'lat_min' => 36, 'lat_max' => 44, 'lng_min' => -9, 'lng_max' => 4],
    ['nom' => 'Portugal', 'lat_min' => 37, 'lat_max' => 42, 'lng_min' => -9, 'lng_max' => -6],
    ['nom' => 'Royaume-Uni', 'lat_min' => 50, 'lat_max' => 59, 'lng_min' => -8, 'lng_max' => 2],
    ['nom' => 'Maroc', 'lat_min' => 27, 'lat_max' => 36, 'lng_min' => -13, 'lng_max' => -1],
    ['nom' => 'Sénégal', 'lat_min' => 12, 'lat_max' => 16, 'lng_min' => -17, 'lng_max' => -11],
    ['nom' => 'Nigeria', 'lat_min' => 4, 'lat_max' => 14, 'lng_min' => 3, 'lng_max' => 15],
    ['nom' => 'Égypte', 'lat_min' => 22, 'lat_max' => 31, 'lng_min' => 25, 'lng_max' => 37],
    ['nom' => 'Afrique du Sud', 'lat_min' => -35, 'lat_max' => -22, 'lng_min' => 16, 'lng_max' => 33],
    ['nom' => 'Kenya', 'lat_min' => -5, 'lat_max' => 5, 'lng_min' => 34, 'lng_max' => 42],
    ['nom' => 'Japon', 'lat_min' => 30, 'lat_max' => 45, 'lng_min' => 130, 'lng_max' => 145],
    ['nom' => 'Chine', 'lat_min' => 18, 'lat_max' => 53, 'lng_min' => 73, 'lng_max' => 135],
    ['nom' => 'Inde', 'lat_min' => 8, 'lat_max' => 37, 'lng_min' => 68, 'lng_max' => 97],
    ['nom' => 'Thaïlande', 'lat_min' => 5, 'lat_max' => 21, 'lng_min' => 97, 'lng_max' => 106],
    ['nom' => 'Brésil', 'lat_min' => -33, 'lat_max' => 5, 'lng_min' => -74, 'lng_max' => -34],
    ['nom' => 'Argentine', 'lat_min' => -55, 'lat_max' => -22, 'lng_min' => -73, 'lng_max' => -53],
    ['nom' => 'Mexique', 'lat_min' => 14, 'lat_max' => 33, 'lng_min' => -117, 'lng_max' => -87],
    ['nom' => 'Canada', 'lat_min' => 42, 'lat_max' => 84, 'lng_min' => -141, 'lng_max' => -52],
    ['nom' => 'États-Unis', 'lat_min' => 24, 'lat_max' => 49, 'lng_min' => -125, 'lng_max' => -66],
    ['nom' => 'Australie', 'lat_min' => -44, 'lat_max' => -10, 'lng_min' => 113, 'lng_max' => 154],
];
$pays_trouve = null;
foreach($pays_coords as $p){
    if($lat >= $p['lat_min'] && $lat <= $p['lat_max'] && $lng >= $p['lng_min'] && $lng <= $p['lng_max']){
        $pays_trouve = $p['nom'];
        break;
    }
}
if($pays_trouve){
    $stmt = $pdo->prepare('SELECT * FROM pays WHERE nom_pays = ?');
    $stmt->execute([$pays_trouve]);
    $pays = $stmt->fetch(PDO::FETCH_ASSOC);
    if($pays){
        header('Content-Type: application/json');
        echo json_encode($pays);
    } else {
        echo json_encode(['erreur' => 'Pays non trouvé']);
    }
} else {
    echo json_encode(['erreur' => 'Aucun pays détecté']);
}
?>