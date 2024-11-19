<?php
// Afficher_facture.php
require_once('tcpdf/tcpdf.php');

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

// Vérifier si id_facture est passé en paramètre
if (isset($_GET['id_facture'])) {
    $id_facture = $_GET['id_facture'];

    // Requête pour obtenir les détails de la facture
    $query = "
        SELECT f.id_facture, c.nom AS client_nom, f.date AS date_paiement, 
               f.montant, v.marque, v.modele, p.caution, p.motif, 
               r.date_debut, r.date_fin, DATEDIFF(r.date_fin, r.date_debut) AS nombre_jours
        FROM facture f
        JOIN client c ON f.client_id = c.id
        JOIN voiture v ON f.voiture_id = v.id
        LEFT JOIN paiement p ON f.id_facture = p.id_facture
        JOIN reservation r ON f.reservation_id = r.id_reservation
        WHERE f.id_facture = :id_facture
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute(['id_facture' => $id_facture]);
    $facture = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($facture) {
        // Convertir les montants en flottants pour éviter les erreurs de type
        $montant = floatval($facture['montant']);
        $caution = isset($facture['caution']) ? floatval($facture['caution']) : 0.0;

        // Initialiser TCPDF
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Votre Nom');
        $pdf->SetTitle('Facture - ' . $facture['id_facture']);
        $pdf->SetHeaderData('', 0, 'Facture', 'ID : ' . $facture['id_facture']);
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
        $pdf->SetMargins(15, 27, 15);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->AddPage();

        // Contenu de la facture
        $html = '
            <h2>Facture #' . htmlspecialchars($facture['id_facture']) . '</h2>
            <p><strong>Client :</strong> ' . htmlspecialchars($facture['client_nom']) . '</p>
            <p><strong>Date de paiement :</strong> ' . htmlspecialchars($facture['date_paiement']) . '</p>
            <p><strong>Montant :</strong> ' . $montant . ' USD</p>
            <p><strong>Voiture :</strong> ' . htmlspecialchars($facture['marque']) . ' ' . htmlspecialchars($facture['modele']) . '</p>
            <p><strong>Caution :</strong> ' . $caution . ' USD</p>
            <p><strong>Motif :</strong> ' . htmlspecialchars($facture['motif']) . '</p>
            <p><strong>Date de début :</strong> ' . htmlspecialchars($facture['date_debut']) . '</p>
            <p><strong>Date de fin :</strong> ' . htmlspecialchars($facture['date_fin']) . '</p>
            <p><strong>Nombre de jours :</strong> ' . htmlspecialchars($facture['nombre_jours']) . '</p>
        ';
        
        // Écrire le contenu HTML dans le PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Générer et envoyer le PDF au navigateur
        $pdf->Output('Facture_' . $facture['id_facture'] . '.pdf', 'I'); // 'I' pour afficher dans le navigateur, 'D' pour forcer le téléchargement
    } else {
        echo "Aucune facture trouvée avec cet ID.";
    }
} else {
    echo "ID de la facture non spécifié.";
}
?>
