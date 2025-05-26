<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "prêt materiel";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}

$sql = "SELECT * FROM reservations";
$result = $conn->query($sql);

echo "<h3>DEBUG : Toutes les lignes de la table reservations</h3>";
echo "<pre>";
print_r($result->fetch_all(MYSQLI_ASSOC));
echo "</pre>";
$conn->close();
?>
