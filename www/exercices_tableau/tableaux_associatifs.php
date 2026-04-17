<?php
// Dé claration d'un tableau associatif
$personne = [
"nom" => "Lotfi",
"age" => 30,
"ville" => "Paris"
];
// Accéder à un élément du tableau
echo $personne ["nom"]; // Affiche : Lotfi
// Ajouter une nouvelle clé/valeur
$personne [" profession "] = " Professeur ";
// Parcourir le tableau
foreach ( $personne as $cle => $valeur ) {
echo "$cle : $valeur \n";
}
/*
Affiche :
nom : Lotfi
age : 30
ville : Paris
profession : Professeur
*/
?>