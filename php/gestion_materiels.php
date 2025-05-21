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
    die("Erreur de connexion : " . $conn->connect_error);
}

$success = "";
$error = "";

// Ajouter un matériel
if (isset($_POST['ajouter'])) {
    $reference = $_POST['reference'];
    $designation = $_POST['designation'];
    $photo = $_POST['photo'];
    $type = $_POST['type'];
    $date_achat = $_POST['date_achat'];
    $etat = $_POST['etat'];
    $quantite = intval($_POST['quantite']);
    $descriptif = $_POST['descriptif'];
    $lien = $_POST['lien'];

    $stmt = $conn->prepare("INSERT INTO materiels (reference, designation, photo, type, date_achat, etat, quantite, descriptif, lien_demonstration) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssiss", $reference, $designation, $photo, $type, $date_achat, $etat, $quantite, $descriptif, $lien);
    if ($stmt->execute()) {
        $success = "Matériel ajouté avec succès.";
    } else {
        $error = "Erreur lors de l'ajout.";
    }
    $stmt->close();
}

// Supprimer un matériel
if (isset($_POST['supprimer'])) {
    $id = intval($_POST['materiel_id']);
    $stmt = $conn->prepare("DELETE FROM materiels WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $success = "Matériel supprimé.";
    } else {
        $error = "Erreur lors de la suppression.";
    }
    $stmt->close();
}

// Récupération des matériels
$materiels = $conn->query("SELECT * FROM materiels ORDER BY designation ASC");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des matériels | Emprunt</title>
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
            <h2 class="mb-4">Gestion des matériels</h2>

            <?php if ($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php elseif ($error): ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <h4>Ajouter un nouveau matériel</h4>
            <form method="POST" class="mb-4">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>Référence</label>
                        <input type="text" class="form-control" name="reference" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Désignation</label>
                        <input type="text" class="form-control" name="designation" required>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Photo (nom du fichier)</label>
                        <input type="text" class="form-control" name="photo">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label>Type</label>
                        <input type="text" class="form-control" name="type" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Date d'achat</label>
                        <input type="date" class="form-control" name="date_achat" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label>État</label>
                        <select class="form-control" name="etat">
                            <option>Très bon</option>
                            <option>Bon</option>
                            <option>En panne</option>
                            <option>En réparation</option>
                        </select>
                    </div>
                    <div class="form-group col-md-3">
                        <label>Quantité</label>
                        <input type="number" class="form-control" name="quantite" min="1" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Descriptif</label>
                    <textarea class="form-control" name="descriptif" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label>Lien démonstration (YouTube ou autre)</label>
                    <input type="url" class="form-control" name="lien">
                </div>
                <button type="submit" name="ajouter" class="btn btn-success">Ajouter</button>
            </form>

            <h4>Liste des matériels</h4>
            <?php if ($materiels->num_rows === 0): ?>
                <div class="alert alert-info">Aucun matériel enregistré.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-light">
                        <tr>
                            <th>Désignation</th>
                            <th>Type</th>
                            <th>État</th>
                            <th>Quantité</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = $materiels->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['designation']) ?></td>
                                <td><?= htmlspecialchars($row['type']) ?></td>
                                <td><?= htmlspecialchars($row['etat']) ?></td>
                                <td><?= $row['quantite'] ?></td>
                                <td>
                                    <!-- Suppression simple (pas de modification ici pour l’instant) -->
                                    <form method="POST" onsubmit="return confirm('Supprimer ce matériel ?');">
                                        <input type="hidden" name="materiel_id" value="<?= $row['id'] ?>">
                                        <button type="submit" name="supprimer" class="btn btn-sm btn-danger">Supprimer</button>
                                    </form>
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
