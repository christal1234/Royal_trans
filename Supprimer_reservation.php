<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ROYAL";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifier si un ID est passé dans l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Requête pour supprimer la réservation
    $stmt = $conn->prepare("DELETE FROM reservation WHERE id_reservation = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo "La réservation a été supprimée avec succès.";
    } else {
        echo "Aucune réservation trouvée avec cet ID.";
    }

    // Redirection après suppression
    header("Location: Afficher_factur.php");
    exit;
} else {
    echo "Aucun ID de réservation spécifié.";
}
?>
