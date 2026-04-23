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
#carte {
    height: 650px;
    width: 100%;
    border-radius: 20px;
    box-shadow: 0 5px 25px rgba(0,0,0,0.15);
}
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
.info-ligne {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #F0F0F0;
    font-size: 15px;
}
.info-ligne:last-of-type { border: none; }
.drapeau-img {
    height: 70px;
    border-radius: 8px;
    margin-bottom: 15px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}
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
.btn-jouer-pays:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(27,108,168,0.4);
}
</style>

<main class="page-carte">
    <h1>🗺️ Explore le monde !</h1>
    <p class="sous-titre">Clique sur un pays pour découvrir sa capitale ! 🌍</p>

    <div id="carte"></div>

    <div class="infos-pays-box" id="infos-pays">
        <img id="drapeau" src="" alt="drapeau" class="drapeau-img" style="display:none;">
        <h2 id="nom-pays">Clique sur un pays !</h2>
        <div class="info-ligne">
            <span>🏛️ Capitale</span>
            <strong id="capitale">—</strong>
        </div>
        <div class="info-ligne">
            <span>🌍 Continent</span>
            <span id="continent">—</span>
        </div>
        <div class="info-ligne">
            <span>📏 Superficie</span>
            <span id="superficie">—</span>
        </div>
        <div class="info-ligne">
            <span>🌤️ Climat</span>
            <span id="climat">—</span>
        </div>
        <a id="btn-jouer" href="quiz.php" class="btn-jouer-pays">
            🎮 Jouer avec ce pays !
        </a>
    </div>
</main>

<script>
// Création de la carte Leaflet
var carte = L.map('carte', {
    center: [20, 0],
    zoom: 2,
    minZoom: 2,
    maxZoom: 10
});

// Fond de carte OpenStreetMap
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap contributors'
}).addTo(carte);

// Marqueur actuel
var marqueur = null;

// Quand on clique sur la carte
carte.on('click', function(e) {
    var lat = e.latlng.lat;
    var lng = e.latlng.lng;

    // Utilise l'API Nominatim pour trouver le pays
    fetch('https://nominatim.openstreetmap.org/reverse?lat=' + lat + '&lon=' + lng + '&format=json&accept-language=fr')
    .then(function(r) { return r.json(); })
    .then(function(data) {

        if(data && data.address && data.address.country) {
            var nomPays = data.address.country;

            // Ajoute un marqueur
            if(marqueur) carte.removeLayer(marqueur);
            marqueur = L.marker([lat, lng])
                .bindPopup('<strong>' + nomPays + '</strong>')
                .addTo(carte)
                .openPopup();

            // Cherche dans notre BDD
            fetch('api_pays.php?nom=' + encodeURIComponent(nomPays))
            .then(function(r) { return r.json(); })
            .then(function(pays) {

                var box = document.getElementById('infos-pays');
                box.style.display = 'block';

                if(pays.nom_pays) {
                    document.getElementById('nom-pays').textContent = pays.nom_pays;
                    document.getElementById('capitale').textContent = pays.capitale;
                    document.getElementById('continent').textContent = pays.continent;
                    document.getElementById('superficie').textContent = pays.superficie + ' km²';
                    document.getElementById('climat').textContent = pays.climat;

                    if(pays.drapeau_url) {
                        var img = document.getElementById('drapeau');
                        img.src = pays.drapeau_url;
                        img.style.display = 'block';
                    } else {
                        document.getElementById('drapeau').style.display = 'none';
                    }

                    document.getElementById('btn-jouer').href = 'quiz.php?pays_id=' + pays.id;

                } else {
                    document.getElementById('nom-pays').textContent = nomPays;
                    document.getElementById('capitale').textContent = 'Pas encore dans notre BDD';
                    document.getElementById('continent').textContent = '—';
                    document.getElementById('superficie').textContent = '—';
                    document.getElementById('climat').textContent = '—';
                    document.getElementById('drapeau').style.display = 'none';
                    document.getElementById('btn-jouer').href = 'quiz.php';
                }

                box.scrollIntoView({behavior: 'smooth'});
            });
        }
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>