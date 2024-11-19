<?php
$servername = "localhost";
$username = "root";  // Utilisateur root par défaut pour XAMPP
$password = "";      // Mot de passe vide par défaut pour XAMPP
$dbname = "royal";

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit();
}

// Récupérer les réservations
$sql = "SELECT r.`id-reservation`, r.date_debut, r.date_fin, v.marque, v.modele, c.nom 
        FROM reservations r 
        JOIN voitures v ON r.voiture_id = v.id 
        JOIN clients c ON r.client_id = c.id";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Vérification des données récupérées
if (!$reservations) {
    echo "Aucune réservation trouvée.";
    exit();
}

// Création du document PDF
$pdf = new TCPDF();
$pdf->AddPage();

// Titre du PDF
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Liste des Réservations', 0, 1, 'C');

// Mise en forme du tableau
$pdf->SetFont('helvetica', '', 12);
$pdf->Cell(40, 10, 'ID', 1);
$pdf->Cell(40, 10, 'Nom du Client', 1);
$pdf->Cell(50, 10, 'Voiture', 1);
$pdf->Cell(30, 10, 'Date Début', 1);
$pdf->Cell(30, 10, 'Date Fin', 1);
$pdf->Ln();

// Remplissage du tableau avec les réservations
foreach ($reservations as $reservation) {
    // Vérification si chaque champ existe dans la réservation
    $id_reservation = isset($reservation['id-reservation']) ? $reservation['id-reservation'] : 'N/A';
    $nom_client = isset($reservation['nom']) ? $reservation['nom'] : 'N/A';
    $voiture = isset($reservation['marque']) && isset($reservation['modele']) ? $reservation['marque'] . ' ' . $reservation['modele'] : 'N/A';
    $date_debut = isset($reservation['date_debut']) ? $reservation['date_debut'] : 'N/A';
    $date_fin = isset($reservation['date_fin']) ? $reservation['date_fin'] : 'N/A';

    // Remplissage du tableau PDF
    $pdf->Cell(40, 10, $id_reservation, 1);
    $pdf->Cell(40, 10, $nom_client, 1);
    $pdf->Cell(50, 10, $voiture, 1);
    $pdf->Cell(30, 10, $date_debut, 1);
    $pdf->Cell(30, 10, $date_fin, 1);
    $pdf->Ln();
}

// Sortie du PDF
$pdf->Output('reservations.pdf', 'I');
?>
