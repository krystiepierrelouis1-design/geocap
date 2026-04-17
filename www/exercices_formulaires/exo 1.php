<?php
/**
 * Exercice 1 - Traitement du formulaire de contact
 * CNAM Paris - M. Mostefaoui
 */

// Vérifier que le formulaire a été soumis en POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Récupération et nettoyage des données
    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    // Variable pour stocker les erreurs
    $erreurs = array();
    
    // Validation : vérifier que tous les champs sont remplis
    if (empty($nom)) {
        $erreurs[] = "Le champ 'Nom' est obligatoire.";
    }
    
    if (empty($email)) {
        $erreurs[] = "Le champ 'Email' est obligatoire.";
    }
    
    if (empty($message)) {
        $erreurs[] = "Le champ 'Message' est obligatoire.";
    }
    
    // Affichage du résultat
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Résultat - Exercice 1</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                max-width: 600px;
                margin: 50px auto;
                padding: 20px;
                background-color: #f4f4f4;
            }
            .container {
                background-color: white;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }
            h1 {
                color: #333;
                text-align: center;
            }
            .erreur {
                background-color: #f8d7da;
                color: #721c24;
                padding: 15px;
                border-radius: 4px;
                border: 1px solid #f5c6cb;
                margin-bottom: 20px;
            }
            .succes {
                background-color: #d4edda;
                color: #155724;
                padding: 15px;
                border-radius: 4px;
                border: 1px solid #c3e6cb;
                margin-bottom: 20px;
            }
            .info {
                margin: 10px 0;
            }
            .label {
                font-weight: bold;
                color: #555;
            }
            .retour {
                margin-top: 20px;
                text-align: center;
            }
            .retour a {
                color: #4CAF50;
                text-decoration: none;
            }
            .retour a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Résultat du Formulaire</h1>
            
            <?php if (!empty($erreurs)): ?>
                <!-- Affichage des erreurs -->
                <div class="erreur">
                    <strong>Erreur :</strong>
                    <ul>
                        <?php foreach ($erreurs as $erreur): ?>
                            <li><?php echo htmlspecialchars($erreur); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <p>Veuillez remplir tous les champs.</p>
                </div>
            <?php else: ?>
                <!-- Affichage des données si tout est valide -->
                <div class="succes">
                    <strong>Formulaire soumis avec succès !</strong>
                </div>
                
                <h2>Données reçues :</h2>
                
                <div class="info">
                    <span class="label">Nom :</span>
                    <?php echo htmlspecialchars($nom); ?>
                </div>
                
                <div class="info">
                    <span class="label">Email :</span>
                    <?php echo htmlspecialchars($email); ?>
                </div>
                
                <div class="info">
                    <span class="label">Message :</span><br>
                    <?php echo nl2br(htmlspecialchars($message)); ?>
                </div>
            <?php endif; ?>
            
            <div class="retour">
                <a href="exercice1_formulaire.html">← Retour au formulaire</a>
            </div>
        </div>
    </body>
    </html>
    <?php
    
} else {
    // Si on accède directement au script sans formulaire
    header("Location: exercice1_formulaire.html");
    exit();
}
?>