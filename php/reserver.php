<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$role = $_SESSION['user_role'];
$isProf = $role === 'enseignant';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "prêt materiel";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}

$materiels = $conn->query("SELECT id, designation FROM materiels");
$salles = $conn->query("SELECT id, nom_salle FROM salles");

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $date = $_POST['date'];
    $horaire = $_POST['horaire'];
    $motif = $_POST['motif'];
    $commentaire = $_POST['commentaire'];
    $type = $isProf ? $_POST['type_reservation'] : 'materiel';
    $materiel_id = null;
    $salle_id = null;

    if ($type === 'materiel') {
        $materiel_id = !empty($_POST['materiel_id']) ? intval($_POST['materiel_id']) : null;
    } elseif ($type === 'salle') {
        $salle_id = !empty($_POST['salle_id']) ? intval($_POST['salle_id']) : null;
    }

    if (!empty($date) && !empty($horaire) && !empty($motif)) {
        $stmt = $conn->prepare("INSERT INTO reservations (user_id, materiel_id, salle_id, date, horaire, motif, commentaire, statut) VALUES (?, ?, ?, ?, ?, ?, ?, 'en attente')");
        $stmt->bind_param("iiissss", $user_id, $materiel_id, $salle_id, $date, $horaire, $motif, $commentaire);

        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Réservation enregistrée avec succès.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Erreur lors de la soumission.</div>";
        }

        $stmt->close();
    } else {
        $message = "<div class='alert alert-warning'>Veuillez remplir tous les champs obligatoires.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réserver | <?= ucfirst($role) ?></title>
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
                        <li class="breadcrumb-item"><a href="<?= $isProf ? 'accueil_enseignant.php' : 'accueil.php' ?>">Accueil</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Réserver</li>
                    </ol>
                </nav>

                <h2 class="mb-4">Faire une réservation</h2>
                <?= $message ?>

                <form method="POST">
                    <?php if ($isProf): ?>
                        <div class="form-group">
                            <label for="type_reservation">Type de réservation</label>
                            <select name="type_reservation" id="type_reservation" class="form-control" required onchange="toggleReservationType()">
                                <option value="">-- Choisir --</option>
                                <option value="materiel">Matériel</option>
                                <option value="salle">Salle</option>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="form-group" id="materiel_group" style="<?= $isProf ? 'display:none;' : '' ?>">
                        <label for="materiel_id">Matériel</label>
                        <select name="materiel_id" class="form-control">
                            <option value="">-- Aucun --</option>
                            <?php while ($m = $materiels->fetch_assoc()): ?>
                                <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['designation']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group" id="salle_group" style="display:none;">
                        <label for="salle_id">Salle</label>
                        <select name="salle_id" class="form-control">
                            <option value="">-- Choisir une salle --</option>
                            <?php while ($s = $salles->fetch_assoc()): ?>
                                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nom_salle']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" name="date" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="horaire">Créneau</label>
                        <select name="horaire" class="form-control" required>
                            <option value="">-- Choisissez un créneau --</option>
                            <option value="08h30 - 10h30">08h30 - 10h30</option>
                            <option value="10h45 - 12h45">10h45 - 12h45</option>
                            <option value="13h45 - 15h45">13h45 - 15h45</option>
                            <option value="16h - 18h">16h - 18h</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="motif">Motif</label>
                        <textarea name="motif" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="commentaire">Commentaire</label>
                        <textarea name="commentaire" class="form-control" rows="2"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Soumettre</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleReservationType() {
            let type = document.getElementById("type_reservation").value;
            document.getElementById("materiel_group").style.display = (type === "materiel") ? "block" : "none";
            document.getElementById("salle_group").style.display = (type === "salle") ? "block" : "none";
        }
    </script>
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
