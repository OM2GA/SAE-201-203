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

$sql = "SELECT r.*, u.nom, u.prenom, m.designation 
        FROM reservations r
        JOIN utilisateurs u ON r.user_id = u.id
        JOIN materiels m ON r.materiel_id = m.id
        ORDER BY r.date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des réservations | Emprunt</title>
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
                <h2 class="mb-4">Gestion des réservations</h2>

                <?php if ($result->num_rows === 0): ?>
                    <div class="alert alert-info text-center">
                        Aucune réservation n'a été effectuée pour le moment.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="thead-light">
                                <tr>
                                    <th>Étudiant</th>
                                    <th>Matériel</th>
                                    <th>Date</th>
                                    <th>Créneau</th>
                                    <th>Motif</th>
                                    <th>État</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['prenom'] . " " . $row['nom']) ?></td>
                                        <td><?= htmlspecialchars($row['designation']) ?></td>
                                        <td><?= htmlspecialchars($row['date']) ?></td>
                                        <td><?= htmlspecialchars($row['horaire']) ?></td>
                                        <td><?= nl2br(htmlspecialchars($row['motif'])) ?></td>
                                        <td>
                                            <?php
                                                $statut = $row['statut'];
                                                $class = $statut === 'validée' ? 'text-success' : ($statut === 'refusée' ? 'text-danger' : 'text-warning');
                                                echo "<span class='$class'>" . ucfirst($statut) . "</span>";
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($statut === 'en attente'): ?>
                                                <form method="POST" action="valider_reservation.php" class="form-inline">
                                                    <input type="hidden" name="reservation_id" value="<?= $row['id'] ?>">
                                                    <input type="text" name="commentaire_admin" class="form-control mb-2 mr-2" placeholder="Commentaire">
                                                    <button type="submit" name="action" value="valider" class="btn btn-success btn-sm mr-2">Valider</button>
                                                    <button type="submit" name="action" value="refuser" class="btn btn-danger btn-sm">Refuser</button>
                                                </form>
                                            <?php else: ?>
                                                <?= nl2br(htmlspecialchars($row['commentaire_admin'])) ?>
                                            <?php endif; ?>
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
