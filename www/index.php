<?php
$dossiers = scandir('.');
echo "<h1>Mes projets</h1>";
foreach($dossiers as $dossier) {
    echo '<a href="' . $dossier . '">' . $dossier . '</a><br>';
}
?>