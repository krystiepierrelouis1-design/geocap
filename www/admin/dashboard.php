<?php
session_start();
require_once '../includes/db.php';
if(!isset($_SESSION['user_id']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'professeur')){
    header("Location: ../accueil.php"); exit;
}
$succes = "";
if(isset($_GET['bloquer'])){ $id=(int)$_GET['bloquer']; $pdo->prepare("UPDATE utilisateurs SET role='bloque' WHERE id=?")->execute([$id]); $succes="Compte bloqué !"; }
if(isset($_GET['debloquer'])){ $id=(int)$_GET['debloquer']; $pdo->prepare("UPDATE utilisateurs SET role='enfant', tentatives=0 WHERE id=?")->execute([$id]); $succes="Compte débloqué !"; }
if(isset($_GET['supprimer'])){ $id=(int)$_GET['supprimer']; $pdo->prepare("DELETE FROM scores WHERE utilisateur_id=?")->execute([$id]); $pdo->prepare("DELETE FROM reponses WHERE utilisateur_id=?")->execute([$id]); $pdo->prepare("DELETE FROM messages WHERE expediteur_id=?")->execute([$id]); $pdo->prepare("DELETE FROM utilisateurs WHERE id=?")->execute([$id]); $succes="Compte supprimé !"; }
if(isset($_GET['email_inactif'])){ $id=(int)$_GET['email_inactif']; $u=$pdo->prepare("SELECT * FROM utilisateurs WHERE id=?"); $u->execute([$id]); $ui=$u->fetch(); mail($ui['email'],"GEOCAP - Tu nous manques !","Bonjour ".$ui['prenom'].", reviens sur GEOCAP !"); $succes="Email envoyé !"; }
if(isset($_POST['ajouter_question'])){ $pdo->prepare("INSERT INTO quiz (pays_id,question,bonne_reponse,fausse_reponse1,fausse_reponse2) VALUES (?,?,?,?,?)")->execute([(int)$_POST['pays_id'],htmlspecialchars($_POST['question']),htmlspecialchars($_POST['bonne_reponse']),htmlspecialchars($_POST['fausse1']),htmlspecialchars($_POST['fausse2'])]); $succes="Question ajoutée !"; }
if(isset($_GET['supprimer_question'])){ $pdo->prepare("DELETE FROM quiz WHERE id=?")->execute([(int)$_GET['supprimer_question']]); $succes="Question supprimée !"; }

$users = $pdo->query("SELECT * FROM utilisateurs ORDER BY id DESC")->fetchAll();
$messages = $pdo->query("SELECT M.*, U.pseudo, U.avatar FROM messages M JOIN utilisateurs U ON M.expediteur_id=U.id WHERE M.lu=0 ORDER BY M.date_envoi DESC")->fetchAll();
$questions = $pdo->query("SELECT Q.*, P.nom_pays FROM quiz Q JOIN pays P ON Q.pays_id=P.id ORDER BY Q.id DESC LIMIT 20")->fetchAll();
$pays_liste = $pdo->query("SELECT * FROM pays ORDER BY nom_pays")->fetchAll();
$inactifs = $pdo->query("SELECT * FROM utilisateurs WHERE derniere_connexion < DATE_SUB(NOW(), INTERVAL 30 DAY) AND role='enfant'")->fetchAll();
?>
<?php require_once '../includes/header.php'; ?>

<div class="hero">
    <h1>⚙️ Tableau de bord</h1>
    <p>Bienvenue, <?= htmlspecialchars($_SESSION['pseudo']) ?> !</p>
</div>

