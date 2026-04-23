<?php
session_start();
require_once '../includes/db.php';

if(!isset($_SESSION['user_id']) ||
($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'professeur')){
    header('Location: ../accueil.php'); exit;
}

$succes = "";
$erreur = "";

// Bloquer un compte
if(isset($_GET['bloquer'])){
    $id = (int)$_GET['bloquer'];
    $pdo->prepare('UPDATE utilisateurs SET role="bloque" WHERE id=?')->execute([$id]);
    $succes = "✅ Compte bloqué !";
}

// Débloquer un compte
if(isset($_GET['debloquer'])){
    $id = (int)$_GET['debloquer'];
    $pdo->prepare('UPDATE utilisateurs SET role="enfant", tentatives=0 WHERE id=?')->execute([$id]);
    $succes = "✅ Compte débloqué !";
}

// Supprimer un compte
if(isset($_GET['supprimer'])){
    $id = (int)$_GET['supprimer'];
    $pdo->prepare('DELETE FROM scores WHERE utilisateur_id=?')->execute([$id]);
    $pdo->prepare('DELETE FROM reponses WHERE utilisateur_id=?')->execute([$id]);
    $pdo->prepare('DELETE FROM messages WHERE expediteur_id=?')->execute([$id]);
    $pdo->prepare('DELETE FROM utilisateurs WHERE id=?')->execute([$id]);
    $succes = "✅ Compte supprimé !";
}

// Email compte inactif
if(isset($_GET['email_inactif'])){
    $id = (int)$_GET['email_inactif'];
    $u = $pdo->prepare('SELECT * FROM utilisateurs WHERE id=?');
    $u->execute([$id]);
    $user_inactif = $u->fetch();

    $sujet = "GEOCAP — Tu nous manques !";
    $message = "Bonjour " . $user_inactif['prenom'] . ",\n\n";
    $message .= "Cela fait longtemps que tu ne t'es pas connecté sur GEOCAP !\n";
    $message .= "Reviens explorer le monde avec nous ! 🌍\n\n";
    $message .= "Connecte-toi sur : localhost:8080\n\n";
    $message .= "L'équipe GEOCAP";

    mail($user_inactif['email'], $sujet, $message);
    $succes = "📧 Email envoyé à " . $user_inactif['pseudo'] . " !";
}

// Ajouter une question
if(isset($_POST['ajouter_question'])){
    $pays_id = (int)$_POST['pays_id'];
    $question = htmlspecialchars($_POST['question']);
    $bonne = htmlspecialchars($_POST['bonne_reponse']);
    $fausse1 = htmlspecialchars($_POST['fausse1']);
    $fausse2 = htmlspecialchars($_POST['fausse2']);
    $pdo->prepare('INSERT INTO quiz (pays_id, question, bonne_reponse, fausse_reponse1, fausse_reponse2) VALUES (?,?,?,?,?)')->execute([$pays_id, $question, $bonne, $fausse1, $fausse2]);
    $succes = "✅ Question ajoutée !";
}

// Supprimer une question
if(isset($_GET['supprimer_question'])){
    $id = (int)$_GET['supprimer_question'];
    $pdo->prepare('DELETE FROM quiz WHERE id=?')->execute([$id]);
    $succes = "✅ Question supprimée !";
}

// Récupère données
$users = $pdo->query('SELECT * FROM utilisateurs ORDER BY id DESC')->fetchAll();
$messages = $pdo->query('SELECT M.*, U.pseudo FROM messages M JOIN utilisateurs U ON M.expediteur_id = U.id WHERE M.lu = 0 ORDER BY M.date_envoi DESC')->fetchAll();
$questions = $pdo->query('SELECT Q.*, P.nom_pays FROM quiz Q JOIN pays P ON Q.pays_id = P.id ORDER BY Q.id DESC LIMIT 20')->fetchAll();
$pays_liste = $pdo->query('SELECT * FROM pays ORDER BY nom_pays')->fetchAll();

// Comptes inactifs
$inactifs = $pdo->query("SELECT * FROM utilisateurs WHERE derniere_connexion < DATE_SUB(NOW(), INTERVAL 30 DAY) AND role='enfant'")->fetchAll();

// Stats
$total_users = count($users);
$total_questions = $pdo->query('SELECT COUNT(*) as total FROM quiz')->fetch()['total'];
$total_messages = count($messages);
$total_reponses = $pdo->query('SELECT COUNT(*) as total FROM reponses')->fetch()['total'];
?>
<?php require_once '../includes/header.php'; ?>

