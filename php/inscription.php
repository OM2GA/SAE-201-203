<?php
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

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $pseudo = $_POST['pseudo'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $date_naissance = $_POST['dateNaissance'];
    $adresse_postale = $_POST['adressePostale'];
    $mot_de_passe = password_hash($_POST['motDePasse'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $groupe_td = isset($_POST['groupe_td']) ? $_POST['groupe_td'] : null;
    $groupe_tp = isset($_POST['groupe_tp']) ? $_POST['groupe_tp'] : null;

    // Vérifier si l'email existe déjà
    $check_sql = "SELECT id FROM utilisateurs WHERE email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // L'email existe déjà
        echo "Erreur: Un compte avec cet email existe déjà.";
    } else {
        // L'email n'existe pas, insérer le nouvel utilisateur
        $sql = "INSERT INTO utilisateurs (pseudo, nom, prenom, email, date_naissance, adresse_postale, mot_de_passe, role, groupe_td, groupe_tp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssis", $pseudo, $nom, $prenom, $email, $date_naissance, $adresse_postale, $mot_de_passe, $role, $groupe_td, $groupe_tp);

        if ($stmt->execute()) {
            echo "Inscription réussie!";
        } else {
            echo "Erreur: " . $stmt->error;
        }

        // Fermer la connexion
        $stmt->close();
    }

    // Fermer la connexion
    $check_stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S'inscrire sur le site | Emprunt</title>
    <link rel="icon" href="../images/favicon.ico" type="image/x-icon">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>
<body>
    <div class="header">
        <img src="../images/logo_universite.png" alt="Logo Université">
    </div>
    <div class="main-container">
        <div class="container">
            <div class="login-container">
                <h2>Inscription</h2>
                <form method="POST" action="inscription.php">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Entrez votre adresse email" required>
                    </div>
                    <div class="form-group">
                        <label for="pseudo">Pseudo</label>
                        <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Entrez votre pseudo" required>
                    </div>
                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrez votre nom" required>
                    </div>
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Entrez votre prénom" required>
                    </div>
                    <div class="form-group">
                        <label for="dateNaissance">Date de naissance</label>
                        <input type="date" class="form-control" id="dateNaissance" name="dateNaissance" required>
                    </div>
                    <div class="form-group">
                        <label for="adressePostale">Adresse postale</label>
                        <input type="text" class="form-control" id="adressePostale" name="adressePostale" placeholder="Entrez votre adresse postale" required>
                    </div>
                    <div class="form-group">
                        <label for="motDePasse">Mot de passe</label>
                        <input type="password" class="form-control" id="motDePasse" name="motDePasse" placeholder="Entrez votre mot de passe (min 6 caractères)" minlength="6" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Rôle</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="">-- Sélectionnez un rôle --</option>
                            <option value="étudiant">Étudiant</option>
                            <option value="enseignant">Enseignant</option>
                            <option value="administrateur">Administrateur</option>
                            <option value="agent">Agent</option>
                        </select>
                    </div>
                    <div class="form-group" id="groupe_td_group">
                        <label for="groupe_td">Groupe de TD</label>
                        <select class="form-control" id="groupe_td" name="groupe_td">
                            <option value="">-- Sélectionnez un groupe de TD --</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>
                    <div class="form-group" id="groupe_tp_group">
                        <label for="groupe_tp">Groupe de TP</label>
                        <select class="form-control" id="groupe_tp" name="groupe_tp">
                            <option value="">-- Sélectionnez un groupe de TP --</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">S'inscrire</button>
                </form>
                <div class="forgot-password">
                    Déjà un compte ? <a href="connexion.php">Connectez-vous</a>
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
    $(document).ready(function() {
        // Masquer les groupes de TD et TP par défaut
        $('#groupe_td_group').hide();
        $('#groupe_tp_group').hide();

        // Afficher les groupes de TD et TP si le rôle est étudiant
        $('#role').change(function() {
            if ($(this).val() === 'étudiant') {
                $('#groupe_td_group').show();
                $('#groupe_tp_group').show();
            } else {
                $('#groupe_td_group').hide();
                $('#groupe_tp_group').hide();
            }
        });

        // Mettre à jour les options de TP en fonction du TD sélectionné
        $('#groupe_td').change(function() {
            var td = $(this).val();
            var tpOptions = {
                '1': ['A', 'B'],
                '2': ['C', 'D'],
                '3': ['E', 'F']
            };

            var tpSelect = $('#groupe_tp');
            tpSelect.empty();
            tpSelect.append('<option value="">-- Sélectionnez un groupe de TP --</option>');

            if (td && tpOptions[td]) {
                $.each(tpOptions[td], function(index, value) {
                    tpSelect.append('<option value="' + value + '">' + value + '</option>');
                });
            }
        });
    });
    </script>
</body>
</html>
