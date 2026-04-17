<?php
// Dé claration d'un tableau
$fruits = ["Pomme", "Banane ", "Cerise "];
// Accéder à un élément du tableau
echo $fruits [0]; // Affiche : Pomme
// Ajouter un nouvel élément
$fruits [] = "Orange ";
// Parcourir le tableau avec une boucle
foreach ( $fruits as $fruit) {
echo $fruit . " ";
}
// Affiche : Pomme Banane Cerise Orange
?>