<style>
.dashboard { max-width: 1000px; margin: 30px auto; padding: 20px; }
.dash-titre { color: #1B6CA8; font-size: 28px; font-weight: 900; margin-bottom: 5px; }
.dash-sous-titre { color: #888; margin-bottom: 30px; }
.stats-grid {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    margin-bottom: 30px;
}
.stat-card {
    background: white;
    border-radius: 16px;
    padding: 20px 25px;
    flex: 1;
    min-width: 150px;
    text-align: center;
    box-shadow: 0 3px 15px rgba(0,0,0,0.06);
}
.stat-card .nombre { font-size: 35px; font-weight: 900; }
.stat-card .label { color: #888; font-size: 13px; }
.section-admin {
    background: white;
    border-radius: 20px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.06);
}
.section-admin h2 {
    font-size: 18px;
    font-weight: 800;
    margin-bottom: 15px;
    padding-bottom: 10px;
    border-bottom: 2px solid #F0F0F0;
}
.user-ligne {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #F5F5F5;
    flex-wrap: wrap;
    gap: 10px;
}
.user-info strong { font-size: 15px; }
.user-info small { color: #888; display: block; }
.badge-role {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    color: white;
}
.role-enfant { background: #1B6CA8; }
.role-admin { background: #8E44AD; }
.role-professeur { background: #10B981; }
.role-bloque { background: #E74C3C; }
.btn-action {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    border: none;
    text-decoration: none;
    display: inline-block;
    font-family: 'Nunito', sans-serif;
    transition: all 0.2s;
    margin: 2px;
}
.btn-bloquer { background: #FEE2E2; color: #E74C3C; }
.btn-debloquer { background: #D1FAE5; color: #10B981; }
.btn-supprimer { background: #FEE2E2; color: #E74C3C; }
.btn-email { background: #FEF3C7; color: #F59E0B; }
.btn-action:hover { opacity: 0.8; transform: scale(1.05); }
.form-question input,
.form-question select {
    padding: 10px 14px;
    border: 2px solid #E0E0E0;
    border-radius: 10px;
    font-size: 14px;
    font-family: 'Nunito', sans-serif;
    margin-bottom: 10px;
    width: 100%;
    outline: none;
    color: #2D2D2D;
}
.btn-ajouter {
    background: linear-gradient(135deg, #1B6CA8, #0D4A7A);
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 20px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    font-family: 'Nunito', sans-serif;
    transition: all 0.3s;
    width: 100%;
}
.btn-ajouter:hover { transform: translateY(-2px); }
.message-admin {
    background: #F8F9FA;
    border-radius: 12px;
    padding: 15px;
    margin-bottom: 10px;
    border-left: 4px solid #1B6CA8;
}
.alerte-inactif {
    background: #FEF9E7;
    border-radius: 12px;
    padding: 12px 15px;
    margin-bottom: 8px;
    border-left: 4px solid #F59E0B;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}
</style>

<main class="dashboard">

    <h1 class="dash-titre">⚙️ Tableau de bord</h1>
    <p class="dash-sous-titre">Bienvenue, <strong><?= htmlspecialchars($_SESSION['pseudo']) ?></strong> !</p>

    <?php if($succes): ?>
        <p style="background:#D1FAE5; color:#10B981; padding:12px; border-radius:10px; margin-bottom:20px; font-weight:700;">
            <?= $succes ?>
        </p>
    <?php endif; ?>

    <!-- Stats rapides -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="nombre" style="color:#1B6CA8;"><?= $total_users ?></div>
            <div class="label">👥 Utilisateurs</div>
        </div>
        <div class="stat-card">
            <div class="nombre" style="color:#10B981;"><?= $total_questions ?></div>
            <div class="label">❓ Questions</div>
        </div>
        <div class="stat-card">
            <div class="nombre" style="color:#F59E0B;"><?= $total_messages ?></div>
            <div class="label">📩 Messages non lus</div>
        </div>
        <div class="stat-card">
            <div class="nombre" style="color:#8E44AD;"><?= $total_reponses ?></div>
            <div class="label">🎯 Réponses données</div>
        </div>
        <div class="stat-card">
            <div class="nombre" style="color:#E74C3C;"><?= count($inactifs) ?></div>
            <div class="label">😴 Comptes inactifs</div>
        </div>
    </div>

    <!-- Messages non lus -->
    <div class="section-admin">
        <h2>📩 Messages non lus (<?= count($messages) ?>)</h2>
        <?php if(empty($messages)): ?>
            <p style="color:#888;">Aucun nouveau message 😊</p>
        <?php else: ?>
            <?php foreach($messages as $msg): ?>
                <div class="message-admin">
                    <strong><?= htmlspecialchars($msg['pseudo']) ?></strong>
                    <small style="color:#888;"><?= $msg['date_envoi'] ?></small>
                    <p style="margin-top:8px;"><?= htmlspecialchars($msg['contenu']) ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Comptes inactifs -->
    <div class="section-admin">
        <h2>😴 Comptes inactifs depuis +30 jours (<?= count($inactifs) ?>)</h2>
        <?php if(empty($inactifs)): ?>
            <p style="color:#888;">Aucun compte inactif 😊</p>
        <?php else: ?>
            <?php foreach($inactifs as $u): ?>
                <div class="alerte-inactif">
                    <div>
                        <strong><?= htmlspecialchars($u['pseudo']) ?></strong>
                        <small style="color:#888;">
                            Dernière connexion : <?= $u['derniere_connexion'] ?? 'Jamais' ?>
                        </small>
                    </div>
                    <div>
                        <a href="?email_inactif=<?= $u['id'] ?>"
                           class="btn-action btn-email">
                           📧 Envoyer email
                        </a>
                        <a href="?bloquer=<?= $u['id'] ?>"
                           class="btn-action btn-bloquer">
                           🔒 Bloquer
                        </a>
                        <a href="?supprimer=<?= $u['id'] ?>"
                           class="btn-action btn-supprimer"
                           onclick="return confirm('Supprimer ce compte ?')">
                           🗑️ Supprimer
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Gestion utilisateurs -->
    <div class="section-admin">
        <h2>👥 Tous les utilisateurs (<?= count($users) ?>)</h2>
        <?php foreach($users as $u): ?>
            <div class="user-ligne">
                <div class="user-info">
                    <strong><?= htmlspecialchars($u['pseudo']) ?></strong>
                    <small>
                        <?= htmlspecialchars($u['email']) ?> —
                        <?= $u['tentatives'] ?> tentative(s) échouée(s)
                    </small>
                </div>
                <div style="display:flex; gap:5px; align-items:center; flex-wrap:wrap;">
                    <span class="badge-role role-<?= $u['role'] ?>">
                        <?= $u['role'] ?>
                    </span>
                    <?php if($u['role'] !== 'admin'): ?>
                        <a href="?email_inactif=<?= $u['id'] ?>"
                           class="btn-action btn-email">
                           📧 Email
                        </a>
                        <?php if($u['role'] === 'bloque'): ?>
                            <a href="?debloquer=<?= $u['id'] ?>"
                               class="btn-action btn-debloquer">
                               🔓 Débloquer
                            </a>
                        <?php else: ?>
                            <a href="?bloquer=<?= $u['id'] ?>"
                               class="btn-action btn-bloquer">
                               🔒 Bloquer
                            </a>
                        <?php endif; ?>
                        <a href="?supprimer=<?= $u['id'] ?>"
                           class="btn-action btn-supprimer"
                           onclick="return confirm('Supprimer ce compte définitivement ?')">
                           🗑️ Supprimer
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Gestion quiz -->
    <div class="section-admin">
        <h2>❓ Gestion des questions (<?= $total_questions ?>)</h2>

        <details style="margin-bottom:20px;">
            <summary style="cursor:pointer; color:#1B6CA8; font-weight:700; padding:10px 0;">
                ➕ Ajouter une nouvelle question
            </summary>
            <form method="POST" class="form-question" style="margin-top:15px;">
                <select name="pays_id" required>
                    <option value="">Choisir un pays...</option>
                    <?php foreach($pays_liste as $p): ?>
                        <option value="<?= $p['id'] ?>">
                            <?= htmlspecialchars($p['nom_pays']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <input type="text" name="question" placeholder="La question..." required>
                <input type="text" name="bonne_reponse" placeholder="✅ Bonne réponse" required>
                <input type="text" name="fausse1" placeholder="❌ Fausse réponse 1" required>
                <input type="text" name="fausse2" placeholder="❌ Fausse réponse 2" required>
                <button type="submit" name="ajouter_question" class="btn-ajouter">
                    ➕ Ajouter la question
                </button>
            </form>
        </details>

        <!-- 20 dernières questions -->
        <p style="color:#888; font-size:13px; margin-bottom:10px;">
            Affichage des 20 dernières questions
        </p>
        <?php foreach($questions as $q): ?>
            <div style="background:#F8F9FA; border-radius:12px; padding:12px 15px; margin-bottom:8px; display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap;">
                <div>
                    <strong style="font-size:14px;">
                        <?= htmlspecialchars($q['question']) ?>
                    </strong>
                    <small style="color:#888; display:block;">
                        ✅ <?= $q['bonne_reponse'] ?> |
                        ❌ <?= $q['fausse_reponse1'] ?> |
                        ❌ <?= $q['fausse_reponse2'] ?>
                    </small>
                </div>
                <a href="?supprimer_question=<?= $q['id'] ?>"
                   class="btn-action btn-supprimer"
                   onclick="return confirm('Supprimer cette question ?')">
                   🗑️
                </a>
            </div>
        <?php endforeach; ?>
    </div>

</main>

<?php require_once '../includes/footer.php'; ?>