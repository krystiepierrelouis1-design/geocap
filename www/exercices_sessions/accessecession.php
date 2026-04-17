<?php
// access_session .php
session_start ();
if (isset( $_SESSION ["nom"])) {
echo " Bienvenue " . $_SESSION ["nom"];
} else {
echo "Aucune session utilisateur active .";
}
?>