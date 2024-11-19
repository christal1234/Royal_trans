<?php
// Modifier_paiement.php
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
    $id = $_POST['id'];
    $client_id = $_POST['client_id'];
    $voiture_id = $_POST['voiture_id'];
    $caution = $_POST['caution'];
    $montant = $_POST['montant_paye'];
    $motif = $_POST['motif'];

    // Mise à jour de la base de données
    $stmt = $conn->prepare("UPDATE paiement SET client_id=?, voiture_id=?, caution=?, montant_paye=?, motif=? WHERE id_paiement=?");
    $stmt->execute([$client_id, $voiture_id, $caution, $montant, $motif, $id]);

    header('Location: Affiche_paiements.php');
    exit();
} else {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM paiement WHERE id_paiement = ?");
    $stmt->execute([$id]);
    $paiement = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Récupération des clients et voitures
$clients = $conn->query("SELECT * FROM client")->fetchAll(PDO::FETCH_ASSOC);
$voitures = $conn->query("SELECT * FROM voiture")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un Paiement</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Modifier un Paiement</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($paiement['id_paiement']) ?>">
        <div class="mb-3">
            <label for="client_id" class="form-label">Client</label>
            <select class="form-select" name="client_id" required>
                <?php foreach ($clients as $client): ?>
                    <option value="<?= $client['id'] ?>" <?= $client['id'] == $paiement['client_id'] ? 'selected' : '' ?>><?= htmlspecialchars($client['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="voiture_id" class="form-label">Voiture</label>
            <select class="form-select" name="voiture_id" required>
                <?php foreach ($voitures as $voiture): ?>
                    <option value="<?= $voiture['id'] ?>" <?= $voiture['id'] == $paiement['voiture_id'] ? 'selected' : '' ?>><?= htmlspecialchars($voiture['marque']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="montant_paye" class="form-label">Montant Payé</label>
            <input type="number" class="form-control" name="montant_paye" value="<?= htmlspecialchars($paiement['montant_paye']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="caution" class="form-label">Caution</label>
            <input type="number" class="form-control" name="caution" value="<?= htmlspecialchars($paiement['caution']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="motif" class="form-label">Motif</label>
            <select name="motif" class="form-select" required>
                <option value="Solde" <?= $paiement['motif'] == 'Solde' ? 'selected' : '' ?>>Solde</option>
                <option value="Acompte" <?= $paiement['motif'] == 'Acompte' ? 'selected' : '' ?>>Acompte</option>
            </select>
        </div>
        <button type="submit" class="btn btn-warning">Mettre à jour</button>
        <a href="Affiche_paiements.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</body>
</html>
