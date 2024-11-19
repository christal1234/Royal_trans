<?php
// Configuration de la base de données
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

// Fonctions pour les opérations CRUD

// Fonction pour récupérer les clients
function getClients($conn, $searchTerm = '') {
    $query = "SELECT * FROM client";
    if ($searchTerm) {
        $query .= " WHERE nom LIKE ? OR postnom LIKE ? OR prenom LIKE ?";
        $stmt = $conn->prepare($query);
        $stmt->execute(['%' . $searchTerm . '%', '%' . $searchTerm . '%', '%' . $searchTerm . '%']);
    } else {
        $stmt = $conn->query($query);
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les voitures
function getVoitures($conn, $searchTerm = '') {
    $query = "SELECT * FROM voiture";
    if ($searchTerm) {
        $query .= " WHERE marque LIKE ? OR modele LIKE ? OR categorie LIKE ?";
        $stmt = $conn->prepare($query);
        $stmt->execute(['%' . $searchTerm . '%', '%' . $searchTerm . '%', '%' . $searchTerm . '%']);
    } else {
        $stmt = $conn->query($query);
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les chauffeurs
function getChauffeurs($conn, $searchTerm = '') {
    $query = "SELECT * FROM chauffeur";
    if ($searchTerm) {
        $query .= " WHERE nom LIKE ? OR prenom LIKE ?";
        $stmt = $conn->prepare($query);
        $stmt->execute(['%' . $searchTerm . '%', '%' . $searchTerm . '%']);
    } else {
        $stmt = $conn->query($query);
    }
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les factures
function getFactures($conn) {
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
        JOIN voiture ON facture.voiture_id = voiture.id";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion - ROYAL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5"> <a href="debut.php" class="btn btn-danger">Retour</a>

    <!-- Affichage des clients -->
    <h2 class="animate__animated animate__fadeInDown">Liste des Clients</h2>
    <form method="POST" class="mb-3">
        <input type="text" class="form-control animate__animated animate__fadeIn" name="search_client" placeholder="Rechercher par nom, postnom ou prénom">
        <button class="btn btn-primary mt-2 animate__animated animate__bounceIn" type="submit">Rechercher</button>
    </form>
    <table class="table table-bordered animate__animated animate__fadeIn">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Postnom</th>
                <th>Prénom</th>
                <th>Sexe</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (getClients($conn, $_POST['search_client'] ?? '') as $client): ?>
                <tr>
                    <td><?= htmlspecialchars($client['id']) ?></td>
                    <td><?= htmlspecialchars($client['nom']) ?></td>
                    <td><?= htmlspecialchars($client['postnom']) ?></td>
                    <td><?= htmlspecialchars($client['prenom']) ?></td>
                    <td><?= htmlspecialchars($client['sexe']) ?></td>
                    <td><?= htmlspecialchars($client['email']) ?></td>
                    <td>
                        <a href="Modie.php?id=<?= $client['id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="Sup.php?id=<?= $client['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Affichage des voitures -->
    <h2 class="animate__animated animate__fadeInDown">Liste des Voitures</h2>
    <form method="POST" class="mb-3">
        <input type="text" class="form-control animate__animated animate__fadeIn" name="search_voiture" placeholder="Rechercher par marque, modèle ou catégorie">
        <button class="btn btn-primary mt-2 animate__animated animate__bounceIn" type="submit">Rechercher</button>
    </form>
    <table class="table table-bordered animate__animated animate__fadeIn">
        <thead>
            <tr>
                <th>ID</th>
                <th>Marque</th>
                <th>Modèle</th>
                <th>Catégorie</th>
                <th>Immatriculation</th>
                <th>Couleur</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (getVoitures($conn, $_POST['search_voiture'] ?? '') as $voiture): ?>
                <tr>
                    <td><?= htmlspecialchars($voiture['id']) ?></td>
                    <td><?= htmlspecialchars($voiture['marque']) ?></td>
                    <td><?= htmlspecialchars($voiture['modele']) ?></td>
                    <td><?= htmlspecialchars($voiture['categorie']) ?></td>
                    <td><?= htmlspecialchars($voiture['imatriculation']) ?></td>
                    <td><?= htmlspecialchars($voiture['couleur']) ?></td>
                    <td>
                        <a href="modie_voiture.php?id=<?= $voiture['id'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="supp_voiture.php?id=<?= $voiture['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette voiture ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Affichage des chauffeurs -->
    <h2 class="animate__animated animate__fadeInDown">Liste des Chauffeurs</h2>
    <form method="POST" class="mb-3">
        <input type="text" class="form-control animate__animated animate__fadeIn" name="search_chauffeur" placeholder="Rechercher par nom ou prénom">
        <button class="btn btn-primary mt-2 animate__animated animate__bounceIn" type="submit">Rechercher</button>
    </form>
    <table class="table table-bordered animate__animated animate__fadeIn">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Sexe</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach (getChauffeurs($conn, $_POST['search_chauffeur'] ?? '') as $chauffeur): ?>
                <tr>
                    <td><?= htmlspecialchars($chauffeur['id_chauffeur']) ?></td>
                    <td><?= htmlspecialchars($chauffeur['nom']) ?></td>
                    <td><?= htmlspecialchars($chauffeur['prenom']) ?></td>
                    <td><?= htmlspecialchars($chauffeur['sexe']) ?></td>
                    <td>
                        <a href="modi_chaufeur.php?id=<?= $chauffeur['id_chauffeur'] ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="sup_chauffeur.php?id=<?= $chauffeur['id_chauffeur'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce chauffeur ?')">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="christal">
    <center>
        <a href="Afficher_factur.php" class="btn btn-warning btn-lg animate__animated animate__pulse animate__infinite">Voir les réservations</a>
    </center>
</div>

<style>
    .christal {
        margin: 30px;
        padding: 20px;
        border-radius: 50px;
        color: #fff;
    }
</style>
</body>
</html>
