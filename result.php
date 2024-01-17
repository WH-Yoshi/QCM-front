<?php
session_start();
$db = require('./scripts/db.php');
/*if (!isset($_SESSION['identifiant'])) {
    $_SESSION['message'] = "Vous devez vous connecter pour accéder à cette page";
    header("Location: ./connection.php");
    exit();
}*/
// This code going to get each exam the user has done
$sql = "SELECT e.NbQuestions,e.examenID,e.Resultat,e.qcm_ID,q.Titre FROM EXAMEN AS e JOIN QCM as q ON e.qcm_ID=q.qcmID WHERE e.utilisateur_ID = :U_ID";
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
    $sql = "SELECT isCorrect, examen_ID, reponse_ID FROM CHOIX_UTILISATEUR WHERE examen_ID = :examen_ID;";
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':examen_ID', $exam['examenID']);
        $stmt->execute();
        $userChoice = $stmt->fetchAll($mode = PDO::FETCH_ASSOC);

        // Si des résultats sont trouvés, mettez-les dans l'array
        if (!empty($userChoice)) {
            $userChoices[$exam['examenID']] = $userChoice;
        } else {
            // Si aucun résultat n'est trouvé, répétez les valeurs par défaut
            $userChoices[$exam['examenID']] = array_fill(0, round($exam['NbQuestions']), array('isCorrect' => 0, 'examen_ID' => $exam['examenID'], 'reponse_ID' => null));
        }
    } catch (PDOException $e) {
        echo "Error coming from the database : " . $e->getMessage();
    }
}


$choiceAnswers = array();
foreach ($userChoices as $userChoice) {
    $idkAnswers = 0;
    $goodAnswers = 0;
    $badAnswers = 0;
    foreach ($userChoice as $item) {
        if ($item['isCorrect'] == 0 && $item['reponse_ID'] == null) {
            $idkAnswers++;
        } elseif ($item['isCorrect'] == 0) {
            $badAnswers++;
        } else {
            $goodAnswers++;
        }
        $choiceAnswers[$item['examen_ID']] = array('idk' => $idkAnswers, 'good' => $goodAnswers, 'bad' => $badAnswers);
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
    <main style="position: relative" id="results">
        <a href='./menu.php' class='button' style='position: absolute; top: 20px; left: 20px'><i class="fa-solid fa-chevron-left"></i>Menu</a>
        <h1>Résultats de <?php echo $_SESSION['identifiant'] ?></h1>
        <section id="qcm-result">
            <?php
            foreach ($userExams as $exam) {
                echo '<div class="result">
                <div id="onleft">
                    <h3>' . htmlspecialchars($exam['Titre']) . '</h3>
                    <p>Vous avez obtenu ' . $exam['Resultat'] . '/' . $exam['NbQuestions'] . '</p>
                </div>
                <div id="onright">
                    <p>Bonnes reponses : ' . $choiceAnswers[$exam['examenID']]['good'] . '</p>
                    <p>Mauvaises reponses : ' . $choiceAnswers[$exam['examenID']]['bad'] . '</p>
                    <p>Questions non repondues : ' . $choiceAnswers[$exam['examenID']]['idk'] . '</p>
                    <p class="next"><a href="./details.php?examID=' . $exam['examenID'] . '">Voir les détails</a></p>
                </div>
                </div>';
            }
            ?>
        </section>
    </main>
    <footer>
        <img class="logo" src="images/logo.png" alt="Logo Henallux" >
    </footer>
</body>
</html>