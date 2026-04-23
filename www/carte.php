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
<main style="padding:20px;">
    <h1>🗺️ Carte du monde</h1>
    <div id="carte"></div>
    <div id="infos-pays" style="display:none; background:#F5F5F5; padding:20px; border-radius:10px; margin-top:20px;">
        <h2 id="nom-pays"></h2>
        <p>Capitale : <strong><span id="capitale"></span></strong></p>
        <p>Continent : <span id="continent"></span></p>
        <a id="btn-jouer" href="#" style="background:#1B6CA8; color:white; padding:10px 20px; border-radius:8px; text-decoration:none;">
            🎮 Jouer avec ce pays !
        </a>
    </div>
</main>
<script>
var carte = L.map("carte").setView([20,0],2);
L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(carte);
carte.on("click", function(e){
    var lat = e.latlng.lat;
    var lng = e.latlng.lng;
    fetch("api_pays.php?lat="+lat+"&lng="+lng)
    .then(function(r){ return r.json(); })
    .then(function(data){
        if(data.nom_pays){
            document.getElementById("nom-pays").textContent = data.nom_pays;
            document.getElementById("capitale").textContent = data.capitale;
            document.getElementById("continent").textContent = data.continent;
            document.getElementById("btn-jouer").href = "quiz.php?pays_id="+data.id;
            document.getElementById("infos-pays").style.display = "block";
        }
    });
});
</script>
<?php require_once 'includes/footer.php'; ?>