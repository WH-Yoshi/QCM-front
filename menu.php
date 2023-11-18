<?php
session_start();
$db = require('./scripts/db.php');
if (!isset($_SESSION['identifiant'])) {
    $_SESSION['message'] = "Vous devez vous connecter pour accéder à cette page";
    header("Location: ./connection.php");
    exit();
}
require('./scripts/examcheck.php');
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
        <h1>QCM : MENU</h1>
        <section id="panels">
            <article class="choices">
                <div>
                    <h2>Examen</h2>
                    <p> L'examen que vous choisirez se composera de 10 questions choisies au hasard parmi les 20 du sujet étudié.</p>
                    <p>+1 : bonne réponse -- -0.5 : mauvaise réponse -- 0 : pas de réponse</p>
                    <p>Tout abandon mène à l'échec</p>
                </div>
                <div>
                    <h4 class="error"><?php error_message();?></h4>
                    <form method="post" action="./qcm.php" class="custom-select">
                        <label for="examen">Sélectionner un examen :</label>
                        <article>
                            <select name="examenID" id="examen">
                                <option value="0" selected>--Choisir un examen--</option>
                                <?php
                                try {
                                    $sql = "SELECT qcmID,Titre FROM QCM";
                                    $stmt = $db->query($sql);
                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='" . $row['qcmID'] . "'>" . $row['Titre'] . "</option>";
                                    }
                                } catch (PDOException $e) {
                                    echo "Erreur de connexion à la base de données : " . $e->getMessage();
                                }
                                ?>
                            </select>
                            <input type="submit" value="Commencer" class="button">
                        </article>
                    </form>
                </div>
            </article>
            <article class="choices">
                <div>
                    <h2>Resultats</h2>
                    <p>Dans la page resultats vous verrez la liste des examen que vous avez passé et leurs details</p>
                </div>
                <a href="./result.php">Resultats</a>
            </article>
        </section>
    </section>
</main>
<footer>
    <img class="logo" src="images/logo.png" alt="Logo Henallux" >
</footer>
</body>
</html>