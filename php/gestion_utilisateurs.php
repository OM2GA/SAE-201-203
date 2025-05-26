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
    die("Erreur de connexion: " . $conn->connect_error);
}

// Supprimer un utilisateur
if (isset($_GET['delete_id'])) {
    $user_id = intval($_GET['delete_id']);

    // Supprimer d'abord les réservations associées
    $delete_resa = $conn->prepare("DELETE FROM reservations WHERE user_id = ?");
    $delete_resa->bind_param("i", $user_id);
    $delete_resa->execute();

    // Puis supprimer l'utilisateur
    $delete_user = $conn->prepare("DELETE FROM utilisateurs WHERE id = ?");
    $delete_user->bind_param("i", $user_id);
    if ($delete_user->execute()) {
        $message = "Utilisateur supprimé avec succès.";
    } else {
        $message = "Erreur lors de la suppression.";
    }
}

// Récupérer tous les utilisateurs
$sql = "SELECT * FROM utilisateurs ORDER BY id ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des utilisateurs | Admin</title>
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="header">
        <img src="../images/logo_universite.png" alt="Logo Université">
        <form action="deconnexion.php" method="post" style="position: absolute; top: 10px; right: 10px;">
            <button type="submit" class="btn btn-danger">Déconnexion</button>
        </form>
    </div>

    <div class="main-container">
        <div class="container">
            <div class="login-container">
                <!-- FIL D'ARIANE -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-light px-3 py-2 rounded">
                        <li class="breadcrumb-item"><a href="accueil_admin.php">Accueil</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Gestion des utilisateurs</li>
                    </ol>
                </nav>

                <h2 class="mb-4">Gestion des utilisateurs</h2>

                <?php if (isset($message)): ?>
                    <div class="alert alert-info"><?= $message ?></div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>Pseudo</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Email</th>
                                <th>Rôle</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($user = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td><?= htmlspecialchars($user['pseudo']) ?></td>
                                    <td><?= htmlspecialchars($user['nom']) ?></td>
                                    <td><?= htmlspecialchars($user['prenom']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= htmlspecialchars($user['role']) ?></td>
                                    <td>
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <a href="?delete_id=<?= $user['id'] ?>" onclick="return confirm('Supprimer cet utilisateur ?');" class="btn btn-sm btn-danger">Supprimer</a>
                                        <?php else: ?>
                                            <span class="text-muted">Moi</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <!-- FOOTER -->
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
