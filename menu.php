<?php
session_start();
require('./scripts/db.php');
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
</head>
<body>
    <header>
        <nav>
            <img class="logo" src="images/logo.png" alt="Logo Henallux" >
            <h2>QCM - Technologie WEB</h2>
        </nav>
    </header>
    <main>
        <section id="menu">
            <h1>QCM : MENU</h1>
            <section id="panels" class="section-panels">
                <article class="choices">
                    <div>
                        <h5>Informations</h5>
                        <p>Cet examen se compose de 10 questions choisies au hasard sur les 20 que vous avez étudiés. <br>Il n’y a qu’une seule reponse possible par question</p>
                    </div>
                    <form action="./qcm.php" class="custom-select">
                        <label for="examen">Sélectionner un examen :</label>
                        <article class="choicexam">
                            <select name="examen" id="examen">
                                <?php
                                try {
                                    $sql = "SELECT Titre FROM QCM"; // Ajustez la requête SQL selon votre structure de base de données.
                                    $stmt = $db->query($sql);

                                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='" . $row['Titre'] . "'>" . $row['Titre'] . "</option>";
                                    }
                                } catch (PDOException $e) {
                                    echo "Erreur de connexion à la base de données : " . $e->getMessage();
                                }
                                ?>
                            </select>
                            <input type="submit" value="Commencer" class="button">
                        </article>
                    </form>
                </article>
                <article class="choices">
                    <div>
                        <h5>Resultats</h5>
                        <p>Pour verifier les resultat après avoir fini l’examen appuyer ici</p>
                    </div>
                    <a href="./result.php" class="button">Resultats</a>
                </article>              
            </section>
        </section>        
    </main>
    <footer>
        <img class="logo" src="images/logo.png" alt="Logo Henallux" >
    </footer>
</body>
</html>