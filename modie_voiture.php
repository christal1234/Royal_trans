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
    $stmt = $conn->prepare("SELECT * FROM voiture WHERE id = ?");
    $stmt->execute([$id]);
    $voiture = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$voiture) {
        echo "Voiture introuvable.";
        exit;
    }
}

// Mettre à jour les informations de la voiture
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $categorie = $_POST['categorie'];
    $imatriculation = $_POST['imatriculation'];
    $couleur = $_POST['couleur'];

    $stmt = $conn->prepare("UPDATE voiture SET marque = ?, modele = ?, categorie = ?, imatriculation = ?, couleur = ? WHERE id = ?");
    $stmt->execute([$marque, $modele, $categorie, $imatriculation, $couleur, $id]);

    echo "La voiture a été mise à jour avec succès.";
    header("Location: base_de_donnes.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Voiture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Modifier la Voiture</h2>
    <form method="POST">
        <div class="mb-3">
            <label>Marque</label>
            <input type="text" class="form-control" name="marque" value="<?= htmlspecialchars($voiture['marque']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Modèle</label>
            <input type="text" class="form-control" name="modele" value="<?= htmlspecialchars($voiture['modele']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Catégorie</label>
            <input type="text" class="form-control" name="categorie" value="<?= htmlspecialchars($voiture['categorie']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Immatriculation</label>
            <input type="text" class="form-control" name="imatriculation" value="<?= htmlspecialchars($voiture['imatriculation']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Couleur</label>
            <input type="text" class="form-control" name="couleur" value="<?= htmlspecialchars($voiture['couleur']) ?>" required>
        </div>
        <button class="btn btn-primary" type="submit">Enregistrer les modifications</button>
    </form>
</div>
</body>
</html>
