<?php
session_start();
function error_message(){
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" >
    <title>Henallux QCM</title>
    <link href="style/style.css" rel="stylesheet" >
    <link href="style/index.css" rel="stylesheet" >
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope&family=Montserrat&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <nav>
            <img src="images/logo.png" alt="Logo Henallux" >
            <h3>QCM - Technologie WEB</h3>
        </nav>
    </header>
    <main>
        <section id="connexion" class="section-connexion">
            <h2>Page de connexion</h2>
            <form id="form" method="post" action="./scripts/login.php">
                <label for="identifiant">Identifiant</label>
                <input type="text" name="identifiant" id="identifiant">
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password">
                <input type="submit" value="Se connecter" class="cta">
                <h4 id="error"><?php error_message();?></h4>
            </form>
        </section>
    </main>
    <footer>
        <img src="images/logo.png" alt="Logo Henallux" >
    </footer>
</body>
</html>