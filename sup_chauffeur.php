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

    // Préparer et exécuter la requête de suppression
    $stmt = $conn->prepare("DELETE FROM chauffeur WHERE id_chauffeur = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo "Le chauffeur a été supprimé avec succès.";
    } else {
        echo "Aucun chauffeur trouvé avec cet ID.";
    }

    // Redirection vers la liste des chauffeurs après suppression
    header("Location: base_de_donnes.php");
    exit;
} else {
    echo "Aucun ID de chauffeur spécifié.";
}
?>
