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
    exit();
}

// Gestion de la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_clients = $_POST['nom_clients'];
    $marque = $_POST['marque'];
    $model = $_POST['model'];
    $montant = $_POST['montant'];
    $date = $_POST['date'];
    $caution = $_POST['caution'];
    $motif = $_POST['motif'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $nombre_jours = $_POST['nombre_jours'];

    // Requête d'insertion
    $sql = "INSERT INTO facture (nom_clients, marque, model, montant, date, caution, motif, date_debut, date_fin, nombre_jours) 
            VALUES (:nom_clients, :marque, :model, :montant, :date, :caution, :motif, :date_debut, :date_fin, :nombre_jours)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nom_clients', $nom_clients);
    $stmt->bindParam(':marque', $marque);
    $stmt->bindParam(':model', $model);
    $stmt->bindParam(':montant', $montant);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':caution', $caution);
    $stmt->bindParam(':motif', $motif);
    $stmt->bindParam(':date_debut', $date_debut);
    $stmt->bindParam(':date_fin', $date_fin);
    $stmt->bindParam(':nombre_jours', $nombre_jours);

    // Exécute la requête
    if ($stmt->execute()) {
        echo "La facture a été ajoutée avec succès.";
    } else {
        echo "Erreur lors de l'ajout de la facture.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Facture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Ajouter une Facture</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="nom_clients" class="form-label">Nom du Client</label>
            <input type="text" class="form-control" id="nom_clients" name="nom_clients" required>
        </div>
        <div class="mb-3">
            <label for="marque" class="form-label">Marque de Voiture</label>
            <input type="text" class="form-control" id="marque" name="marque" required>
        </div>
        <div class="mb-3">
            <label for="model" class="form-label">Modèle</label>
            <input type="text" class="form-control" id="model" name="model" required>
        </div>
        <div class="mb-3">
            <label for="montant" class="form-label">Montant</label>
            <input type="number" step="0.01" class="form-control" id="montant" name="montant" required>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>
        <div class="mb-3">
            <label for="caution" class="form-label">Caution</label>
            <input type="number" step="0.01" class="form-control" id="caution" name="caution" required>
        </div>
        <div class="mb-3">
            <label for="motif" class="form-label">Motif</label>
            <textarea class="form-control" id="motif" name="motif"></textarea>
        </div>
        <div class="mb-3">
            <label for="date_debut" class="form-label">Date de Début</label>
            <input type="date" class="form-control" id="date_debut" name="date_debut" required>
        </div>
        <div class="mb-3">
            <label for="date_fin" class="form-label">Date de Fin</label>
            <input type="date" class="form-control" id="date_fin" name="date_fin" required>
        </div>
        <div class="mb-3">
            <label for="nombre_jours" class="form-label">Nombre de Jours</label>
            <input type="number" class="form-control" id="nombre_jours" name="nombre_jours" required>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter Facture</button>
    </form>
</div>
</body>
</html>
