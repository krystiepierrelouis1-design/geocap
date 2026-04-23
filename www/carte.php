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
.sous-titre { text-align: center; color: #888; margin-bottom: 20px; }

#carte {
    height: 600px;
    width: 100%;
    border-radius: 20px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.15);
    cursor: crosshair;
}

.infos-pays-box {
    background: white;
    border-radius: 20px;
    padding: 25px 30px;
    margin-top: 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
    text-align: center;
    display: none;
    border-top: 5px solid #1B6CA8;
}

.drapeau-img {
    height: 60px;
    border-radius: 6px;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.infos-pays-box h2 {
    color: #1B6CA8;
    font-size: 22px;
    margin-bottom: 15px;
}

.info-grille {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-bottom: 20px;
}

.info-bloc {
    background: #F8F9FA;
    border-radius: 12px;
    padding: 12px;
    text-align: center;
}

.info-bloc .emoji { font-size: 22px; display: block; }
.info-bloc .label { font-size: 11px; color: #888; margin: 3px 0; }
.info-bloc .valeur { font-size: 14px; font-weight: 800; color: #2D2D2D; }

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
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(27,108,168,0.3);
}

.btn-jouer-pays:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(27,108,168,0.4);
}

.chargement {
    text-align: center;
    padding: 20px;
    color: #888;
    display: none;
}
</style>

<main class="page-carte">
    <h1>🗺️ Explore le monde !</h1>
    <p class="sous-titre">Clique n'importe où sur la carte pour découvrir un pays ! 🌍</p>

    <div id="carte"></div>

    <!-- Chargement -->
    <p class="chargement" id="chargement">⏳ Chargement des infos...</p>

    <!-- Infos pays -->
    <div class="infos-pays-box" id="infos-pays">
        <img id="drapeau" src="" alt="drapeau" class="drapeau-img" style="display:none;">
        <h2 id="nom-pays"></h2>

        <div class="info-grille">
            <div class="info-bloc">
                <span class="emoji">🏛️</span>
                <div class="label">Capitale</div>
                <div class="valeur" id="capitale">—</div>
            </div>
            <div class="info-bloc">
                <span class="emoji">🌍</span>
                <div class="label">Continent</div>
                <div class="valeur" id="continent">—</div>
            </div>
            <div class="info-bloc">
                <span class="emoji">📏</span>
                <div class="label">Superficie</div>
                <div class="valeur" id="superficie">—</div>
            </div>
            <div class="info-bloc">
                <span class="emoji">🌤️</span>
                <div class="label">Climat</div>
                <div class="valeur" id="climat">—</div>
            </div>
        </div>

        <a id="btn-jouer" href="quiz.php" class="btn-jouer-pays">
            🎮 Jouer avec ce pays !
        </a>
    </div>
</main>

<script>
var carte = L.map('carte', {
    center: [20, 0],
    zoom: 2,
    minZoom: 2,
    maxZoom: 12,
    zoomControl: true
});

// Fond de carte simple et propre
L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
    attribution: '© OpenStreetMap © CartoDB',
    subdomains: 'abcd'
}).addTo(carte);

var marqueur = null;

carte.on('click', function(e) {
    var lat = e.latlng.lat;
    var lng = e.latlng.lng;

    // Affiche chargement
    document.getElementById('chargement').style.display = 'block';
    document.getElementById('infos-pays').style.display = 'none';

    // Ajoute marqueur
    if(marqueur) carte.removeLayer(marqueur);
    marqueur = L.marker([lat, lng]).addTo(carte);

    // Trouve le pays avec Nominatim
    fetch('https://nominatim.openstreetmap.org/reverse?lat=' + lat + '&lon=' + lng + '&format=json&accept-language=fr')
    .then(function(r){ return r.json(); })
    .then(function(data){

        document.getElementById('chargement').style.display = 'none';

        if(data && data.address && data.address.country){
            var nomPays = data.address.country;

            marqueur.bindPopup('<strong>' + nomPays + '</strong>').openPopup();

            // Cherche dans notre BDD
            fetch('api_pays.php?nom=' + encodeURIComponent(nomPays))
            .then(function(r){ return r.json(); })
            .then(function(pays){

                var box = document.getElementById('infos-pays');
                box.style.display = 'block';

                if(pays.nom_pays){
                    document.getElementById('nom-pays').textContent = pays.nom_pays;
                    document.getElementById('capitale').textContent = pays.capitale;
                    document.getElementById('continent').textContent = pays.continent;
                    document.getElementById('superficie').textContent = pays.superficie + ' km²';
                    document.getElementById('climat').textContent = pays.climat;

                    if(pays.drapeau_url){
                        var img = document.getElementById('drapeau');
                        img.src = pays.drapeau_url;
                        img.style.display = 'block';
                    } else {
                        document.getElementById('drapeau').style.display = 'none';
                    }

                    document.getElementById('btn-jouer').href = 'quiz.php?pays_id=' + pays.id;

                } else {
                    document.getElementById('nom-pays').textContent = nomPays;
                    document.getElementById('capitale').textContent = 'Pas encore disponible';
                    document.getElementById('continent').textContent = '—';
                    document.getElementById('superficie').textContent = '—';
                    document.getElementById('climat').textContent = '—';
                    document.getElementById('drapeau').style.display = 'none';
                    document.getElementById('btn-jouer').href = 'quiz.php';
                }

                box.scrollIntoView({behavior: 'smooth'});
            });
        } else {
            document.getElementById('chargement').style.display = 'none';
        }
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>