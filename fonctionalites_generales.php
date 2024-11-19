<?php
// Paramètres de connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ROYAL";

try {
    // Connexion à la base de données
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

session_start();

if (!isset($_SESSION['user_id'])) { // Vérifie si l'utilisateur est connecté
    header("Location: login.php"); // Redirige vers la page de connexion
    exit();
}

$action = $_GET['action'] ?? 'client';
$reservationDetails = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($action == 'client') {
        // Insertion du client
        $stmt = $conn->prepare("INSERT INTO client (nom, postnom, prenom, sexe, email) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['nom'], $_POST['postnom'], $_POST['prenom'], $_POST['sexe'], $_POST['email']]);
    } elseif ($action == 'voiture') {
        // Insertion de la voiture
        $stmt = $conn->prepare("INSERT INTO voiture (marque, modele, categorie, imatriculation, couleur, prix_journalier) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$_POST['marque'], $_POST['modele'], $_POST['categorie'], $_POST['imatriculation'], $_POST['couleur'], $_POST['prix_journalier']]);
    } elseif ($action == 'chauffeur') {
        // Insertion du chauffeur
        $stmt = $conn->prepare("INSERT INTO chauffeur (nom, prenom, sexe) VALUES (?, ?, ?)");
        $stmt->execute([$_POST['nom'], $_POST['prenom'], $_POST['sexe']]);
    } elseif ($action == 'reservation') {
        // Calcul du nombre de jours de la réservation
        $nombre_jours = (new DateTime($_POST['date_debut']))->diff(new DateTime($_POST['date_fin']))->days;

        try {
            // Requête d'insertion dans la table `reservation`
            $stmt = $conn->prepare("INSERT INTO reservation (date_debut, date_fin, client_id, voiture_id, chauffeur_id, montant_paye, caution, motif, nombre_jours) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $_POST['date_debut'],
                $_POST['date_fin'],
                $_POST['client_id'],
                $_POST['voiture_id'],
                $_POST['chauffeur_id'] ?: null,
                $_POST['montant_paye'],
                $_POST['caution'],
                $_POST['motif'],
                $nombre_jours
            ]);
            
        
            // Récupérer l'ID de la dernière réservation insérée
            $reservationId = $conn->lastInsertId();
        
            // Récupération des détails complets de la réservation
            $detailsStmt = $conn->prepare("SELECT
                    reservation.date_debut, reservation.date_fin, reservation.motif, reservation.montant_paye, reservation.caution,
                    client.nom AS client_nom, client.prenom AS client_prenom,
                    voiture.marque AS voiture_marque, voiture.modele AS voiture_modele,
                    chauffeur.nom AS chauffeur_nom, chauffeur.prenom AS chauffeur_prenom
                FROM reservation
                LEFT JOIN client ON reservation.client_id = client.id
                LEFT JOIN voiture ON reservation.voiture_id = voiture.id
                LEFT JOIN chauffeur ON reservation.chauffeur_id = chauffeur.id_chauffeur
                WHERE reservation.id_reservation = ?");
            $detailsStmt->execute([$reservationId]);
            $reservationDetails = $detailsStmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de l'insertion : " . $e->getMessage();
        }
    }
}

// Récupération des données pour les formulaires de sélection
$clients = $conn->query("SELECT * FROM client")->fetchAll(PDO::FETCH_ASSOC);
$voitures = $conn->query("SELECT * FROM voiture")->fetchAll(PDO::FETCH_ASSOC);
$chauffeurs = $conn->query("SELECT * FROM chauffeur")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 800px; }
        .form-section { margin-top: 20px; }
        .reservation-table {
            margin-top: 40px;
            font-size: 0.9em; /* Réduit la taille de la police */
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>

<body>
<div id="loading" class="loading">
    <img src="spinner.gif" alt="Chargement...">
</div>

<style>
        /* Styles généraux */
        body {
            font-family: Arial, sans-serif;
            color: #343a40;
            background: #fff;
            opacity: 0.9; /* Ajoute de la transparence pour voir l'image derrière */

        }
        header {
            background: linear-gradient(90deg, #212529, #343a40);
            padding: 20px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }
        header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 1.75rem;
            font-weight: 700;
        }
        header a {
            color: #f8f9fa;
            text-decoration: none;
        }
        nav.navbar {
            background-color: #f8f9fa;
        }
        .navbar-nav .nav-link {
            font-weight: 500;
            color: #343a40;
        }
        .promo-section {
            background: url('george-barros-3ZdKPcSQRIs-unsplash.jpg') center/cover no-repeat;
            color: #fff;
            padding: 100px 0;
            text-align: center;
        }
        .promo-section h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .promo-section p {
            font-size: 1.25rem;
            max-width: 600px;
            margin: auto;
        }
        .gallery-section {
            padding: 60px 0;
        }
        .gallery-item {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        .gallery-item img {
            width: 100%;
            height: auto;
        }
        .gallery-item:hover {
            transform: scale(1.05);
        }
        .gallery-item h4 {
            font-size: 1.25rem;
            font-weight: 600;
            color: #343a40;
        }
        .gallery-item p {
            font-size: 0.9rem;
            color: #666;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        footer {
            background-color: #333;
            color: #ccc;
            padding: 40px 0;
            text-align: center;
        }
        footer a {
            color: #ccc;
        }
        footer a:hover {
            color: #ffffff;
        }
        .navbar-nav .nav-link i {
            font-size: 1.5rem;
        }
        .btn-primary {
    transition: transform 0.3s ease, background-color 0.3s ease;
}

.btn-primary:hover {
    transform: scale(1.1);
    background-color: #0056b3;
}
.form-control {
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 8px rgba(0, 123, 255, 0.5);
}
.fade-in {
    opacity: 0;
    animation: fadeIn 1s forwards;
}

@keyframes fadeIn {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}
.gallery-item {
    transition: transform 0.3s ease;
}

.gallery-item:hover {
    transform: scale(1.05);
}
.loading {
    display: none;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

    </style>
</head>
<body>

    <!-- En-tête -->
    <header class="py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <h1>Royal-trans - 12 ans d’expertise</h1>
            <div>
                <a href="index.php" class="btn btn-danger">Déconnexion</a>
                <a href="debut.php" class="btn btn-danger">Retour</a>
            </div>
       
    </header>

    <!-- Barre de navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">Royal-Trans</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
<div class="container mt-5"> 
    <h2>Gestion - Ajouter  <?= ucfirst($action) ?></h2>
    <nav class="mb-4">
        <a href="?action=client" class="btn btn-outline-primary">Ajouter un Client</a>
        <a href="?action=voiture" class="btn btn-outline-primary">Ajouter une Voiture</a>
        <a href="?action=chauffeur" class="btn btn-outline-primary">Ajouter un Chauffeur</a>
        <a href="?action=reservation" class="btn btn-outline-primary">Ajouter une Réservation</a>
    </nav>

    <?php if ($action == 'client'): ?>
        <!-- Formulaire Client -->
        <form method="post" class="form-section">
            <div class="mb-3">
                <label>Nom</label>
                <input type="text" name="nom" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Postnom</label>
                <input type="text" name="postnom" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Prénom</label>
                <input type="text" name="prenom" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Sexe</label>
                <select name="sexe" class="form-select" required>
                    <option value="M">Masculin</option>
                    <option value="F">Féminin</option>
                </select>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter Client</button>
        </form>
    <?php elseif ($action == 'voiture'): ?>
        <!-- Formulaire Voiture -->
        <form method="post" class="form-section">
            <div class="mb-3">
                <label>Marque</label>
                <input type="text" name="marque" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Modèle</label>
                <input type="text" name="modele" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Catégorie</label>
                <input type="text" name="categorie" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Immatriculation</label>
                <input type="text" name="imatriculation" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Couleur</label>
                <input type="text" name="couleur" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Prix Journalier</label>
                <input type="number" name="prix_journalier" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter Voiture</button>
        </form>
    <?php elseif ($action == 'chauffeur'): ?>
        <!-- Formulaire Chauffeur -->
        <form method="post" class="form-section">
            <div class="mb-3">
                <label>Nom</label>
                <input type="text" name="nom" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Prénom</label>
                <input type="text" name="prenom" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Sexe</label>
                <select name="sexe" class="form-select" required>
                    <option value="M">Masculin</option>
                    <option value="F">Féminin</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter Chauffeur</button>
        </form>
    <?php elseif ($action == 'reservation'): ?>
        <!-- Formulaire Réservation -->
        <form method="post" class="form-section">
            <div class="mb-3">
                <label>Client</label>
                <select name="client_id" class="form-select" required>
                    <?php foreach ($clients as $client): ?>
                        <option value="<?= $client['id'] ?>"><?= $client['nom'] ?> <?= $client['prenom'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Voiture</label>
                <select name="voiture_id" class="form-select" required>
                    <?php foreach ($voitures as $voiture): ?>
                        <option value="<?= $voiture['id'] ?>"><?= $voiture['marque'] ?> - <?= $voiture['modele'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Chauffeur</label>
                <select name="chauffeur_id" class="form-select">
                    <option value="">Pas de chauffeur</option>
                    <?php foreach ($chauffeurs as $chauffeur): ?>
                        <option value="<?= $chauffeur['id_chauffeur'] ?>"><?= $chauffeur['nom'] ?> <?= $chauffeur['prenom'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Date de début</label>
                <input type="date" name="date_debut" class="form-control" required>
            </div>
        
            <div class="mb-3">
                <label>Date de fin</label>
                <input type="date" name="date_fin" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Montant à payer</label>
                <input type="number" name="montant_paye" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Caution</label>
                <input type="number" name="caution" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Destination</label>
                <textarea name="motif" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter Réservation</button>
        </form>
    <?php endif; ?>
    <br><br><br><br>
    <br><br><br><br>


    <!-- Affichage des détails de la réservation -->
    <?php if ($reservationDetails): ?>
       <fieldset>
        <legend><center> <big><big>
                            <h6><u><big><big>FACTURE N°......</big></big></u></h6>
            </big></big></center></legend>
        <div class="reservation-table">
            <center>            <img while="100" height="100" src="Royal.jpg" alt="" srcset="">
            </center> <BR></BR>
            
            <table class="table table-bordered">
                <tr>
                    <th>Nom du Client</th>
                    <td><center><big><?= htmlspecialchars($reservationDetails['client_nom'] . ' ' . $reservationDetails['client_prenom']) ?></big></center></td>
                </tr>
                <tr>
                    <th>Voiture</th>
                    <td><center><big><?= htmlspecialchars($reservationDetails['voiture_marque'] . ' ' . $reservationDetails['voiture_modele']) ?></big></center></td>
                </tr>
                <tr>
                    <th>Chauffeur</th>
                    <td><center><big><?= htmlspecialchars($reservationDetails['chauffeur_nom'] . ' ' . $reservationDetails['chauffeur_prenom']) ?></big></center></td>
                </tr>
                <tr>
                    <th>Du</th>
                    <td><center><big><?= htmlspecialchars($reservationDetails['date_debut']) ?></big></center></td>
                </tr>
                <tr>
                    <th>Au</th>
                    <td><center><big><?= htmlspecialchars($reservationDetails['date_fin']) ?></big></center></td>
                </tr>
               
    
                <tr>
                    <th>Destination</th>
                    <td><center><big><?= htmlspecialchars($reservationDetails['motif']) ?></big></center></td>
                </tr>
                <tr>
                    <th>Montant à payer</th>
                    <td><center><big><?= htmlspecialchars($reservationDetails['montant_paye']) ?></big></center></td>
                </tr>
                <tr>
                    <th>Caution</th>
                    <td><center><big><?= htmlspecialchars($reservationDetails['caution']) ?></big></center></td>
                </tr>
            </table>
            <a href="javascript:window.print()" class="btn btn-primary no-print">Imprimer</a>
        </div>
       </fieldset>
       <h3> Signature:</h3>
    <?php endif; ?>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
    .reservation-table {
    margin-top: 40px;
    background-color: #f9f9f9;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
.reservation-table h3 {
    margin-bottom: 20px;
}
.table {
    font-size: 0.95rem;
}
.table th {
    background-image: url('Royal.jpg'); /* Chemin de votre image */
    color: #fff;
}

</style>
<br><br><br><br>
<br><br><br><br>
<br><br><br><br>
<br><br><br><br>
<br><br><br><br>
<br><br><br><br>
<footer>
        <div class="container">
            <p>&copy; 2024 ROYAL TRANS. </p>
         <p>développé par : Ir.BOYIMBA NGOSO Chrstal & Ir NSIKU MAKAYA Julio</p>
         <p></p>
        </div>
    </footer>
</body>
</html>
