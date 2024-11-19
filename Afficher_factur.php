<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ROYAL";

try {
    // Connexion à la base de données
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}

session_start();

if (!isset($_SESSION['user_id'])) { // Vérifie si l'utilisateur est connecté
    header("Location: login.php"); // Redirige vers la page de connexion
    exit();
}
// Initialiser les filtres
$mois = isset($_GET['mois']) ? $_GET['mois'] : '';
$annee = isset($_GET['annee']) ? $_GET['annee'] : '';

// Requête SQL avec filtres optionnels
$sql = "SELECT reservation.id_reservation, reservation.date_debut, reservation.date_fin, reservation.montant_paye, reservation.caution,
               client.nom AS client_nom, client.prenom AS client_prenom,
               voiture.marque AS voiture_marque, voiture.modele AS voiture_modele,
               chauffeur.nom AS chauffeur_nom, chauffeur.prenom AS chauffeur_prenom,
               DATEDIFF(reservation.date_fin, reservation.date_debut) AS nombre_jours
        FROM reservation
        LEFT JOIN client ON reservation.client_id = client.id
        LEFT JOIN voiture ON reservation.voiture_id = voiture.id
        LEFT JOIN chauffeur ON reservation.chauffeur_id = chauffeur.id_chauffeur";

// Ajouter des conditions de filtre
$conditions = [];
if (!empty($mois)) {
    $conditions[] = "MONTH(reservation.date_debut) = :mois";
}
if (!empty($annee)) {
    $conditions[] = "YEAR(reservation.date_debut) = :annee";
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$stmt = $conn->prepare($sql);

// Lier les paramètres
if (!empty($mois)) {
    $stmt->bindValue(':mois', $mois, PDO::PARAM_INT);
}
if (!empty($annee)) {
    $stmt->bindValue(':annee', $annee, PDO::PARAM_INT);
}

$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calcul du montant total payé
$totalMontantPaye = 0;
foreach ($reservations as $reservation) {
    $totalMontantPaye += $reservation['montant_paye'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Réservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet"> <!-- Animation library -->
    <style>
        /* Animation for table rows */
        tr {
            transition: transform 0.2s ease-in-out, opacity 0.2s ease-in-out;
        }
        tr:hover {
            transform: scale(1.02);
            opacity: 0.9;
        }
    </style>
</head>
<body>
<br><br>
<div class="container mt-5">
<a href="base_de_donnes.php" class="btn btn-danger">Retour</a>

    <h2 data-aos="fade-down">Liste des Réservations</h2> <!-- Animation on heading -->

    <!-- Formulaire de recherche -->
    <form method="GET" class="row mb-4" data-aos="fade-up">
        <div class="col-md-4">
            <label for="mois" class="form-label">Mois</label>
            <select id="mois" name="mois" class="form-select">
                <option value="">Tous</option>
                <?php for ($i = 1; $i <= 12; $i++): ?>
                    <option value="<?= $i ?>" <?= ($mois == $i) ? 'selected' : '' ?>>
                        <?= DateTime::createFromFormat('!m', $i)->format('F') ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="annee" class="form-label">Année</label>
            <select id="annee" name="annee" class="form-select">
                <option value="">Toutes</option>
                <?php for ($i = date('Y'); $i >= 1990; $i--): ?>
                    <option value="<?= $i ?>" <?= ($annee == $i) ? 'selected' : '' ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Rechercher</button>
        </div>
    </form>

    <!-- Tableau des réservations -->
    <table class="table table-bordered table-striped" data-aos="fade-left">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Date Début</th>
                <th>Date Fin</th>
                <th>Client</th>
                <th>Voiture</th>
                <th>Chauffeur</th>
                <th>Montant Payé</th>
                <th>Caution</th>
                <th>Nombre de Jours</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td><?= $reservation['id_reservation'] ?></td>
                    <td><?= $reservation['date_debut'] ?></td>
                    <td><?= $reservation['date_fin'] ?></td>
                    <td><?= htmlspecialchars($reservation['client_nom'] . ' ' . $reservation['client_prenom']) ?></td>
                    <td><?= htmlspecialchars($reservation['voiture_marque'] . ' ' . $reservation['voiture_modele']) ?></td>
                    <td><?= $reservation['chauffeur_nom'] ? htmlspecialchars($reservation['chauffeur_nom'] . ' ' . $reservation['chauffeur_prenom']) : 'Aucun' ?></td>
                    <td><?= $reservation['montant_paye'] ?></td>
                    <td><?= $reservation['caution'] ?></td>
                    <td><?= $reservation['nombre_jours'] ?></td>
                    <td>
                        <a href="modifier_reservation.php?id=<?= $reservation['id_reservation'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="supprimer_reservation.php?id=<?= $reservation['id_reservation'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous vraiment supprimer cette réservation ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="6" class="text-end fw-bold">Total Montant Payé</td>
                <td colspan="3" class="fw-bold"><?= $totalMontantPaye ?></td>
            </tr>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init(); // Initialize AOS library
</script>
</body>
</html>
