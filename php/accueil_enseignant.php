<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'enseignant') {
    header("Location: connexion.php");
    exit();
}

$prenom = $_SESSION['user_prenom'] ?? 'Enseignant';
$nom = $_SESSION['user_nom'] ?? '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil Enseignant | Emprunt</title>
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
        <div class="login-container text-center">
            <h2>Bienvenue, <?= htmlspecialchars($prenom . ' ' . $nom) ?> 👨‍🏫</h2>
            <p class="lead mt-3">Vous êtes connecté en tant qu'enseignant.</p>

            <div class="row mt-4">
                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Réserver un matériel ou une salle</h5>
                            <p class="card-text">Planifiez l'utilisation de matériels ou de salles pour vos cours ou projets étudiants.</p>
                            <a href="reserver.php" class="btn btn-primary">Réserver maintenant</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Voir mes réservations</h5>
                            <p class="card-text">Consultez, annulez ou ajoutez un commentaire à vos réservations passées ou à venir.</p>
                            <a href="historique.php" class="btn btn-secondary">Voir l'historique</a>
                        </div>
                    </div>
                </div>
            </div>

            <a href="deconnexion.php" class="btn btn-outline-danger mt-4">Se déconnecter</a>
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
