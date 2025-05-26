<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil Admin | Emprunt</title>
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
        <div class="container text-center">
            <div class="login-container">
                <h2>Bienvenue, Administrateur</h2>
                <p class="mt-4">Vous êtes connecté avec succès !</p>

                <div class="row mt-5">
                    <div class="col-md-6 mb-3">
                        <a href="gestion_utilisateurs.php" class="btn btn-primary btn-block">Gérer les Utilisateurs</a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="gestion_materiels.php" class="btn btn-primary btn-block">Gérer les Matériels</a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="gestion_reservations.php" class="btn btn-primary btn-block">Gérer les Réservations</a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="profil.php" class="btn btn-primary btn-block">Mon profil</a>
                    </div>
                </div>
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
