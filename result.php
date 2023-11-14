<?php
session_start();
$db = require('./scripts/db.php');
/*if (!isset($_SESSION['identifiant'])) {
    $_SESSION['message'] = "Vous devez vous connecter pour accéder à cette page";
    header("Location: ./connection.php");
    exit();
}*/

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
if (empty($userExams)) {
    $_SESSION['message'] = "Vous n'avez pas encore fait d'examen";
    header("Location: ./menu.php");
    exit();
}
// This code is going to check if an exam is 'en cours'
foreach ($userExams as &$Exam) {
    if ($Exam['Resultat'] == 'null') {
        $sql = "UPDATE EXAMEN SET Etat = 'fini', Resultat = '0' WHERE utilisateur_ID = :user;";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':user', $_SESSION['userID']);
            $stmt->execute();
            $Exam['Resultat'] = '0';
        } catch (PDOException $e) {
            echo "Error coming from the database : " . $e->getMessage();
        }
    }
}
// This code is going to get each choice the user has done
$userChoices = array();
foreach ($userExams as $exam) {
    $sql = "SELECT * FROM CHOIX_UTILISATEUR WHERE examen_ID = :examen_ID;";
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':examen_ID', $exam['examenID']);
        $stmt->execute();
        $userChoice = $stmt->fetchAll($mode = PDO::FETCH_ASSOC);
        $userChoices[$exam['examenID']] = $userChoice;
    } catch (PDOException $e) {
        echo "Error coming from the database : " . $e->getMessage();
    }
}
$goodAnswer = 0;
$badAnswer = 0;
$idkAnswer = 0;
foreach ($userChoices as $choice) {
    foreach ($choice as $answer) {
        if ($answer['isCorrect'] == 1) {
            $goodAnswer++;
        } elseif ($answer['isCorrect'] == 0) {
            $badAnswer++;
        } else {
            $idkAnswer++;
        }
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
    <main id="results">
        <h1>Résultats de <?php echo $_SESSION['identifiant'] ?></h1>
        <section id="qcm-result">
            <?php
            foreach ($userExams as $exam) {
                echo '<div class="result">
                <div>
                    <h3>' . htmlspecialchars($exam['Titre']) . '</h3>
                    <p>Vous avez obtenu ' . $exam['Resultat'] . '/10</p>
                </div>
                <a href="./result.php?examID=' . $exam['examenID'] . '">Voir les détails</a>
                </div>';
            }
            print_r($userExams);
            ?>
        </section>
    </main>
    <footer>
        <img class="logo" src="images/logo.png" alt="Logo Henallux" >
    </footer>
</body>
</html>