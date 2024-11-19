<?php
// Ajoute_voiture.php
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
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $categorie = $_POST['categorie'];
    $imatriculation = $_POST['imatriculation'];
    $couleur = $_POST['couleur'];

    $stmt = $conn->prepare("INSERT INTO voiture (marque, modele, categorie, imatriculation, couleur) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$marque, $modele, $categorie, $imatriculation, $couleur]);
    header('Location: Affiche_voitures.php');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Voiture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Ajouter une Voiture</h2>
    <form method="POST">
        <div class="mb-3">
            <label for="marque" class="form-label">Marque</label>
            <input type="text" class="form-control" name="marque" required>
        </div>
        <div class="mb-3">
            <label for="modele" class="form-label">Modèle</label>
            <input type="text" class="form-control" name="modele" required>
        </div>
        <div class="mb-3">
                <label for="Catégorie" class="form-label">Catégorie</label>
                <select name="Catégorie" id="Catégorie" class="form-select" required>
                    <option value="Luxe">Luxe</option>
                    <option value="Familiale">Familiale</option>

                    <option value="Economique">Economique</option>
                   
                </select>
            </div>
        <div class="mb-3">
            <label for="imatriculation" class="form-label">Immatriculation</label>
            <input type="text" class="form-control" name="imatriculation" required>
        </div>
        <div class="mb-3">
            <label for="couleur" class="form-label">Couleur</label>
            <input type="text" class="form-control" name="couleur">
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
        <a href="Affiche_voitures.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</body>
</html>
