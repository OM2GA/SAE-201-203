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

// Récupérer l'ID de l'utilisateur depuis la session
$user_id = $_SESSION['user_id'];

// Préparer et exécuter la requête SQL pour récupérer les informations de l'utilisateur
$sql = "SELECT * FROM utilisateurs WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    // Stocker les informations de l'utilisateur dans des variables
    $prenom = $row['prenom'];
    $nom = $row['nom'];
    $email = $row['email'];
    $adresse = $row['adresse_postale'];
    $naissance = $row['date_naissance'];
    $role = $row['role'];
    $groupe_td = $row['groupe_td'];
    $groupe_tp = $row['groupe_tp'];

    // Calculer l'âge
    $date_naissance = new DateTime($naissance);
    $aujourdhui = new DateTime();
    $age = $aujourdhui->diff($date_naissance)->y;
} else {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas trouvé
    header("Location: connexion.php");
    exit();
}

// Fermer la connexion
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil | Emprunt</title>
    <nk rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
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
                        <li class="breadcrumb-item">
    <a href="<?php
        echo match ($role) {
            'administrateur' => 'accueil_admin.php',
            'enseignant'     => 'accueil_enseignant.php',
            'agent'          => 'accueil_agent.php',
            default          => 'accueil.php'
        };
    ?>">Accueil</a>
</li>

                        <li class="breadcrumb-item active" aria-current="page">Profil</li>
                    </ol>
                </nav>
                <h2>Mon profil</h2>
                <form id="profilForm">
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" class="form-control" id="prenom" value="<?php echo $prenom; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" class="form-control" id="nom" value="<?php echo $nom; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="email">Adresse email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
                    </div>
                    <div class="form-group">
                        <label for="adresse">Adresse postale</label>
                        <input type="text" class="form-control" id="adresse" name="adresse" value="<?php echo $adresse; ?>">
                    </div>
                    <div class="form-group">
                        <label for="naissance">Date de naissance</label>
                        <input type="date" class="form-control" id="naissance" value="<?php echo $naissance; ?>" disabled>
                        <small class="form-text text-muted">Âge: <?php echo $age; ?> ans</small>
                    </div>
                    <div class="form-group">
                        <label for="role">Rôle</label>
                        <input type="text" class="form-control" id="role" value="<?php echo $role; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="groupe_td">Groupe de TD</label>
                        <input type="text" class="form-control" id="groupe_td" value="<?php echo $groupe_td; ?>" disabled>
                    </div>
                    <div class="form-group">
                        <label for="groupe_tp">Groupe de TP</label>
                        <input type="text" class="form-control" id="groupe_tp" value="<?php echo $groupe_tp; ?>" disabled>
                    </div>
                    <button type="button" id="updateProfile" class="btn btn-primary btn-block mt-4">Mettre à jour mon profil</button>
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

    <script>
    $(document).ready(function() {
        $('#updateProfile').click(function() {
            var email = $('#email').val();
            var adresse = $('#adresse').val();

            $.ajax({
                url: 'mettre_a_jour_profil.php',
                type: 'POST',
                data: {
                    email: email,
                    adresse: adresse
                },
                success: function(response) {
                    alert('Profil mis à jour avec succès!');
                },
                error: function(xhr, status, error) {
                    alert('Erreur lors de la mise à jour du profil.');
                }
            });
        });
    });
    </script>
</body>
</html>
