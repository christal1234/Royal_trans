<?php
// Ajouter_facture.php
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

// Initialiser les variables de message
$message = ''; 
$factureAjoutee = false; 

// Récupérer les clients, voitures, et chauffeurs
$clients = $conn->query("SELECT * FROM client")->fetchAll(PDO::FETCH_ASSOC);
$voitures = $conn->query("SELECT * FROM voiture")->fetchAll(PDO::FETCH_ASSOC);
$chauffeurs = $conn->query("SELECT * FROM chauffeur")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_id = $_POST['nom'] ?? null;
    $voiture_id = $_POST['voiture_id'] ?? null;
    $chauffeur_id = $_POST['chauffeur_id'] ?? null;
    $montant = $_POST['montant'] ?? null;
    $caution = $_POST['caution'] ?? null;
    $motif = $_POST['motif'] ?? null;
    $date_debut = $_POST['date_debut'] ?? null;
    $date_fin = $_POST['date_fin'] ?? null;

    // Vérification des valeurs
    if ($client_id && $voiture_id && $chauffeur_id && $montant && $caution && $motif && $date_debut && $date_fin) {
        // Calcul automatique du nombre de jours
        $dateDebut = new DateTime($date_debut);
        $dateFin = new DateTime($date_fin);
        $nombre_jours = $dateDebut->diff($dateFin)->days;

        // Préparer et exécuter l'insertion
        $stmt = $conn->prepare("INSERT INTO facture (client_id, voiture_id, chauffeur_id, montant, caution, motif, date_debut, date_fin, nombre_jours, date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        if ($stmt->execute([$client_id, $voiture_id, $chauffeur_id, $montant, $caution, $motif, $date_debut, $date_fin, $nombre_jours])) {
            $message = "Facture ajoutée avec succès.";
            $factureAjoutee = true;

            // Récupérer les informations du client
            $clientQuery = $conn->prepare("SELECT nom FROM client WHERE id = ?");
            $clientQuery->execute([$client_id]);
            $client = $clientQuery->fetch(PDO::FETCH_ASSOC);
            $nom_clients = $client ? $client['nom'] : 'Inconnu';

            // Récupérer les informations de la voiture (marque et couleur)
            $voitureQuery = $conn->prepare("SELECT marque, couleur FROM voiture WHERE id = ?");
            $voitureQuery->execute([$voiture_id]);
            $voiture = $voitureQuery->fetch(PDO::FETCH_ASSOC);
            $marque_voiture = $voiture ? $voiture['marque'] : 'Inconnu';
            $couleur_voiture = $voiture ? $voiture['couleur'] : 'Inconnu';
        } else {
            $errorInfo = $stmt->errorInfo();
            $message = "Erreur lors de l'ajout de la facture : " . $errorInfo[2];
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }
            .print-only {
                display: block !important;
            }
            .hide-on-print {
                display: none !important;
            }

            table {
                border-collapse: collapse;
                width: 100%;
            }

            th, td {
                border: 1px solid #000;
                padding: 12px;
                text-align: left;
            }

            h3, .footer-date {
                text-align: center;
                margin: 20px 0;
            }
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .form-section {
            border: 2px solid #007bff;
            border-radius: 10px;
            padding: 20px;
            background-color: #e9f4ff;
            box-shadow: 0px 4px 8px rgba(0, 123, 255, 0.2);
        }
        h3 {
            text-align: center;
            color: #007bff;
        }
        .footer-date {
            text-align: center;
            margin-top: 30px;
            font-weight: bold;
        }
        .btn-info {
            background-color: #007bff;
            border: none;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 12px;
            border: 1px solid #007bff;
            text-align: left;
            background-color: #f9f9f9;
            color: #333;
        }

        .table th {
            background-color: #007bff;
            color: #fff;
            text-transform: uppercase;
        }
    </style>
    <script>
        function imprimerFacture() {
            window.print();
        }
    </script>
</head>
<body>
<div class="container mt-5">
    <div class="logo-container">
        <img src="Royal.jpg" alt="Logo de l'entreprise" style="height: 80px;">
    </div>
    
    <div class="form-section no-print">
        <h3>Ajouter une Facture</h3>
        <form method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">Client</label>
                <select class="form-select" name="nom" required>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?= htmlspecialchars($client['id']) ?>"><?= htmlspecialchars($client['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="voiture_id" class="form-label">Voiture</label>
                <select class="form-select" name="voiture_id" required>
                    <?php foreach ($voitures as $voiture): ?>
                        <option value="<?= htmlspecialchars($voiture['id']) ?>">
                            <?= htmlspecialchars($voiture['marque']) ?> - 
                            <?= htmlspecialchars($voiture['modele']) ?> - 
                            Immatriculation: <?= htmlspecialchars($voiture['imatriculation']) ?> - 
                            Catégorie: <?= htmlspecialchars($voiture['categorie']) ?> - 
                            Couleur: <?= htmlspecialchars($voiture['couleur']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="chauffeur_id" class="form-label">Chauffeur</label>
                <select class="form-select" name="chauffeur_id" required>
                    <?php foreach ($chauffeurs as $chauffeur): ?>
                        <option value="<?= htmlspecialchars($chauffeur['id_chauffeur']) ?>"><?= htmlspecialchars($chauffeur['nom']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="montant" class="form-label">Montant (en FCFA)</label>
                <input type="number" class="form-control" name="montant" required>
            </div>
            <div class="mb-3">
                <label for="caution" class="form-label">Caution (en FCFA)</label>
                <input type="number" class="form-control" name="caution" required>
            </div>
            <div class="mb-3">
                <label for="motif" class="form-label">Motif</label>
                <input type="text" class="form-control" name="motif" required>
            </div>
            <div class="mb-3">
                <label for="date_debut" class="form-label">Date de Début</label>
                <input type="date" class="form-control" name="date_debut" required>
            </div>
            <div class="mb-3">
                <label for="date_fin" class="form-label">Date de Fin</label>
                <input type="date" class="form-control" name="date_fin" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <a href="Afficher_factures.php" class="btn btn-secondary">Annuler</a>
        </form>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info mt-3"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>
    </div>

    <?php if ($factureAjoutee): ?>
        <div class="mt-5 print-only">
            <h3>Détails de la Facture</h3>
            <table class="table table-bordered table-striped">
                <tr>
                    <th>Nom du Client</th>
                    <td><?= htmlspecialchars($nom_clients) ?></td>
                </tr>
                <tr>
                    <th>Marque de la Voiture</th>
                    <td><?= htmlspecialchars($marque_voiture) ?></td>
                </tr>
                <tr>
                    <th>Couleur de la Voiture</th>
                    <td><?= htmlspecialchars($couleur_voiture) ?></td>
                </tr>
                <tr>
                    <th>Montant</th>
                    <td><?= htmlspecialchars($montant) ?> FCFA</td>
                </tr>
                <tr>
                    <th>Caution</th>
                    <td><?= htmlspecialchars($caution) ?> FCFA</td>
                </tr>
                <tr>
                    <th>Motif</th>
                    <td><?= htmlspecialchars($motif) ?></td>
                </tr>
                <tr>
                    <th>Date de Début</th>
                    <td><?= htmlspecialchars($date_debut) ?></td>
                </tr>
                <tr>
                    <th>Date de Fin</th>
                    <td><?= htmlspecialchars($date_fin) ?></td>
                </tr>
            </table>
            <div class="footer-date">
                Fait à [Votre Ville], le <?= date("d/m/Y") ?>
            </div>
            <button onclick="imprimerFacture()" class="btn btn-info no-print mt-3">Imprimer la Facture</button>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
