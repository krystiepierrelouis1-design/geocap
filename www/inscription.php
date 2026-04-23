<?php
session_start();
// démarre la session (pour garder l'utilisateur connecté après inscription)

require_once 'includes/db.php';
// connecte la base de données

$erreur = "";
// variable vide pour afficher un message d'erreur si besoin


if(isset($_POST['pseudo'])){
    // vérifie si le formulaire a été envoyé

    $nom = htmlspecialchars($_POST['nom']);
    // récupère le nom + protège contre les attaques

    $prenom = htmlspecialchars($_POST['prenom']);
    // récupère le prénom

    $pseudo = htmlspecialchars($_POST['pseudo']);
    // récupère le pseudo

    $email = htmlspecialchars($_POST['email']);
    // récupère l'email

    $mdp = $_POST['mdp'];
    // récupère le mot de passe (pas encore sécurisé ici)


    $stmt = $pdo->prepare('SELECT id FROM utilisateurs WHERE pseudo = ? OR email = ?');
    // vérifie si le pseudo ou email existe déjà

    $stmt->execute([$pseudo, $email]);
    // envoie les infos à la base

    $existe = $stmt->fetch();
    // regarde si un utilisateur existe déjà


    if($existe){
        // si déjà utilisé

        $erreur = "Ce pseudo ou email existe déjà 😊 Essaie un autre !";
        // message d'erreur
    } else {
        // si tout est OK

        $mdp_chiffre = password_hash($mdp, PASSWORD_DEFAULT);
        // transforme le mot de passe en version sécurisée

        $stmt2 = $pdo->prepare('INSERT INTO utilisateurs (nom, prenom, pseudo, email, mot_de_passe, role, statut, derniere_connexion) VALUES (?, ?, ?, ?, ?, "enfant", "actif", NOW())');
        // crée un nouvel utilisateur dans la base

        $stmt2->execute([$nom, $prenom, $pseudo, $email, $mdp_chiffre]);
        // enregistre l'utilisateur


        $new_id = $pdo->lastInsertId();
        // récupère l'id du nouvel utilisateur

        $stmt3 = $pdo->prepare('INSERT INTO scores (utilisateur_id, points, continent, badges) VALUES (?, 0, "", "")');
        // crée son profil de score (départ à 0)

        $stmt3->execute([$new_id]);
        // enregistre les scores


        $_SESSION['user_id'] = $new_id;
        // connecte automatiquement l'utilisateur

        $_SESSION['pseudo'] = $pseudo;
        // stocke le pseudo

        $_SESSION['role'] = 'enfant';
        // donne le rôle "enfant"


        header('Location: accueil.php');
        // redirige vers la page d'accueil

        exit;
        // arrête le code
    }
}
?>

<?php require_once 'includes/header.php'; ?>
// affiche le haut du site

<main class="formulaire">

<h2>🌍 Créer mon compte GEOCAP</h2>
// titre de la page

<?php if($erreur): ?>
    // si erreur existe

    <p style="color:red"><?= $erreur ?></p>
    // affiche message rouge
<?php endif; ?>

<form method="POST">
    // début du formulaire

    <input type="text" name="nom" placeholder="Ton nom" required>
    // champ nom obligatoire

    <input type="text" name="prenom" placeholder="Ton prénom" required>
    // champ prénom obligatoire

    <input type="text" name="pseudo" placeholder="Choisis un pseudo" required>
    // champ pseudo obligatoire

    <input type="email" name="email" placeholder="Ton email" required>
    // champ email obligatoire

    <input type="password" name="mdp" placeholder="Choisis un mot de passe" required>
    // champ mot de passe obligatoire

    <button type="submit">C'est parti pour l'aventure ! 🚀</button>
    // bouton envoyer

</form>

<p>Tu as déjà un compte ? <a href="index.php">Connecte-toi ici !</a></p>
// lien vers connexion

</main>

<?php require_once 'includes/footer.php'; ?>
// affiche le bas du site