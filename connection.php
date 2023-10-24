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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="Luca Abs">
    <title>Henallux QCM</title>
    <link href="styles/style.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope&family=Montserrat&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <nav>
            <img src="images/logo.png" alt="Logo Henallux" >
            <h3>QCM - Technique WEB</h3>
        </nav>
    </header>
    <main>
        <section id="connexion" class="section-connexion">
            <h2>Page de connexion</h2>
            <form id="form-connexion" method="post" action="./scripts/login.php">
                <label for="Identifiant">Identifiant</label>
                <input type="text" name="Identifiant" id="Identifiant">
                <label for="Motdepasse">Mot de passe</label>
                <input type="password" name="Motdepasse" id="Motdepasse">
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