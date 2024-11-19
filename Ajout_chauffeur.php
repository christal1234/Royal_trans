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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $sexe = $_POST['sexe'];

    $stmt = $conn->prepare("INSERT INTO chauffeur (nom, prenom, sexe) VALUES (?, ?, ?)");
    $stmt->execute([$nom, $prenom, $sexe]);
    header('Location: index.php');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Chauffeur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Ajouter un Chauffeur</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="nom" class="form-label">Nom</label>
            <input type="text" class="form-control" name="nom" required>
        </div>
        <div class="mb-3">
            <label for="prenom" class="form-label">Prenom</label>
            <input type="text" class="form-control" name="prenom">
        </div>
        
        <div class="mb-3">
            <label for="sexe" class="form-label">Sexe</label>
            <select class="form-select" name="sexe" required>
                <option value="M">M</option>
                <option value="F">F</option>
            </select>
        </div>
        
        <button type="submit" class="btn btn-primary">Ajouter</button>
        <a href="index.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</body>
</html>
