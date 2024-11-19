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
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifier si l'ID est passé dans l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Requête pour récupérer les données de la réservation
    $stmt = $conn->prepare("SELECT * FROM reservation WHERE id_reservation = ?");
    $stmt->execute([$id]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reservation) {
        die("Réservation introuvable !");
    }
} else {
    die("Aucun ID de réservation spécifié !");
}

// Mise à jour de la réservation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $montant_paye = $_POST['montant_paye'];
    $caution = $_POST['caution'];

    $stmt = $conn->prepare("UPDATE reservation SET date_debut = ?, date_fin = ?, montant_paye = ?, caution = ? WHERE id_reservation = ?");
    $stmt->execute([$date_debut, $date_fin, $montant_paye, $caution, $id]);

    header("Location: reservations.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier une Réservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Modifier la Réservation</h2>
    <form method="post">
        <div class="mb-3">
            <label for="date_debut" class="form-label">Date Début</label>
            <input type="date" name="date_debut" id="date_debut" class="form-control" value="<?= $reservation['date_debut'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="date_fin" class="form-label">Date Fin</label>
            <input type="date" name="date_fin" id="date_fin" class="form-control" value="<?= $reservation['date_fin'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="montant_paye" class="form-label">Montant Payé</label>
            <input type="number" name="montant_paye" id="montant_paye" class="form-control" value="<?= $reservation['montant_paye'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="caution" class="form-label">Caution</label>
            <input type="number" name="caution" id="caution" class="form-control" value="<?= $reservation['caution'] ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Modifier</button>
        <a href="reservations.php" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</body>
</html>
