<?php
require_once 'includes/db.php';
// connecte la base de données (pour récupérer les infos des pays)

$lat = $_GET['lat'] ?? 0;
// récupère la latitude envoyée par l'utilisateur (ou 0 si rien)

$lng = $_GET['lng'] ?? 0;
// récupère la longitude envoyée (ou 0 si rien)


$pays_coords = [
    // liste des pays avec leurs zones géographiques

    ['nom' => 'France', 'lat_min' => 42, 'lat_max' => 51, 'lng_min' => -5, 'lng_max' => 8],
    // France = zone de coordonnées

    ['nom' => 'Allemagne', 'lat_min' => 47, 'lat_max' => 55, 'lng_min' => 6, 'lng_max' => 15],
    // Allemagne

    ['nom' => 'Italie', 'lat_min' => 36, 'lat_max' => 47, 'lng_min' => 6, 'lng_max' => 19],
    // Italie

    ['nom' => 'Espagne', 'lat_min' => 36, 'lat_max' => 44, 'lng_min' => -9, 'lng_max' => 4],
    // Espagne

    ['nom' => 'Portugal', 'lat_min' => 37, 'lat_max' => 42, 'lng_min' => -9, 'lng_max' => -6],
    // Portugal

    ['nom' => 'Royaume-Uni', 'lat_min' => 50, 'lat_max' => 59, 'lng_min' => -8, 'lng_max' => 2],
    // Royaume-Uni

    ['nom' => 'Maroc', 'lat_min' => 27, 'lat_max' => 36, 'lng_min' => -13, 'lng_max' => -1],
    // Maroc

    ['nom' => 'Sénégal', 'lat_min' => 12, 'lat_max' => 16, 'lng_min' => -17, 'lng_max' => -11],
    // Sénégal

    ['nom' => 'Nigeria', 'lat_min' => 4, 'lat_max' => 14, 'lng_min' => 3, 'lng_max' => 15],
    // Nigeria

    ['nom' => 'Égypte', 'lat_min' => 22, 'lat_max' => 31, 'lng_min' => 25, 'lng_max' => 37],
    // Égypte

    ['nom' => 'Afrique du Sud', 'lat_min' => -35, 'lat_max' => -22, 'lng_min' => 16, 'lng_max' => 33],
    // Afrique du Sud

    ['nom' => 'Kenya', 'lat_min' => -5, 'lat_max' => 5, 'lng_min' => 34, 'lng_max' => 42],
    // Kenya

    ['nom' => 'Japon', 'lat_min' => 30, 'lat_max' => 45, 'lng_min' => 130, 'lng_max' => 145],
    // Japon

    ['nom' => 'Chine', 'lat_min' => 18, 'lat_max' => 53, 'lng_min' => 73, 'lng_max' => 135],
    // Chine

    ['nom' => 'Inde', 'lat_min' => 8, 'lat_max' => 37, 'lng_min' => 68, 'lng_max' => 97],
    // Inde

    ['nom' => 'Thaïlande', 'lat_min' => 5, 'lat_max' => 21, 'lng_min' => 97, 'lng_max' => 106],
    // Thaïlande

    ['nom' => 'Brésil', 'lat_min' => -33, 'lat_max' => 5, 'lng_min' => -74, 'lng_max' => -34],
    // Brésil

    ['nom' => 'Argentine', 'lat_min' => -55, 'lat_max' => -22, 'lng_min' => -73, 'lng_max' => -53],
    // Argentine

    ['nom' => 'Mexique', 'lat_min' => 14, 'lat_max' => 33, 'lng_min' => -117, 'lng_max' => -87],
    // Mexique

    ['nom' => 'Canada', 'lat_min' => 42, 'lat_max' => 84, 'lng_min' => -141, 'lng_max' => -52],
    // Canada

    ['nom' => 'États-Unis', 'lat_min' => 24, 'lat_max' => 49, 'lng_min' => -125, 'lng_max' => -66],
    // USA

    ['nom' => 'Australie', 'lat_min' => -44, 'lat_max' => -10, 'lng_min' => 113, 'lng_max' => 154],
    // Australie
];

$pays_trouve = null;
// variable vide pour stocker le pays trouvé


foreach($pays_coords as $p){
    // on vérifie chaque pays de la liste

    if(
        $lat >= $p['lat_min'] && $lat <= $p['lat_max'] &&
        $lng >= $p['lng_min'] && $lng <= $p['lng_max']
    ){
        // si les coordonnées sont dans la zone du pays

        $pays_trouve = $p['nom'];
        // on stocke le nom du pays trouvé

        break;
        // on arrête la boucle
    }
}


if($pays_trouve){
    // si un pays a été trouvé

    $stmt = $pdo->prepare('SELECT * FROM pays WHERE nom_pays = ?');
    // cherche les infos du pays dans la base

    $stmt->execute([$pays_trouve]);
    // envoie le nom du pays

    $pays = $stmt->fetch(PDO::FETCH_ASSOC);
    // récupère les données du pays

    if($pays){
        // si le pays existe dans la base

        header('Content-Type: application/json');
        // dit que la réponse est du JSON

        echo json_encode($pays);
        // envoie les infos du pays
    } else {
        // si pas trouvé dans la base

        echo json_encode(['erreur' => 'Pays non trouvé']);
        // message d'erreur
    }

} else {
    // si aucun pays détecté

    echo json_encode(['erreur' => 'Aucun pays détecté']);
    // message d'erreur
}
?>