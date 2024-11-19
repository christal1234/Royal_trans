<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ROYAL";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer l'ID de la facture à modifier
    $id_facture = $_GET['id'] ?? null;

    if ($id_facture) {
        // Récupérer les détails de la facture
        $stmt = $conn->prepare("SELECT * FROM facture WHERE id_facture = :id_facture");
        $stmt->execute(['id_facture' => $id_facture]);
        $facture = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérifier si la facture existe
        if (!$facture) {
            echo "<p>Facture introuvable.</p>";
            exit;
        }
    } else {
        echo "<p>Aucun identifiant de facture fourni.</p>";
        exit;
    }

    // Traitement du formulaire de mise à jour
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $montant = $_POST['montant'];
        $caution = $_POST['caution'];
        $motif = $_POST['motif'];
        // Ajoutez d'autres champs si nécessaire

        // Mettre à jour la facture
        $stmt = $conn->prepare("UPDATE facture SET montant = :montant, caution = :caution, motif = :motif WHERE id_facture = :id_facture");
        $stmt->execute([
            'montant' => $montant,
            'caution' => $caution,
            'motif' => $motif,
            'id_facture' => $id_facture
        ]);

        // Redirection ou message de succès
        header("Location: facture.php"); // redirige vers la liste des factures
        exit;
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Facture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Modifier Facture</h2>

    <form method="POST">
        <div class="mb-3">
            <label for="montant" class="form-label">Montant</label>
            <input type="text" id="montant" name="montant" class="form-control" value="<?= htmlspecialchars($facture['montant']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="caution" class="form-label">Caution</label>
            <input type="text" id="caution" name="caution" class="form-control" value="<?= htmlspecialchars($facture['caution']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="motif" class="form-label">Motif</label>
            <input type="text" id="motif" name="motif" class="form-control" value="<?= htmlspecialchars($facture['motif']); ?>" required>
        </div>
        <!-- Ajoutez d'autres champs si nécessaire -->

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
</body>
</html>
