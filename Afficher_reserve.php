<?php
// Affiche_reservations.php
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

$query = $conn->query("SELECT r.*, c.nom AS client_nom, v.marque AS voiture_marque, v.couleur, v.modele, v.imatriculation, v.categorie
                       FROM reservation r 
                       JOIN client c ON r.client_id = c.id 
                       JOIN voiture v ON r.voiture_id = v.id");
$reservations = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Liste des Réservations</h2>
    <a href="Ajouter_reserve.php" class="btn btn-success mb-3">Ajouter une Réservation</a>
    <table class="table table-striped table-hover align-middle text-center">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Date de Début</th>
                <th>Date de Fin</th>
                <th>Nombre de jours</th>
                <th>Nom du Client</th>
                <th>Montant Payé</th>
                <th>Caution</th>
                <th>Voiture</th>
                <th>Couleur</th>
                <th>Modèle</th>
                <th>Immatriculation</th>
                <th>Catégorie</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $reservation) : ?>
                <tr>
                    <td><?= htmlspecialchars($reservation['id_reservation']) ?></td>
                    <td><?= htmlspecialchars($reservation['date_debut']) ?></td>
                    <td><?= htmlspecialchars($reservation['date_fin']) ?></td>
                    <td><?= htmlspecialchars($reservation['nombre_jours']) ?></td>
                    <td><?= htmlspecialchars($reservation['client_nom']) ?></td>
                    <td><?= htmlspecialchars($reservation['montant_paye']) ?></td>
                    <td><?= htmlspecialchars($reservation['caution']) ?></td>
                    <td><?= htmlspecialchars($reservation['voiture_marque']) ?></td>
                    <td><?= htmlspecialchars($reservation['couleur']) ?></td>
                    <td><?= htmlspecialchars($reservation['modele']) ?></td>
                    <td><?= htmlspecialchars($reservation['imatriculation']) ?></td>
                    <td><?= htmlspecialchars($reservation['categorie']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
