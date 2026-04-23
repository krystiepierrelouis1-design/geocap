<?php
session_start();
// session

require_once 'includes/db.php';
// base

if(!isset($_SESSION['user_id'])){
// sécurité

header("Location:index.php");
// login

exit;
// stop

}
?>

<?php require_once 'includes/header.php'; ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
// CSS carte

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
// JS carte

<div id="carte"></div>
// conteneur carte

<div id="infos-pays" style="display:none;">
// bloc infos caché

<h2 id="nom-pays"></h2>
// nom pays

<p>Capitale : <span id="capitale"></span></p>
// capitale

<p>Continent : <span id="continent"></span></p>
// continent

<a id="btn-jouer" href="#">
// bouton jouer

🎮 Jouer avec ce pays !

</a>
// fin bouton

</div>
// fin bloc

<script>

var carte = L.map("carte").setView([20,0],2);
// création carte

L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png").addTo(carte);
// fond carte

carte.on("click", function(e){
// clic carte

var lat = e.latlng.lat;
// latitude

var lng = e.latlng.lng;
// longitude

fetch("api_pays.php?lat="+lat+"&lng="+lng)
// appel API

.then(r=>r.json())
// transforme JSON

.then(data=>{
// résultat

document.getElementById("nom-pays").textContent = data.nom;
// nom

document.getElementById("capitale").textContent = data.capitale;
// capitale

document.getElementById("continent").textContent = data.continent;
// continent

document.getElementById("infos-pays").style.display="block";
// affiche bloc

});

});

</script>

<?php require_once 'includes/footer.php'; ?>
