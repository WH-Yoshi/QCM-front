<?php
session_start();
$db = require('./scripts/db.php');
function error_message(){
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}
if (!isset($_SESSION['identifiant'])) {
    $_SESSION['message'] = "Vous devez vous connecter pour accéder à cette page";
    header("Location: ./connection.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Luca Abs">
    <title>Henallux QCM</title>
    <link href="styles/style.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/147d135573.js" crossorigin="anonymous"></script>
</head>
<body>
<header>
    <div class="leftnav">
        <img class="logo" src="images/logo.png" alt="Logo Henallux">
        <h2 id="nameofpage">QCM - Technologie WEB</h2>
    </div>
    <div class="dropdown">
        <button type="button" class="dropbtn">
            <i class="fa-solid fa-user"></i>
            <?php if (isset($_SESSION['Prenom'])) {
                echo "<h4>" . $_SESSION['Prenom'] . "</h4>";
            } ?>
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content" id="myDropdown">
            <a href="./scripts/logout.php">Déconnexion</a>
        </div>
    </div>
</header>
<main>
    <section id="menu">
        <h1>PROFESSEUR : MENU</h1>
        <section id="panels">
            <article class="choices">
                <div>
                    <h2>Gestion étudiants</h2>
                    <p>Vous pouvez gérer les étudiants de votre classe.</p>
                </div>
                <h4 class="error"><?php error_message();?></h4>
                <a href="./gestionetu.php" class="button">Gérer les étudiants</a>
            </article>
        </section>
    </section>
</main>
<footer>
    <img class="logo" src="images/logo.png" alt="Logo Henallux" >
</footer>
</body>
</html>