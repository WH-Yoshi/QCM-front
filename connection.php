<?php
session_start();
if (isset($_SESSION['examenID'])) {
    $sql = "SELECT Etat FROM EXAMEN WHERE utilisateur_ID = :U_ID";
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':U_ID', $_SESSION['utilisateurID']);
        $stmt->execute();
        $exam = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error coming from the database: " . $e->getMessage() . "<br>";
    }
    if ($exam['Etat'] == 'en cours') {
        $sql = "DELETE FROM EXAMEN WHERE utilisateur_ID = :U_ID AND Etat = 'en cours'";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':U_ID', $_SESSION['utilisateurID']);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error coming from the database: " . $e->getMessage() . "<br>";
        }
    }
}
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
</head>

<body>
    <header id="page1header">
        <img class="logo" src="images/logo.png" alt="Logo Henallux" >
        <h2 id="nameofpage1">QCM - Technique WEB</h2>
    </header>
    <main>
        <section id="connexion">
            <article class="content">
                <h1>connexion</h1>
                <form method="post" action="./scripts/login.php">
                    <input type="text" required placeholder="Identifiant" name="Identifiant" id="Identifiant">
                    <input type="password" required placeholder="Mot de passe" name="Motdepasse" id="Motdepasse">
                    <h4 class="error"><?php error_message();?></h4>
                    <input type="submit" value="Se connecter" id="button">
                </form>
            </article>
        </section>
    </main>
    <footer>
        <img class="logo" src="images/logo.png" alt="Logo Henallux" >
    </footer>
</body>
</html>