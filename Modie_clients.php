<?php
include 'config.php'; // Assurez-vous d'inclure le fichier de connexion

$id_client = $_GET['id'] ?? null;

if ($id_client) {
    $stmt = $conn->prepare("SELECT * FROM client WHERE id = :id");
    $stmt->execute(['id' => $id_client]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $postnom = $_POST['postnom'];
    $prenom = $_POST['prenom'];
    $sexe = $_POST['sexe'];
    $email = $_POST['email'];

    $stmt = $conn->prepare("UPDATE client SET nom = :nom, postnom = :postnom, prenom = :prenom, sexe = :sexe, email = :email WHERE id = :id");
    $stmt->execute([
        'nom' => $nom,
        'postnom' => $postnom,
        'prenom' => $prenom,
        'sexe' => $sexe,
        'email' => $email,
        'id' => $id_client
    ]);

    header("Location: index.php"); // Redirection vers la liste des clients
    exit;
}
?>
<!-- Formulaire HTML pour afficher et mettre à jour les informations du client -->
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
        <div class="mb-3">
            <label>Nom</label>
            <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($client['nom']) ?>" required>
        </div>
        <div class="mb-3">
            <label>postnom</label>
            <input type="text" name="postnom" class="form-control" value="<?= htmlspecialchars($client['postnom']) ?>" required>
        </div>
        <div class="mb-3">
            <label>prenom</label>
            <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($client['prenom']) ?>" required>
        </div>
        <div class="mb-3">
            <label>sexe</label>
<select name="sexe" id="sexe">
    <option value="<?= htmlspecialchars($client['sexe']) ?>"></option>
</select>        
</div>

<div class="mb-3">
            <label>Email</label>
            <input type="text" name="email" class="form-control" value="<?= htmlspecialchars($client['email']) ?>" required>
        </div>
        <!-- Autres champs... -->
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
</body>
</html>
