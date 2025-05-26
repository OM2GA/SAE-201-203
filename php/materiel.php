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

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérer les informations du matériel depuis la base de données
$sql = "SELECT * FROM materiels";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matériel disponible | Emprunt</title>
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <img src="../images/logo_universite.png" alt="Logo Université">
            <form action="deconnexion.php" method="post" style="position: absolute; top: 10px; right: 10px;">
        <button type="submit" class="btn btn-danger">Déconnexion</button>
    </form>
    </div>

    <!-- MAIN -->
    <div class="main-container">
        <div class="container">
            <div class="login-container">

                <!-- FIL D'ARIANE -->
                <nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-light px-3 py-2 rounded">
        <li class="breadcrumb-item"><a href="<?= $accueil ?>">Accueil</a></li>
        <li class="breadcrumb-item active" aria-current="page">Materiel</li>
    </ol>
</nav>


                <h2>Matériel disponible</h2>

                <div class="row mt-4">
                    <?php
                    if ($result->num_rows > 0) {
                        // Afficher chaque matériel
                        while($row = $result->fetch_assoc()) {
                            echo '<div class="col-md-6 mb-4">';
                            echo '<div class="card">';
                            echo '<img src="../images/' . $row['photo'] . '" class="card-img-top" alt="' . $row['designation'] . '">';
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title">' . $row['designation'] . '</h5>';
                            echo '<p class="card-text">' . $row['descriptif'] . '</p>';
                            echo '<a href="reserver.php" class="btn btn-primary">Réserver</a>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo "Aucun matériel disponible.";
                    }
                    ?>
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

<?php
// Fermer la connexion
$conn->close();
?>
