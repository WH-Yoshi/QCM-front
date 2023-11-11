<?php
session_start();
require('./scripts/db.php');
if (!isset($_SESSION['identifiant'])) {
    $_SESSION['message'] = "Vous devez vous connecter pour accéder à cette page";
    header("Location: ./connection.php");
    exit();
}
// This code going to get each exam the user has done
$sql = "SELECT e.examenID,e.Resultat,e.qcm_ID,q.Titre FROM EXAMEN AS e JOIN QCM as q ON e.qcm_ID=q.qcmID WHERE e.utilisateur_ID = :U_ID";
try {
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':U_ID', $_SESSION['userID']);
    $stmt->execute();
    $userExams = $stmt->fetchAll($mode = PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error coming from the database : " . $e->getMessage();
}
function getAllExams() {
// This code is going to get each choice the user has done
foreach ($userExams as $exam) {
    $sql = "SELECT * FROM CHOIX_UTILISATEUR WHERE examen_ID = :examen_ID;";
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':examen_ID', $exam['examenID']);
        $stmt->execute();
        $userChoices = $stmt->fetchAll($mode = PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error coming from the database : " . $e->getMessage();
    }

    // Count how many good, bad and 'idk' answers the user has done
    $goodAnswer = 0;
    $badAnswer = 0;
    $idkAnswer = 0;
    foreach ($userChoices as $choice) {
        if ($choice['isCorrect'] == 1) {
            $goodAnswer++;
        } elseif ($choice['isCorrect'] == 0 && $choice['reponse_ID'] != null) {
            $badAnswer++;
        } else {
            $idkAnswer++;
        }
    }
}

    //

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
        <section class="qcm-result">
            <article>
                <h2>Résultats</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Examen</th>
                            <th>Score</th>
                            <th>Nombre de bonnes réponses</th>
                            <th>Nombre de mauvaises réponses</th>
                            <th>Nombre de réponses non répondues</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($userExams as $exam) {
                            echo "<tr>";
                            echo "<td>" . $exam['nom'] . "</td>";
                            echo "<td>" . $exam['score'] . "</td>";
                            echo "<td>" . $exam['nb_bonnes_reponses'] . "</td>";
                            echo "<td>" . $exam['nb_mauvaises_reponses'] . "</td>";
                            echo "<td>" . $exam['nb_reponses_non_repondues'] . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </article>
        </section>       
    </main>
    <footer>
        <img class="logo" src="images/logo.png" alt="Logo Henallux" >
    </footer>
    <script src="scripts/jscripts.js"></script>
</body>
</html>