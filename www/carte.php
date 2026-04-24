<?php
session_start();
require_once 'includes/db.php';
if(!isset($_SESSION['user_id'])){ header("Location: index.php"); exit; }
?>
<?php require_once 'includes/header.php'; ?>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<div style="padding:20px; max-width:1000px; margin:auto;">
    <h1 style="color:#1B6CA8; text-align:center; margin-bottom:5px;">🗺️ Explore le monde !</h1>
    <p style="text-align:center; color:#888; margin-bottom:15px;">Clique sur un pays pour le découvrir !</p>
    <div id="carte" style="height:500px; width:100%; border-radius:15px;"></div>
    <div id="info-pays" class="carte-style" style="margin-top:20px; text-align:center; display:none;">
        <p style="font-size:22px;">🌍 Clique sur un pays !</p>
    </div>
</div>

<script>
var map = L.map('carte').setView([20, 0], 2);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);

var couleurs = ['#FFD54F','#4FC3F7','#81C784','#FF8A65','#BA68C8','#F06292','#64B5F6','#A5D6A7'];
function couleurAlea(){ return couleurs[Math.floor(Math.random()*couleurs.length)]; }

var traductions = {
    'France':'France','Germany':'Allemagne','Italy':'Italie','Spain':'Espagne',
    'Portugal':'Portugal','United Kingdom':'Royaume-Uni','Belgium':'Belgique',
    'Netherlands':'Pays-Bas','Switzerland':'Suisse','Morocco':'Maroc',
    'Senegal':'Senegal','Nigeria':'Nigeria','Egypt':'Egypte',
    'South Africa':'Afrique du Sud','Kenya':'Kenya','Japan':'Japon',
    'China':'Chine','India':'Inde','South Korea':'Coree du Sud',
    'Thailand':'Thailande','Vietnam':'Vietnam','Brazil':'Bresil',
    'Argentina':'Argentine','Mexico':'Mexique','Canada':'Canada',
    'United States of America':'Etats-Unis','Australia':'Australie',
    'Russia':'Russie','Turkey':'Turquie','Indonesia':'Indonesie'
};

axios.get('https://raw.githubusercontent.com/johan/world.geo.json/master/countries.geo.json')
.then(function(response){
    L.geoJSON(response.data, {
        style: function(){ return { color:'#555', weight:1, fillColor:couleurAlea(), fillOpacity:0.7 }; },
        onEachFeature: function(feature, layer){
            layer.on({
                click: function(){
                    var nomEn = feature.properties.name;
                    var nomFr = traductions[nomEn] || nomEn;
                    map.fitBounds(layer.getBounds());
                    document.getElementById('info-pays').style.display = 'block';
                    document.getElementById('info-pays').innerHTML = '<p>⏳ Chargement...</p>';
                    fetch('api_pays.php?nom='+encodeURIComponent(nomFr))
                    .then(function(r){ return r.json(); })
                    .then(function(pays){
                        if(pays.nom_pays){
                            document.getElementById('info-pays').innerHTML =
                                '<img src="'+pays.drapeau_url+'" style="height:55px; margin-bottom:10px; border-radius:5px;"><br>'+
                                '<h2 style="color:#1B6CA8;">'+pays.nom_pays+'</h2>'+
                                '<p style="margin:5px 0;">🏛️ Capitale : <strong>'+pays.capitale+'</strong></p>'+
                                '<p style="margin:5px 0;">🌍 Continent : '+pays.continent+'</p>'+
                                '<p style="margin:5px 0;">📏 Superficie : '+pays.superficie+' km²</p>'+
                                '<p style="margin:5px 0;">🌤️ Climat : '+pays.climat+'</p>'+
                                '<br><a href="quiz.php?pays_id='+pays.id+'" style="background:linear-gradient(135deg,#1B6CA8,#6C3AB5); color:white; padding:10px 20px; border-radius:25px; text-decoration:none; font-weight:700;">🎮 Jouer avec ce pays !</a>';
                        } else {
                            document.getElementById('info-pays').innerHTML = '<h2>'+nomFr+'</h2><p>Pas encore dans notre base !</p>';
                        }
                    });
                },
                mouseover: function(){ layer.setStyle({ fillOpacity:1 }); },
                mouseout: function(){ layer.setStyle({ fillOpacity:0.7 }); }
            });
        }
    }).addTo(map);
});
</script>

<?php require_once 'includes/footer.php'; ?>