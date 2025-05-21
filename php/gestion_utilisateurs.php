<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'administrateur') {
    header("Location: connexion.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "prêt materiel";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

$success = "";
$error = "";

// Traitement changement de rôle
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['changer_role'])) {
    $user_id = intval($_POST['user_id']);
    $nouveau_role = $_POST['nouveau_role'];

    $stmt = $conn->prepare("UPDATE utilisateurs SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $nouveau_role, $user_id);
    if ($stmt->execute()) {
        $success = "Rôle mis à jour avec succès.";
    } else {
        $error = "Erreur lors de la mise à jour du rôle.";
    }
    $stmt->close();
}

// Traitement suppression utilisateur
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['supprimer_utilisateur'])) {
    $user_id = intval($_POST['user_id']);

    // Empêcher la suppression de soi-même
    if ($user_id == $_SESSION['user_id']) {
        $error = "Vous ne pouvez pas supprimer votre propre compte administrateur.";
    } else {
        $stmt = $conn->prepare("DELETE FROM utilisateurs WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            $success = "Utilisateur supprimé avec succès.";
        } else {
            $error = "Erreur lors de la suppression.";
        }
        $stmt->close();
    }
}

// Récupération des utilisateurs
$utilisateurs = $conn->query("SELECT * FROM utilisateurs ORDER BY role, nom");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des utilisateurs | Emprunt</title>
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="header">
        <img src="../images/logo_universite.png" alt="Logo Université">
    </div>

    <div class="main-container">
        <div class="container">
            <div class="login-container">
                <h2 class="mb-4">Gestion des utilisateurs</h2>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php elseif ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <?php if ($utilisateurs->num_rows === 0): ?>
                    <div class="alert alert-info">Aucun utilisateur inscrit pour le moment.</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Email</th>
                                    <th>Rôle</th>
                                    <th>Groupe TD</th>
                                    <th>Groupe TP</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($user = $utilisateurs->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['nom']) ?></td>
                                        <td><?= htmlspecialchars($user['prenom']) ?></td>
                                        <td><?= htmlspecialchars($user['email']) ?></td>
                                        <td>
                                            <form method="POST" class="form-inline">
                                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                <select name="nouveau_role" class="form-control mr-2">
                                                    <option <?= $user['role'] == 'étudiant' ? 'selected' : '' ?>>étudiant</option>
                                                    <option <?= $user['role'] == 'enseignant' ? 'selected' : '' ?>>enseignant</option>
                                                    <option <?= $user['role'] == 'agent' ? 'selected' : '' ?>>agent</option>
                                                    <option <?= $user['role'] == 'administrateur' ? 'selected' : '' ?>>administrateur</option>
                                                </select>
                                                <button type="submit" name="changer_role" class="btn btn-sm btn-primary">Modifier</button>
                                            </form>
                                        </td>
                                        <td><?= htmlspecialchars($user['groupe_td'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($user['groupe_tp'] ?? '-') ?></td>
                                        <td>
                                            <form method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                                <button type="submit" name="supprimer_utilisateur" class="btn btn-sm btn-danger">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <div class="footer">
        <div class="footer-section">
            <h4>Qui sommes-nous ?</h4>
            <a href="https://www.univ-gustave-eiffel.fr/">Université Gustave Eiffel</a>
            <a href="https://www.univ-gustave-eiffel.fr/formation/des-pedagogies-innovantes/">Centre d'Innovation Pédagogique et Numérique (CIPEN)</a>
        </div>
        <div class="footer-section">
            <h4>Support</h4>
            <a href="http://www.u-pem.fr/campus-numerique-ip/assistance/?tx_ttnews%5Bcat%5D=99&cHash=c936e533f67cbaaddda01752716910d3">FAQs</a>
            <a href="http://www.u-pem.fr/universite/mentions-legales/">Privacy</a>
        </div>
        <div class="footer-section">
            <h4>Restons en contact</h4>
            <p>Vous pouvez nous contacter au 01 60 95 72 54, du lundi au vendredi de 9h à 17h ou par courriel</p>
            <p>cipen@univ-eiffel.fr</p>
        </div>
        <div class="footer-section">
            <h4>Suivez-nous</h4>
            <div class="social-media">
                <a href="https://www.facebook.com/UniversiteGustaveEiffel/"><img src="../images/facebook.png" alt="Facebook"></a>
                <a href="https://twitter.com/UGustaveEiffel"><img src="../images/twitter.png" alt="Twitter"></a>
                <a href="https://www.linkedin.com/company/universit%C3%A9-gustave-eiffel/"><img src="../images/linkedin.png" alt="LinkedIn"></a>
                <a href="https://www.instagram.com/universitegustaveeiffel/"><img src="../images/instagram.png" alt="Instagram"></a>
                <a href="https://www.youtube.com/channel/UCNMF04xs6lEAeFZ8TO6s2dw"><img src="../images/youtube.png" alt="YouTube"></a>
            </div>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>
