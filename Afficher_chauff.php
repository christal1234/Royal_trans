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

// Récupérer tous les chauffeurs ou ceux qui correspondent à la recherche
if ($searchTerm) {
    $stmt = $conn->prepare("SELECT * FROM chauffeur WHERE nom LIKE ? OR prenom LIKE ? OR sexe LIKE ?");
    $stmt->execute(['%' . $searchTerm . '%', '%' . $searchTerm . '%', '%' . $searchTerm . '%']);
} else {
    $stmt = $conn->query("SELECT * FROM chauffeur");
}
$chauffeurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Chauffeurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Liste des Chauffeurs</h2>
    <form method="POST" class="mb-3">
        <div class="input-group">
            <input type="text" class="form-control" name="search" placeholder="Rechercher par nom, prénom ou sexe" value="<?= htmlspecialchars($searchTerm) ?>">
            <button class="btn btn-primary" type="submit">Rechercher</button>
        </div>
    </form>
    <a href="Ajouter_voitures.php" class="btn btn-primary mb-3">Ajouter un Chauffeur</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Chauffeur</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Sexe</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($chauffeurs) > 0): ?>
                <?php foreach ($chauffeurs as $chauffeur) : ?>
                    <tr>
                        <td><?= htmlspecialchars($chauffeur['id_chauffeur']) ?></td>
                        <td><?= htmlspecialchars($chauffeur['nom']) ?></td>
                        <td><?= htmlspecialchars($chauffeur['prenom']) ?></td>
                        <td><?= htmlspecialchars($chauffeur['sexe']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">Aucun chauffeur trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
