<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location de Véhucules</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Styles généraux */
        body {
            font-family: Arial, sans-serif;
            color: #343a40;
            background: #f7f7f7;
            animation: fadeIn 1s ease-in;
        }
        header {
            background: linear-gradient(90deg, #212529, #343a40);
            padding: 20px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
            animation: slideInFromTop 1s ease-out;
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
            animation: slideInFromTop 1.5s ease-out;
        }
        .promo-section {
            background: url('george-barros-3ZdKPcSQRIs-unsplash.jpg') center/cover no-repeat;
            color: #fff;
            padding: 100px 0;
            text-align: center;
            animation: fadeIn 2s ease-in-out;
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
            animation: fadeIn 2.5s ease-in-out;
        }
        .gallery-item {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease;
            opacity: 0;
            animation: fadeInUp 1s forwards;
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
            transition: background-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        footer {
            background-color: #333;
            color: #ccc;
            padding: 40px 0;
            text-align: center;
            animation: fadeIn 3s ease-in-out;
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

        /* Animations */
        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        @keyframes slideInFromTop {
            0% {
                transform: translateY(-100%);
            }
            100% {
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
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
                <a class="nav-link" href="fonctionalites_generales.php"><i class="fas fa-info-circle"></i> Fonctionnalités générales</a>
                <a href="index.php" class="btn btn-danger">Déconnexion</a>
                <a href="fonctionalites_generales.php" class="btn btn-danger">La Gestion d'utilisation</a>
            </div>
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

    <!-- Section promotionnelle -->
    <section class="promo-section">
        <div class="container">
            <h2>Découvrez notre collection exclusive de Véhucules</h2>
            <a href="#gallery" class="btn btn-primary mt-4">Voir la collection</a>
        </div>
    </section>

    <!-- Galerie de véhicules -->
    <section class="gallery-section" id="gallery">
        <div class="container">
            <h3 class="text-center mb-5">Nos Véhucules Disponibles</h3>
            <div class="row">
                <!-- Véhicule 1 -->
                <div class="col-md-4 mb-4">
                    <div class="gallery-item">
                        <img src="mercedes.jpg.jpg" alt="Véhicule 1">
                        <div class="p-3">
                            <h4>Véhucule de Luxe</h4>
                            <p>Un Véhucule pour les grandes occasions.</p>
                            <button class="btn btn-primary">Louer</button>
                        </div>
                    </div>
                </div>
                <!-- Véhicule 2 -->
                <div class="col-md-4 mb-4">
                    <div class="gallery-item">
                        <img src="george-barros-3ZdKPcSQRIs-unsplash.jpg" alt="Véhicule 2">
                        <div class="p-3">
                            <h4>Voiture de qualité</h4>
                            <p>Parfaite pour une sortie inoubliable.</p>
                            <button class="btn btn-primary">Louer</button>
                        </div>
                    </div>
                </div>
                <!-- Véhicule 3 -->
                <div class="col-md-4 mb-4">
                    <div class="gallery-item">
                        <img src="toyota_rav4_suv.jpg" alt="Véhicule 3">
                        <div class="p-3">
                            <h4>Voiture idéale pour la sortie familiale</h4>
                            <p>Idéale pour une tenue décontractée.</p>
                            <button class="btn btn-primary">Louer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 Vet-Location. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
