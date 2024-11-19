<?php
require_once('TCPDF/tcpdf.php');

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
    exit();
}

// Récupération de la facture à partir de l'ID
$id_facture = $_GET['id'] ?? null;
if (!$id_facture) {
    echo "ID de facture manquant.";
    exit();
}

$sql = "
    SELECT 
        facture.id_facture,
        client.nom AS nom_client,
        voiture.marque,
        voiture.modele,
        facture.montant,
        facture.date,
        facture.caution,
        facture.motif,
        facture.date_debut,
        facture.date_fin,
        facture.nombre_jours
    FROM facture
    JOIN client ON facture.client_id = client.id
    JOIN voiture ON facture.voiture_id = voiture.id
    WHERE facture.id_facture = :id_facture";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':id_facture', $id_facture, PDO::PARAM_INT);
$stmt->execute();
$facture = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$facture) {
    echo "Facture introuvable.";
    exit();
}

// Création du document PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 12);

// Contenu de la facture
$html = "
    <h2>Facture - Réservation</h2>
    <strong>Nom du client :</strong> {$facture['nom_client']}<br>
    <strong>Voiture :</strong> {$facture['marque']} - {$facture['modele']}<br>
    <strong>Montant :</strong> {$facture['montant']}<br>
    <strong>Date de début :</strong> {$facture['date_debut']}<br>
    <strong>Date de fin :</strong> {$facture['date_fin']}<br>
    <strong>Nombre de jours :</strong> {$facture['nombre_jours']}<br>
    <strong>Date de facture :</strong> {$facture['date']}<br>
    <strong>Caution :</strong> {$facture['caution']}<br>
    <strong>Motif :</strong> {$facture['motif']}<br>";

// Ajout du logo (optionnel)
$html .= '<img src="path_to_logo/logo.png" alt="Logo" height="50"><br>';

$pdf->writeHTML($html, true, false, true, false, '');

// Envoi du fichier PDF au navigateur
$pdf->Output("facture_{$id_facture}.pdf", 'I');
