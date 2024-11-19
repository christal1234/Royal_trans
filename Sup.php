<?php
// Configuration de la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ROYAL";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

// Fonction pour supprimer un client
function deleteClient($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM client WHERE id = ?");
    $stmt->execute([$id]);
}

// Suppression d'un client
if (isset($_GET['id'])) {
    deleteClient($conn, $_GET['id']);
    header("Location:base_de_donnes.php"); // Redirection après la suppression
}

?>
