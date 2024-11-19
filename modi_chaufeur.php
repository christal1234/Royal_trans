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
    echo "Erreur de connexion : " . $e->getMessage();
}

// Vérifier si un ID est passé et récupérer les informations
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM chauffeur WHERE id_chauffeur = ?");
    $stmt->execute([$id]);
    $chauffeur = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$chauffeur) {
        echo "Chauffeur introuvable.";
        exit;
    }
}

// Mettre à jour les informations du chauffeur
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $sexe = $_POST['sexe'];

    $stmt = $conn->prepare("UPDATE chauffeur SET nom = ?, prenom = ?, sexe = ? WHERE id_chauffeur = ?");
    $stmt->execute([$nom, $prenom, $sexe, $id]);

    echo "Le chauffeur a été mis à jour avec succès.";
    header("Location: base_de_donnes.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Chauffeur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Modifier le Chauffeur</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Nom</label>
            <input type="text" class="form-control" name="nom" value="<?= htmlspecialchars($chauffeur['nom']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Prénom</label>
            <input type="text" class="form-control" name="prenom" value="<?= htmlspecialchars($chauffeur['prenom']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Sexe</label>
            <select class="form-control" name="sexe" required>
                <option value="Homme" <?= $chauffeur['sexe'] === 'Homme' ? 'selected' : '' ?>>Homme</option>
                <option value="Femme" <?= $chauffeur['sexe'] === 'Femme' ? 'selected' : '' ?>>Femme</option>
            </select>
        </div>
        <button class="btn btn-primary" type="submit">Enregistrer les modifications</button>
    </form>
</div>
</body>
</html>
