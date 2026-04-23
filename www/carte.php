<?php
session_start();
require_once 'includes/db.php';
if(!isset($_SESSION['user_id'])){
    header('Location:index.php'); exit;
}
?>
<?php require_once 'includes/header.php'; ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
.page-carte { padding: 20px; max-width: 1100px; margin: auto; }
.page-carte h1 { color: #1B6CA8; text-align: center; font-size: 28px; margin-bottom: 5px; }
.page-carte p.sous-titre { text-align: center; color: #888; margin-bottom: 20px; }
#carte { height: 650px; width: 100%; border-radius: 20px; box-shadow: 0 5px 25px rgba(0,0,0,0.15); }
.infos-pays-box {
    background: white;
    border-radius: 20px;
    padding: 25px;
    margin-top: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
    text-align: center;
    display: none;
}
.infos-pays-box h2 { color: #1B6CA8; font-size: 24px; margin-bottom: 15px; }
.infos-pays-box .info-ligne {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #F0F0F0;
    text-align: left;
    font-size: 15px;
}
.infos-pays-box .info-ligne:last-child { border: none; }
.drapeau-img { height: 70px; border-radius: 8px; margin-bottom: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); }
.btn-jouer-pays {
    background: linear-gradient(135deg, #1B6CA8, #0D4A7A);
    color: white;
    padding: 14px 30px;
    border: none;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    font-family: 'Nunito', sans-serif;
    text-decoration: none;
    display: inline-block;
    margin-top: 15px;
    transition: all 0.3s;
}
.btn-jouer-pays:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(27,108,168,0.4); }
</style>

<main class="page-carte">
    <h1>🗺️ Explore le monde !</h1>
    <p class="sous-titre">Clique sur un pays pour découvrir sa capitale ! 🌍</p>

    <div id="carte"></div>

    <div class="infos-pays-box" id="infos-pays">
        <img id="drapeau" src="" alt="drapeau" class="drapeau-img" style="display:none;">
        <h2 id="nom-pays"></h2>
        <div class="info-ligne">
            <span>🏛️ Capitale</span>
            <strong id="capitale"></strong>
        </div>
        <div class="info-ligne">
            <span>🌍 Continent</span>
            <span id="continent"></span>
        </div>
        <div class="info-ligne">
            <span>📏 Superficie</span>
            <span id="superficie"></span>
        </div>
        <div class="info-ligne">
            <span>🌤️ Climat</span>
            <span id="climat"></span>
        </div>
        <a id="btn-jouer" href="#" class="btn-jouer-pays">
            🎮 Jouer avec ce pays !
        </a>
    </div>
</main>

<script>
// Création de la carte
var carte = L.map("carte", {
    center: [20, 0],
    zoom: 2,
    minZoom: 2
});

// Fond de carte en français (CartoDB)
L.tileLayer("https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png", {
    attribution: '© OpenStreetMap © CartoDB',
    subdomains: 'abcd',
    maxZoom: 19
}).addTo(carte);

// Couleur par défaut et au survol
var styleDefaut = {
    fillColor: '#1B6CA8',
    fillOpacity: 0.15,
    color: '#1B6CA8',
    weight: 1
};

var styleSurvol = {
    fillColor: '#1B6CA8',
    fillOpacity: 0.4,
    color: '#0D4A7A',
    weight: 2
};

var styleSelectionne = {
    fillColor: '#F59E0B',
    fillOpacity: 0.5,
    color: '#E67E22',
    weight: 2
};

var layerSelectionne = null;
var geoJsonLayer = null;

// Charge le GeoJSON des pays du monde
fetch('https://raw.githubusercontent.com/datasets/geo-countries/master/data/countries.geojson')
.then(function(r){ return r.json(); })
.then(function(data){
    geoJsonLayer = L.geoJson(data, {
        style: styleDefaut,
        onEachFeature: function(feature, layer){
            // Au survol
            layer.on('mouseover', function(){
                if(layer !== layerSelectionne){
                    layer.setStyle(styleSurvol);
                }
                layer.bindTooltip(
                    '<strong>' + feature.properties.ADMIN + '</strong>',
                    {permanent: false, direction: 'center'}
                ).openTooltip();
            });

            layer.on('mouseout', function(){
                if(layer !== layerSelectionne){
                    layer.setStyle(styleDefaut);
                }
            });

            // Au clic sur le pays
            layer.on('click', function(){
                // Remet l'ancien pays en style normal
                if(layerSelectionne && layerSelectionne !== layer){
                    layerSelectionne.setStyle(styleDefaut);
                }
                layer.setStyle(styleSelectionne);
                layerSelectionne = layer;

                var nomPays = feature.properties.ADMIN;

                // Cherche le pays dans notre BDD
                fetch('api_pays.php?nom=' + encodeURIComponent(nomPays))
                .then(function(r){ return r.json(); })
                .then(function(data){
                    var box = document.getElementById('infos-pays');
                    box.style.display = 'block';

                    if(data.nom_pays){
                        document.getElementById('nom-pays').textContent = data.nom_pays;
                        document.getElementById('capitale').textContent = data.capitale;
                        document.getElementById('continent').textContent = data.continent;
                        document.getElementById('superficie').textContent = data.superficie + ' km²';
                        document.getElementById('climat').textContent = data.climat;

                        if(data.drapeau_url){
                            var img = document.getElementById('drapeau');
                            img.src = data.drapeau_url;
                            img.style.display = 'block';
                        }

                        document.getElementById('btn-jouer').href = 'quiz.php?pays_id=' + data.id;
                    } else {
                        document.getElementById('nom-pays').textContent = nomPays;
                        document.getElementById('capitale').textContent = 'Non disponible';
                        document.getElementById('continent').textContent = 'Non disponible';
                        document.getElementById('superficie').textContent = 'Non disponible';
                        document.getElementById('climat').textContent = 'Non disponible';
                        document.getElementById('drapeau').style.display = 'none';
                        document.getElementById('btn-jouer').href = 'quiz.php';
                    }

                    // Scroll vers les infos
                    box.scrollIntoView({behavior: 'smooth'});
                });
            });
        }
    }).addTo(carte);
});
</script>

<?php require_once 'includes/footer.php'; ?>