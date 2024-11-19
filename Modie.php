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

// Fonction de récupération d'un client spécifique
function getClient($conn, $id) {
    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fonction de mise à jour d'un client
function updateClient($conn, $id, $nom, $postnom, $prenom, $sexe, $email) {
    $stmt = $conn->prepare("UPDATE client SET nom = ?, postnom = ?, prenom = ?, sexe = ?, email = ? WHERE id = ?");
    $stmt->execute([$nom, $postnom, $prenom, $sexe, $email, $id]);
}

// Modification d'un client
if (isset($_GET['id'])) {
    $client = getClient($conn, $_GET['id']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    updateClient($conn, $_POST['id'], $_POST['nom'], $_POST['postnom'], $_POST['prenom'], $_POST['sexe'], $_POST['email']);
    header("Location: base_de_donnes.php"); // Redirection après la mise à jour
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Client</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Modifier Client</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($client['id']) ?>">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom" value="<?= htmlspecialchars($client['nom']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="postnom" class="form-label">Postnom</label>
            <input type="text" class="form-control" id="postnom" name="postnom" value="<?= htmlspecialchars($client['postnom']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="prenom" class="form-label">Prénom</label>
            <input type="text" class="form-control" id="prenom" name="prenom" value="<?= htmlspecialchars($client['prenom']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="sexe" class="form-label">Sexe</label>
            <select class="form-select" id="sexe" name="sexe" required>
                <option value="Homme" <?= $client['sexe'] === 'Homme' ? 'selected' : '' ?>>Homme</option>
                <option value="Femme" <?= $client['sexe'] === 'Femme' ? 'selected' : '' ?>>Femme</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($client['email']) ?>" required>
        </div>
        <button type="submit" class="btn btn-success">Mettre à jour</button>
    </form>
</div>
</body>
</html>
