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

// Vérifier si un ID est passé en paramètre pour supprimer la voiture
if (isset($_GET['id'])) {
    $id_voiture = $_GET['id'];

    // Supprimer la voiture de la base de données
    $stmt = $conn->prepare("DELETE FROM voiture WHERE id = ?");
    $stmt->execute([$id_voiture]);

    // Rediriger après la suppression
    header("Location: base_de_donnes.php"); // Redirigez vers la page principale ou la liste des voitures
    exit();
} else {
    echo "ID de la voiture non spécifié.";
}
?>