<div style="max-width:900px; margin:30px auto; padding:20px;">

    <?php if($succes): ?><p class="succes"><?= $succes ?></p><?php endif; ?>

    <div style="display:flex; gap:15px; flex-wrap:wrap; margin-bottom:25px;">
        <div class="carte-style" style="flex:1; text-align:center; min-width:120px;">
            <p style="font-size:28px; font-weight:800; color:#1B6CA8;"><?= count($users) ?></p>
            <p style="color:#888; font-size:13px;">Utilisateurs</p>
        </div>
        <div class="carte-style" style="flex:1; text-align:center; min-width:120px;">
            <p style="font-size:28px; font-weight:800; color:#10B981;"><?= count($messages) ?></p>
            <p style="color:#888; font-size:13px;">Messages non lus</p>
        </div>
        <div class="carte-style" style="flex:1; text-align:center; min-width:120px;">
            <p style="font-size:28px; font-weight:800; color:#EF4444;"><?= count($inactifs) ?></p>
            <p style="color:#888; font-size:13px;">Comptes inactifs</p>
        </div>
    </div>

    <div class="carte-style">
        <h2 style="margin-bottom:15px;">📩 Messages non lus</h2>
        <?php if(empty($messages)): ?><p style="color:#888;">Aucun message</p>
        <?php else: foreach($messages as $msg): ?>
            <div style="background:#f5f5f5; padding:12px; border-radius:10px; margin-bottom:8px; border-left:4px solid #1B6CA8;">
                <strong><?= $msg['avatar'] ?? '🌍' ?> <?= htmlspecialchars($msg['pseudo']) ?></strong>
                <small style="color:#888;"> — <?= $msg['date_envoi'] ?></small>
                <p style="margin-top:5px;"><?= htmlspecialchars($msg['contenu']) ?></p>
            </div>
        <?php endforeach; endif; ?>
    </div>

    <div class="carte-style">
        <h2 style="margin-bottom:15px;">😴 Comptes inactifs +30 jours</h2>
        <?php if(empty($inactifs)): ?><p style="color:#888;">Aucun compte inactif</p>
        <?php else: foreach($inactifs as $u): ?>
            <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-bottom:1px solid #f5f5f5; gap:10px;">
                <div>
                    <strong><?= $u['avatar'] ?? '🌍' ?> <?= htmlspecialchars($u['pseudo']) ?></strong>
                    <small style="color:#888; display:block;">Dernière connexion : <?= $u['derniere_connexion'] ?></small>
                </div>
                <div style="display:flex; gap:5px;">
                    <a href="?email_inactif=<?= $u['id'] ?>" style="background:#FEF3C7; color:#F59E0B; padding:5px 10px; border-radius:8px; text-decoration:none; font-size:13px; font-weight:700;">📧 Email</a>
                    <a href="?bloquer=<?= $u['id'] ?>" style="background:#FEE2E2; color:#EF4444; padding:5px 10px; border-radius:8px; text-decoration:none; font-size:13px; font-weight:700;">🔒 Bloquer</a>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>

    <div class="carte-style">
        <h2 style="margin-bottom:15px;">👥 Tous les utilisateurs</h2>
        <?php foreach($users as $u): ?>
            <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-bottom:1px solid #f5f5f5; flex-wrap:wrap; gap:8px;">
                <div>
                    <strong><?= $u['avatar'] ?? '🌍' ?> <?= htmlspecialchars($u['pseudo']) ?></strong>
                    <small style="color:#888; display:block;"><?= htmlspecialchars($u['email']) ?> — <?= $u['role'] ?></small>
                </div>
                <?php if($u['role'] != 'admin'): ?>
                <div style="display:flex; gap:5px;">
                    <?php if($u['role'] == 'bloque'): ?>
                        <a href="?debloquer=<?= $u['id'] ?>" style="background:#D1FAE5; color:#10B981; padding:5px 10px; border-radius:8px; text-decoration:none; font-size:13px; font-weight:700;">🔓 Débloquer</a>
                    <?php else: ?>
                        <a href="?bloquer=<?= $u['id'] ?>" style="background:#FEE2E2; color:#EF4444; padding:5px 10px; border-radius:8px; text-decoration:none; font-size:13px; font-weight:700;">🔒 Bloquer</a>
                    <?php endif; ?>
                    <a href="?supprimer=<?= $u['id'] ?>" style="background:#FEE2E2; color:#EF4444; padding:5px 10px; border-radius:8px; text-decoration:none; font-size:13px; font-weight:700;" onclick="return confirm('Supprimer ?')">🗑️</a>
                </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="carte-style">
        <h2 style="margin-bottom:15px;">❓ Ajouter une question</h2>
        <form method="POST">
            <select name="pays_id" style="width:100%; padding:10px; margin-bottom:10px; border:2px solid #e0e0e0; border-radius:10px; font-family:'Nunito',sans-serif;" required>
                <option value="">Choisir un pays...</option>
                <?php foreach($pays_liste as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nom_pays']) ?></option>
                <?php endforeach; ?>
            </select>
            <input type="text" name="question" placeholder="La question" class="champ" required>
            <input type="text" name="bonne_reponse" placeholder="✅ Bonne réponse" class="champ" required>
            <input type="text" name="fausse1" placeholder="❌ Fausse réponse 1" class="champ" required>
            <input type="text" name="fausse2" placeholder="❌ Fausse réponse 2" class="champ" required>
            <button type="submit" name="ajouter_question" class="bouton" style="width:100%;">➕ Ajouter</button>
        </form>
        <h3 style="margin:20px 0 10px;">Dernières questions</h3>
        <?php foreach($questions as $q): ?>
            <div style="background:#f5f5f5; padding:10px; border-radius:8px; margin-bottom:8px; display:flex; justify-content:space-between; align-items:center; gap:10px;">
                <div>
                    <strong style="font-size:14px;"><?= htmlspecialchars($q['question']) ?></strong>
                    <small style="color:#888; display:block;">✅ <?= $q['bonne_reponse'] ?></small>
                </div>
                <a href="?supprimer_question=<?= $q['id'] ?>" style="color:#EF4444; font-size:13px; text-decoration:none;" onclick="return confirm('Supprimer ?')">🗑️</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>