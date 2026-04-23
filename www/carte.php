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

<main class="page-carte">
    <h1>🗺️ Explore le monde !</h1>
    <p style="text-align:center; color:#666; margin-bottom:20px;">
        Clique sur un pays pour découvrir sa capitale !
    </p>

    <!-- Carte du monde -->
    <div id="carte"></div>

    <!-- Infos pays -->
    <div id="infos-pays" class="infos-pays-box" style="display:none;">

        <!-- Drapeau -->
        <img id="drapeau" src="" alt="drapeau" class="drapeau">

        <!-- Nom pays -->
        <h2 id="nom-pays"></h2>

        <!-- Infos -->
        <p>🏛️ Capitale : <strong><span id="capitale"></span></strong></p>
        <p>🌍 Continent : <span id="continent"></span></p>
        <p>📏 Superficie : <span id="superficie"></span> km²</p>
        <p>🌤️ Climat : <span id="climat"></span></p>

        <!-- Bouton jouer -->
        <br>
        <a id="btn-jouer" href="#" class="btn-principal">
            🎮 Jouer avec ce pays !
        </a>

    </div>
</main>

<script>
// Création de la carte
var carte = L.map("carte").setView([20, 0], 2);

// Fond de carte
L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
    attribution: '© OpenStreetMap'
}).addTo(carte);

// Marqueur actuel
var marqueur = null;

// Quand on clique sur la carte
carte.on("click", function(e) {
    var lat = e.latlng.lat;
    var lng = e.latlng.lng;

    // Appel API pour récupérer les infos du pays
    fetch("api_pays.php?lat=" + lat + "&lng=" + lng)
    .then(function(r) { return r.json(); })
    .then(function(data) {

        if(data.nom_pays) {

            // Ajoute un marqueur sur la carte
            if(marqueur) carte.removeLayer(marqueur);
            marqueur = L.marker([lat, lng]).addTo(carte);

            // Affiche les infos
            document.getElementById("nom-pays").textContent = data.nom_pays;
            document.getElementById("capitale").textContent = data.capitale;
            document.getElementById("continent").textContent = data.continent;
            document.getElementById("superficie").textContent = data.superficie;
            document.getElementById("climat").textContent = data.climat;

            // Affiche le drapeau
            if(data.drapeau_url) {
                document.getElementById("drapeau").src = data.drapeau_url;
                document.getElementById("drapeau").style.display = "block";
            }

            // Lien vers quiz
            document.getElementById("btn-jouer").href = "quiz.php?pays_id=" + data.id;

            // Affiche le bloc infos
            document.getElementById("infos-pays").style.display = "block";

            // Scroll vers les infos
            document.getElementById("infos-pays").scrollIntoView({behavior: "smooth"});
        }
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>