<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "prêt materiel";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $motDePasse = $_POST['motDePasse'];

    $sql = "SELECT id, nom, prenom, mot_de_passe, role FROM utilisateurs WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $utilisateur = $result->fetch_assoc();

        if (password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
            // Connexion réussie → Stockage en session
            $_SESSION['user_id'] = $utilisateur['id'];
            $_SESSION['user_role'] = $utilisateur['role'];
            $_SESSION['user_nom'] = $utilisateur['nom'];
            $_SESSION['user_prenom'] = $utilisateur['prenom'];

            // Redirection selon le rôle
            switch ($utilisateur['role']) {
                case 'administrateur':
                    header("Location: accueil_admin.php");
                    break;
                case 'enseignant':
                    header("Location: accueil_enseignant.php");
                    break;
                case 'agent':
                    header("Location: accueil_agent.php");
                    break;
                case 'étudiant':
                default:
                    header("Location: accueil.php");
                    break;
            }
            exit();
        } else {
            $error = "Mot de passe incorrect.";
        }
    } else {
        $error = "Aucun utilisateur trouvé avec cet email.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Se connecter | Emprunt</title>
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="../images/logo_universite.png" alt="Logo Université">
    </div>
    <div class="main-container">
        <div class="container">
            <div class="login-container">
                <h2>Connexion</h2>
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <form method="POST" action="connexion.php">
                    <div class="form-group">
                        <label for="email">Adresse email</label>
                        <input type="email" class="form-control" name="email" id="email" required placeholder="Ex : jean.dupont@univ.fr">
                    </div>
                    <div class="form-group password-container">
                        <label for="motDePasse">Mot de passe</label>
                        <input type="password" class="form-control" name="motDePasse" id="motDePasse" required placeholder="********">
                        <span class="toggle-password" onclick="togglePasswordVisibility()">👁️</span>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
                </form>
                <div class="forgot-password mt-3 text-center">
                    Pas encore de compte ? <a href="inscription.php">Inscrivez-vous</a>
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
    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("motDePasse");
            var toggleIcon = document.querySelector(".toggle-password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.textContent = "👁️";
            } else {
                passwordInput.type = "password";
                toggleIcon.textContent = "👁️";
            }
        }
    </script>
</body>
</html>
