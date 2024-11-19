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

// Récupération des enregistrements de la table facture
$stmt = $conn->query("SELECT * FROM facture");
$factures = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Factures</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Liste des Factures</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID Facture</th>
                <th>Nom du Client</th>
                <th>Marque de Voiture</th>
                <th>Modèle</th>
                <th>Montant</th>
                <th>Date</th>
                <th>Caution</th>
                <th>Motif</th>
                <th>Date de Début</th>
                <th>Date de Fin</th>
                <th>Nombre de Jours</th>
                <th>Action</th>

            </tr>
        </thead>
        <tbody>
            <?php foreach ($factures as $facture): ?>
                <tr>
                    <td><?= htmlspecialchars($facture['id_facture']) ?></td>
                    <td><?= htmlspecialchars($facture['nom_clients']) ?></td>
                    <td><?= htmlspecialchars($facture['marque']) ?></td>
                    <td><?= htmlspecialchars($facture['model']) ?></td>
                    <td><?= htmlspecialchars($facture['montant']) ?></td>
                    <td><?= htmlspecialchars($facture['date']) ?></td>
                    <td><?= htmlspecialchars($facture['caution']) ?></td>
                    <td><?= htmlspecialchars($facture['motif']) ?></td>
                    <td><?= htmlspecialchars($facture['date_debut']) ?></td>
                    <td><?= htmlspecialchars($facture['date_fin']) ?></td>
                    <td><?= htmlspecialchars($facture['nombre_jours']) ?></td>
                    <td>
                    <a href="Modie_clients.php?id=<?= $client['id'] ?>" class="btn btn-warning btn-sm">Modifier</a> <br>
                    <a href="Supprimer_facture.php?id=<?= $client['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?')">Supprimer</a>
                    </td>
                    
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
