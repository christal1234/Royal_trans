


<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ROYAL";

try {
    // Connexion à la base de données
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $client_id = $_POST['client_id'];
    $nom_clients = $_POST['nom_clients'];
    $marque = $_POST['marque'];
    $voiture_id = $_POST['voiture_id'];
    $model = $_POST['model'];
    $reservation_id = $_POST['reservation_id'];
    $montant = $_POST['montant'];
    $date = $_POST['date'];
    $chauffeur_id = $_POST['chauffeur_id'];
    $caution = $_POST['caution'];
    $motif = $_POST['motif'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $nombre_jours = $_POST['nombre_jours'];

    // Requête d'insertion
    $sql = "INSERT INTO facture (client_id, nom_clients, marque, voiture_id, model, reservation_id, montant, date, chauffeur_id, caution, motif, date_debut, date_fin, nombre_jours) 
            VALUES (:client_id, :nom_clients, :marque, :voiture_id, :model, :reservation_id, :montant, :date, :chauffeur_id, :caution, :motif, :date_debut, :date_fin, :nombre_jours)";

    $stmt = $conn->prepare($sql);
    
    // Liaison des paramètres
    $stmt->bindParam(':client_id', $client_id);
    $stmt->bindParam(':nom_clients', $nom_clients);
    $stmt->bindParam(':marque', $marque);
    $stmt->bindParam(':voiture_id', $voiture_id);
    $stmt->bindParam(':model', $model);
    $stmt->bindParam(':reservation_id', $reservation_id);
    $stmt->bindParam(':montant', $montant);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':chauffeur_id', $chauffeur_id);
    $stmt->bindParam(':caution', $caution);
    $stmt->bindParam(':motif', $motif);
    $stmt->bindParam(':date_debut', $date_debut);
    $stmt->bindParam(':date_fin', $date_fin);
    $stmt->bindParam(':nombre_jours', $nombre_jours);

    // Exécution de la requête
    if ($stmt->execute()) {
        echo "Données enregistrées avec succès.";
    } else {
        echo "Erreur : " . $stmt->errorInfo()[2];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Facture</title>
</head>
<body>
    <h2>Ajouter une facture</h2>
    <form method="post" action="">
        <label for="client_id">ID Client:</label>
        <input type="number" name="client_id" required><br>

        <label for="nom_clients">Nom du Client:</label>
        <input type="text" name="nom_clients" required><br>

        <label for="marque">Marque:</label>
        <input type="text" name="marque" required><br>

        <label for="voiture_id">ID Voiture:</label>
        <input type="number" name="voiture_id" required><br>

        <label for="model">Modèle:</label>
        <input type="text" name="model" required><br>

        <label for="reservation_id">ID Réservation:</label>
        <input type="number" name="reservation_id" required><br>

        <label for="montant">Montant:</label>
        <input type="text" name="montant" required><br>

        <label for="date">Date:</label>
        <input type="date" name="date" required><br>

        <label for="chauffeur_id">ID Chauffeur:</label>
        <input type="number" name="chauffeur_id"><br>

        <label for="caution">Caution:</label>
        <input type="text" name="caution" required><br>

        <label for="motif">Motif:</label>
        <input type="text" name="motif" required><br>

        <label for="date_debut">Date de Début:</label>
        <input type="date" name="date_debut" required><br>

        <label for="date_fin">Date de Fin:</label>
        <input type="date" name="date_fin" required><br>

        <label for="nombre_jours">Nombre de Jours:</label>
        <input type="number" name="nombre_jours" required><br>

        <input type="submit" value="Enregistrer">
    </form>
</body>
</html>