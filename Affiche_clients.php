<?php
// Ajoute_client.php
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

// Vérifier si le formulaire a été soumis pour ajouter un client
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ajouter_client'])) {
    $nom = $_POST['nom'];
    $postnom = $_POST['postnom'];
    $prenom = $_POST['prenom'];
    $sexe = $_POST['sexe'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("INSERT INTO client (nom, postnom, prenom, sexe, email) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $postnom, $prenom, $sexe, $email]);
}

// Vérifier si un terme de recherche a été soumis
$searchTerm = '';
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
}

// Récupérer tous les clients ou ceux qui correspondent à la recherche
if ($searchTerm) {
    $stmt = $conn->prepare("SELECT * FROM client WHERE nom LIKE ? OR postnom LIKE ? OR prenom LIKE ?");
    $stmt->execute(['%' . $searchTerm . '%', '%' . $searchTerm . '%', '%' . $searchTerm . '%']);
} else {
    $stmt = $conn->query("SELECT * FROM client");
}
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    

    <h2 class="mt-5">Liste des Clients</h2>
    <form method="POST" class="mb-3">
        <div class="input-group">
            <input type="text" class="form-control" name="search" placeholder="Rechercher par nom, postnom ou prénom" value="<?= htmlspecialchars($searchTerm) ?>">
            <button class="btn btn-primary" type="submit">Rechercher</button>
        </div>
    </form>
    
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Postnom</th>
                <th>Prénom</th>
                <th>Sexe</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($clients) > 0): ?>
                <?php foreach ($clients as $client) : ?>
                    <tr>
                        <td><?= htmlspecialchars($client['id']) ?></td>
                        <td><?= htmlspecialchars($client['nom']) ?></td>
                        <td><?= htmlspecialchars($client['postnom']) ?></td>
                        <td><?= htmlspecialchars($client['prenom']) ?></td>
                        <td><?= htmlspecialchars($client['sexe']) ?></td>
                        <td><?= htmlspecialchars($client['email']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Aucun client trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
