<?php
session_start(); // Démarrer la session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "prêt materiel";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: connexion.php");
    exit();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $materiel = $_POST['materiel'];
    $date = $_POST['date'];
    $horaire = $_POST['horaire'];
    $motif = $_POST['motif'];
    $etudiants = $_POST['etudiants'];
    $commentaire = $_POST['commentaire'];

    // Préparer et exécuter la requête SQL pour insérer la réservation
    $sql = "INSERT INTO reservations (user_id, materiel, date, horaire, motif, etudiants, commentaire) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssss", $user_id, $materiel, $date, $horaire, $motif, $etudiants, $commentaire);

    if ($stmt->execute()) {
        echo "Réservation réussie!";
    } else {
        echo "Erreur: " . $stmt->error;
    }

    // Fermer la connexion
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réserver un matériel | Emprunt</title>
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <img src="../images/logo_universite.png" alt="Logo Université">
    </div>
    <!-- MAIN -->
    <div class="main-container">
        <div class="container">
            <div class="login-container">
                <!-- FIL D'ARIANE -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-light px-3 py-2 rounded">
                        <li class="breadcrumb-item"><a href="accueil.php">Accueil</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Réserver</li>
                    </ol>
                </nav>
                <h2>Faire une réservation</h2>
                <form method="POST" action="reserver.php">
                    <div class="form-group">
                        <label for="materiel">Matériel</label>
                        <select class="form-control" id="materiel" name="materiel" required>
                            <option value="">-- Sélectionnez un matériel --</option>
                            <option value="Casque VR">Casque VR</option>
                            <option value="Caméra 360°">Caméra 360°</option>
                            <option value="PC portable">PC portable</option>
                            <option value="Microphone">Microphone</option>
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
