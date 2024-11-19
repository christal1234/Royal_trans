<?php 
// Affiche_voitures.php
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

// Vérifier si un terme de recherche a été soumis
$searchTerm = '';
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
}

// Récupérer toutes les voitures ou celles qui correspondent à la recherche
if ($searchTerm) {
    $stmt = $conn->prepare("SELECT * FROM voiture WHERE marque LIKE ? OR modele LIKE ? OR categorie LIKE ?");
    $stmt->execute(['%' . $searchTerm . '%', '%' . $searchTerm . '%', '%' . $searchTerm . '%']);
} else {
    $stmt = $conn->query("SELECT * FROM voiture");
}
$voitures = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Voitures</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Liste des Voitures</h2>
    <form method="POST" class="mb-3">
        <div class="input-group">
            <input type="text" class="form-control" name="search" placeholder="Rechercher par marque, modèle ou catégorie" value="<?= htmlspecialchars($searchTerm) ?>">
            <button class="btn btn-primary" type="submit">Rechercher</button>
        </div>
    </form>
    <a href="Ajouter_voitures.php" class="btn btn-primary mb-3">Ajouter une Voiture</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Marque</th>
                <th>Modèle</th>
                <th>Catégorie</th>
                <th>Immatriculation</th>
                <th>Couleur</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($voitures) > 0): ?>
                <?php foreach ($voitures as $voiture) : ?>
                    <tr>
                        <td><?= htmlspecialchars($voiture['id']) ?></td>
                        <td><?= htmlspecialchars($voiture['marque']) ?></td>
                        <td><?= htmlspecialchars($voiture['modele']) ?></td>
                        <td><?= htmlspecialchars($voiture['categorie']) ?></td>
                        <td><?= htmlspecialchars($voiture['imatriculation']) ?></td>
                        <td><?= htmlspecialchars($voiture['couleur']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Aucune voiture trouvée.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
