<?php
session_start(); // Démarrer la session

// Configuration de la base de données                             
$servername = "localhost";  
$username = "root";         
$password = "";             
$dbname = "royal";  

// Connexion à la base de données 
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Email et mot de passe par défaut
$default_email = "Admin@gmailroyal.com"; // Email par défaut
$default_password = "JCBN1234royal@gmail.com";     // Mot de passe par défaut

// Traitement du formulaire
$login_error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? ''; 
    $mdps = $_POST['mdps'] ?? ''; 

    // Vérification des identifiants par défaut
    if ($email === $default_email && $mdps === $default_password) {
        $_SESSION['user_id'] = "admin"; // ID fictif pour l'administrateur
        header("Location: debut.php");
        exit();
    } else {
        // Vérifie dans la base de données si les identifiants par défaut ne sont pas utilisés
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($mdps, $user['mdps'])) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: base_de_donnes.php");
                exit();
            } else {
                $login_error = "Mot de passe incorrect.";
            }
        } else {
            $login_error = "Aucun utilisateur trouvé avec cet email.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Christal.css">
    <title>Connexion</title>
</head>
<body>
<br><br>
<div class="login-container">
<center>
<img width="100" height="100" src="Royal.jpg" alt="" srcset="">  

</center>
<h3><center>Admin</center></h3>

    <h2>Connexion</h2>
    <form action="#" method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="mdps">Mot de passe</label>
            <input type="password" id="mdps" name="mdps" required>
        </div>
        <button type="submit">Se connecter</button>
        <?php if (!empty($login_error)) { echo "<p style='color:red;'>$login_error</p>"; } ?>
        <p><a href="#">Mot de passe oublié ?</a></p>
    </form>
</div>
<style>
    body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    display: flex;
    justify-content: center; /* Centre horizontalement */
    align-items: center;     /* Centre verticalement */
    height: 100vh;           /* Prend toute la hauteur de la fenêtre */
    margin: 0;
    animation: fadeIn 1s ease-in-out;
}

@keyframes fadeIn {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}

.login-container {
    background-color: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    width: 300px; /* Largeur fixe du formulaire */
    transform: scale(0.9); /* Animation de mise à l'échelle initiale */
    animation: scaleUp 0.5s forwards;
}

@keyframes scaleUp {
    0% {
        transform: scale(0.9);
    }
    100% {
        transform: scale(1);
    }
}

h2 {
    text-align: center;
    color: #333;
}

.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
    color: #555;
}

input[type="email"],
input[type="password"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}

button {
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3;
}

p {
    text-align: center;
}

a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

</style>
</body>
</html>
