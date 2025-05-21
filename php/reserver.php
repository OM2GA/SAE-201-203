<?php
session_start();
if (!isset($_SESSION['user_id'])) {
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

$message = "";

// Récupération du matériel
$materiels = $conn->query("SELECT id, designation FROM materiels");

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_SESSION['user_id'];
    $materiel_id = $_POST['materiel'];
    $date = $_POST['date'];
    $horaire = $_POST['horaire'];
    $motif = $_POST['motif'];
    $etudiants = $_POST['etudiants'];
    $commentaire = $_POST['commentaire'];

    if (!empty($materiel_id) && !empty($date) && !empty($horaire) && !empty($motif)) {
        $stmt = $conn->prepare("INSERT INTO reservations (user_id, materiel_id, date, horaire, motif, etudiants, commentaire, statut) VALUES (?, ?, ?, ?, ?, ?, ?, 'en attente')");
        $stmt->bind_param("iisssss", $user_id, $materiel_id, $date, $horaire, $motif, $etudiants, $commentaire);
        
        if ($stmt->execute()) {
            $message = "<div class='alert alert-success'>Votre demande de réservation a été soumise avec succès !</div>";
        } else {
            $message = "<div class='alert alert-danger'>Erreur lors de l'enregistrement de la réservation.</div>";
        }

        $stmt->close();
    } else {
        $message = "<div class='alert alert-warning'>Tous les champs obligatoires doivent être remplis.</div>";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réserver un matériel | Emprunt</title>
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
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-light px-3 py-2 rounded">
                        <li class="breadcrumb-item"><a href="accueil.php">Accueil</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Réserver</li>
                    </ol>
                </nav>

                <h2>Faire une réservation</h2>
                <?= $message ?>

                <form method="POST" action="reserver.php">
                    <div class="form-group">
                        <label for="materiel">Matériel</label>
                        <select class="form-control" id="materiel" name="materiel" required>
                            <option value="">-- Sélectionnez un matériel --</option>
                            <?php while ($row = $materiels->fetch_assoc()): ?>
                                <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['designation']) ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="date">Date de réservation</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                    </div>

                    <div class="form-group">
                        <label for="horaire">Créneau horaire</label>
                        <select class="form-control" id="horaire" name="horaire" required>
                            <option value="">-- Choisissez un créneau --</option>
                            <option value="08h30 - 10h30">08h30 - 10h30</option>
                            <option value="10h45 - 12h45">10h45 - 12h45</option>
                            <option value="13h45 - 15h45">13h45 - 15h45</option>
                            <option value="16h - 18h">16h - 18h</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="motif">Motif</label>
                        <textarea class="form-control" id="motif" name="motif" rows="3" placeholder="Indiquez le but de votre réservation" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="etudiants">Autres étudiants concernés</label>
                        <input type="text" class="form-control" id="etudiants" name="etudiants" placeholder="Ex: prénom nom, prénom nom...">
                    </div>

                    <div class="form-group">
                        <label for="commentaire">Commentaires</label>
                        <textarea class="form-control" id="commentaire" name="commentaire" rows="2" placeholder="Commentaires additionnels (optionnel)"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Soumettre la demande</button>
                </form>
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="footer-section">
            <h4>Qui sommes-nous ?</h4>
            <a href="https://www.univ-gustave-eiffel.fr/">Université Gustave Eiffel</a>
            <a href="https://www.univ-gustave-eiffel.fr/formation/des-pedagogies-innovantes/">CIPEN</a>
        </div>
        <div class="footer-section">
            <h4>Support</h4>
            <a href="#">FAQs</a>
            <a href="#">Mentions légales</a>
        </div>
        <div class="footer-section">
            <h4>Contact</h4>
            <p>01 60 95 72 54<br>cipen@univ-eiffel.fr</p>
        </div>
        <div class="footer-section">
            <h4>Suivez-nous</h4>
            <div class="social-media">
                <!-- Ajoute tes icônes ici -->
            </div>
        </div>
    </div>
</body>
</html>

<?php $conn->close(); ?>