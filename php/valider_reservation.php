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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reservation_id']) && isset($_POST['action'])) {
    $reservation_id = intval($_POST['reservation_id']);
    $action = $_POST['action'];
    $commentaire_admin = isset($_POST['commentaire_admin']) ? trim($_POST['commentaire_admin']) : '';

    // Validation des actions autorisées
    if (!in_array($action, ['valider', 'refuser'])) {
        die("Action non autorisée.");
    }

    $statut = $action === 'valider' ? 'validée' : 'refusée';

    $sql = "UPDATE reservations 
            SET statut = ?, date_validation = NOW(), commentaire_admin = ? 
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $statut, $commentaire_admin, $reservation_id);

    if ($stmt->execute()) {
        header("Location: gestion_reservations.php?success=1");
        exit();
    } else {
        echo "Erreur : " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Requête invalide.";
}

$conn->close();
?>
