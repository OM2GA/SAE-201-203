<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$role = $_SESSION['user_role'] ?? '';
$accueil = match ($role) {
    'administrateur' => 'accueil_admin.php',
    'enseignant'     => 'accueil_enseignant.php',
    'agent'          => 'accueil_agent.php',
    default          => 'accueil.php'
};

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "prêt materiel";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT r.*, 
               m.designation AS designation_materiel, 
               s.nom_salle AS designation_salle
        FROM reservations r
        LEFT JOIN materiels m ON r.materiel_id = m.id
        LEFT JOIN salles s ON r.salle_id = s.id
        WHERE r.user_id = ?
        ORDER BY r.date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon historique | Emprunt</title>
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
            <nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light px-3 py-2 rounded">
        <li class="breadcrumb-item"><a href="<?= $accueil ?>">Accueil</a></li>
        <li class="breadcrumb-item active" aria-current="page">Historique</li>
    </ol>
</nav>


            <h2>Historique de mes réservations</h2>

            <?php if ($result->num_rows === 0): ?>
                <div class="alert alert-info mt-4 text-center">
                    Vous n'avez encore effectué aucune réservation.
                </div>
            <?php else: ?>
                <div class="table-responsive mt-4">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="thead-light">
                        <tr>
                            <th>Date</th>
                            <th>Créneau</th>
                            <th>Type</th>
                            <th>État</th>
                            <th>Motif</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['date']) ?></td>
                                <td><?= htmlspecialchars($row['horaire']) ?></td>
                                <td>
                                    <?php
                                    if (!empty($row['designation_materiel'])) {
                                        echo "Matériel : " . htmlspecialchars($row['designation_materiel']);
                                    } elseif (!empty($row['designation_salle'])) {
                                        echo "Salle : " . htmlspecialchars($row['designation_salle']);
                                    } else {
                                        echo "Non précisé";
                                    }
                                    ?>
                                </td>
                                <td class="<?= $row['statut'] === 'validée' ? 'text-success' : 
                                    ($row['statut'] === 'refusée' ? 'text-danger' : 'text-warning') ?>">
                                    <?= ucfirst($row['statut']) ?>
                                </td>
                                <td><?= nl2br(htmlspecialchars($row['motif'])) ?></td>
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
</div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
