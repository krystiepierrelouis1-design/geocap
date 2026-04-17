<?php
// inscription.php

// les infos pour se connecter a la base de donnees
$hote  = 'db';
$login = 'root';
$mdp   = 'root';
$base  = 'inscription';

// on cree des variables vides au depart
$erreurs = [];
$message = '';
$message_type = '';

// les champs du formulaire, vides au depart
$nom = '';
$prenom = '';
$date_naissance = '';
$lieu_naissance = '';
$adresse = '';
$code_postal = '';
$ville = '';
$pays = '';
$telephone = '';

// ce bloc s'execute seulement quand on clique sur Valider
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // on recupere ce que l'utilisateur a tape dans chaque champ
    // trim() enleve les espaces en trop avant et aprés 
    $nom            = trim($_POST['nom']);
    $prenom         = trim($_POST['prenom']);
    $date_naissance = trim($_POST['date_naissance']);
    $lieu_naissance = trim($_POST['lieu_naissance']);
    $adresse        = trim($_POST['adresse']);
    $code_postal    = trim($_POST['code_postal']);
    $ville          = trim($_POST['ville']);
    $pays           = trim($_POST['pays']);
    $telephone      = trim($_POST['telephone']);

    // on verifie que chaque champ n'est pas vide
    // si vide on ajoute un message d'erreur dans le tableau $erreurs
    if (empty($nom))            $erreurs[] = "Le nom est obligatoire.";
    if (empty($prenom))         $erreurs[] = "Le prenom est obligatoire.";
    if (empty($date_naissance)) $erreurs[] = "La date de naissance est obligatoire.";
    if (empty($lieu_naissance)) $erreurs[] = "Le lieu de naissance est obligatoire.";
    if (empty($adresse))        $erreurs[] = "L'adresse est obligatoire.";
    if (empty($code_postal))    $erreurs[] = "Le code postal est obligatoire.";
    if (empty($ville))          $erreurs[] = "La ville est obligatoire.";
    if (empty($pays))           $erreurs[] = "Le pays est obligatoire.";
    if (empty($telephone))      $erreurs[] = "Le telephone est obligatoire.";

    // si le tableau $erreurs est vide = pas d'erreurs = on peut enregistrer
    if (empty($erreurs)) {
        try {
            // on se connecte a la base de donnees avec PDO
            $pdo = new PDO("mysql:host=$hote;dbname=$base;charset=utf8mb4", $login, $mdp);

            // la requete SQL pour ajouter une ligne dans la table utilisateurs
            // les :nom :prenom etc. sont des emplacements qui seront remplaces par les vraies valeurs
            // ça protege contre les injections SQL
            $sql = "INSERT INTO utilisateurs (nom, prenom, date_naissance, lieu_naissance, adresse, code_postal, ville, pays, telephone)
                    VALUES (:nom, :prenom, :date_naissance, :lieu_naissance, :adresse, :code_postal, :ville, :pays, :telephone)";

            // on prepare la requete
            $stmt = $pdo->prepare($sql);

            // on execute la requete avec les vraies valeurs
            $stmt->execute([
                ':nom'            => $nom,
                ':prenom'         => $prenom,
                ':date_naissance' => $date_naissance,
                ':lieu_naissance' => $lieu_naissance,
                ':adresse'        => $adresse,
                ':code_postal'    => $code_postal,
                ':ville'          => $ville,
                ':pays'           => $pays,
                ':telephone'      => $telephone,
            ]);

            // tout s'est bien passe, on affiche un message de succes
            $message = "Inscription reussie ! Bienvenue " . htmlspecialchars($prenom) . " " . htmlspecialchars($nom);
            $message_type = 'succes';

            // on vide les champs apres l'inscription
            $nom = $prenom = $date_naissance = $lieu_naissance = '';
            $adresse = $code_postal = $ville = $pays = $telephone = '';

        } catch (PDOException $e) {
            // si la connexion ou la requete echoue, on affiche l'erreur
            $message = "Erreur : " . $e->getMessage();
            $message_type = 'erreur';
        }

    } else {
        // il y a des erreurs, on le signale
        $message_type = 'erreur';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <!-- on charge le fichier CSS pour le style de la page -->
    <link rel="stylesheet" href="css_du_projet_un.css">
</head>
<body>

<h1>Formulaire d'inscription</h1>

<div class="boite">

    <!-- si l'inscription a reussi on affiche le message vert -->
    <?php if ($message_type == 'succes'): ?>
        <div class="message-succes"><?= $message ?></div>
    <?php endif; ?>

    <!-- si il y a des erreurs on les affiche en rouge -->
    <?php if ($message_type == 'erreur'): ?>
        <div class="message-erreur">
            <?php if (!empty($erreurs)): ?>
                <strong>Erreurs :</strong>
                <ul>
                    <!-- on affiche chaque erreur une par une -->
                    <?php foreach ($erreurs as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <?= $message ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- le formulaire, action="" = envoie vers le meme fichier -->
    <form action="" method="POST">

        <p class="titre-section">Identite</p>

        <!-- deux champs cote a cote --> 
         <!--htmlspecialchars : sécuriser l'affichage-->
        <div class="deux-colonnes">
            <div class="champ">
                <label>Nom <span class="etoile">*</span></label>
                <input type="text" name="nom" value="<?= htmlspecialchars($nom) ?>">
            </div>
            <div class="champ">
                <label>Prenom <span class="etoile">*</span></label>
                <input type="text" name="prenom" value="<?= htmlspecialchars($prenom) ?>">
            </div>
        </div>

        <div class="deux-colonnes">
            <div class="champ">
                <label>Date de naissance <span class="etoile">*</span></label>
                <input type="date" name="date_naissance" value="<?= htmlspecialchars($date_naissance) ?>">
            </div>
            <div class="champ">
                <label>Lieu de naissance <span class="etoile">*</span></label>
                <input type="text" name="lieu_naissance" value="<?= htmlspecialchars($lieu_naissance) ?>">
            </div>
        </div>

        <p class="titre-section">Coordonnees</p>

        <div class="champ">
            <label>Adresse <span class="etoile">*</span></label>
            <input type="text" name="adresse" value="<?= htmlspecialchars($adresse) ?>">
        </div>

        <div class="deux-colonnes">
            <div class="champ">
                <label>Code postal <span class="etoile">*</span></label>
                <input type="text" name="code_postal" value="<?= htmlspecialchars($code_postal) ?>">
            </div>
            <div class="champ">
                <label>Ville <span class="etoile">*</span></label>
                <input type="text" name="ville" value="<?= htmlspecialchars($ville) ?>">
            </div>
        </div>

        <div class="deux-colonnes">
            <div class="champ">
                <label>Pays <span class="etoile">*</span></label>
                <input type="text" name="pays" value="<?= htmlspecialchars($pays) ?>">
            </div>
            <div class="champ">
                <label>Telephone <span class="etoile">*</span></label>
                <input type="tel" name="telephone" value="<?= htmlspecialchars($telephone) ?>">
            </div>
        </div>

        <!-- les boutons du formulaire -->
        <div class="boutons">
            <button type="submit" class="btn-bleu">Valider</button>
            <button type="reset" class="btn-gris">Reinitialiser</button>
            <a href="liste_inscrits.php" class="btn-gris">Voir la liste</a>
        </div>

    </form>

</div>

</body>
</html>