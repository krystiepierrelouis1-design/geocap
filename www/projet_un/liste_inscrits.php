<?php

// liste_inscrits.php

// les infos pour se connecter a la base de donnees
$hote  = 'db';
$login = 'root';
$mdp   = 'root';
$base  = 'inscription';

// tableau vide qui va stocker les inscrits
$utilisateurs = [];
$db_error = '';

try {
    // on se connecte a la base de donnees
    $pdo = new PDO("mysql:host=$hote;dbname=$base;charset=utf8mb4", $login, $mdp);

    // on recupere tous les utilisateurs, du plus recent au plus ancien
    $stmt = $pdo->query("SELECT * FROM utilisateurs ORDER BY id DESC");

    // on met tous les resultats dans le tableau $utilisateurs
    $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // si la connexion echoue, on stocke l'erreur
    $db_error = "Erreur : " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des inscrits</title>
    <!-- on charge le fichier CSS pour le style de la page -->
    <link rel="stylesheet" href="css_du_projet_un.css">
</head>
<body>

<h1>Liste des inscrits</h1>

<!-- si erreur de connexion on l'affiche -->
<?php if ($db_error): ?>
    <div class="message-erreur"><?= $db_error ?></div>

<?php else: ?>

    <!-- barre du haut avec le nombre d'inscrits et le bouton ajouter -->
    <div class="topbar">
        <span class="compteur"><?= count($utilisateurs) ?> inscrit(s)</span>
        <a href="inscription.php" class="btn-bleu">+ Ajouter</a>
    </div>

    <!-- si personne dans la base on affiche ce message -->
    <?php if (empty($utilisateurs)): ?>
        <div class="boite-tableau">
            <p style="padding:20px; text-align:center;">Aucun inscrit pour le moment.</p>
        </div>

    <!-- sinon on affiche le tableau avec tous les inscrits -->
    <?php else: ?>
        <div class="boite-tableau">
            <table>
                <!-- l'en-tete du tableau -->
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Prenom</th>
                        <th>Date naissance</th>
                        <th>Lieu naissance</th>
                        <th>Adresse</th>
                        <th>Code postal</th>
                        <th>Ville</th>
                        <th>Pays</th>
                        <th>Telephone</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- foreach = on repete une ligne pour chaque inscrit -->
                    <?php foreach ($utilisateurs as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['nom']) ?></td>
                        <td><?= htmlspecialchars($u['prenom']) ?></td>
                        <td><?= htmlspecialchars($u['date_naissance']) ?></td>
                        <td><?= htmlspecialchars($u['lieu_naissance']) ?></td>
                        <td><?= htmlspecialchars($u['adresse']) ?></td>
                        <td><?= htmlspecialchars($u['code_postal']) ?></td>
                        <td><?= htmlspecialchars($u['ville']) ?></td>
                        <td><?= htmlspecialchars($u['pays']) ?></td>
                        <td><?= htmlspecialchars($u['telephone']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

<?php endif; ?>

</body>
</html>