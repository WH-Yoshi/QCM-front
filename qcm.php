<?php
session_start();
$db = require('./scripts/db.php');
if (!isset($_SESSION['identifiant'])) {
    $_SESSION['message'] = "Vous devez vous connecter pour accéder à cette page";
    header("Location: ./connection.php");
    exit();
}
$_SESSION['examChoiceID'] = $_POST['examenID'];
if ($_SESSION['examChoiceID'] == 98) {
    $_SESSION['message'] = "Vous devez choisir un examen";
    header("Location: ./menu.php");
    exit();
} else if ($_SESSION['examChoiceID'] == 99) {
    $_SESSION['message'] = "Vous avez effectué tous les examens";
    header("Location: ./menu.php");
    exit();
}
$sql = "SELECT COUNT(questionID) FROM QUESTION WHERE qcm_ID = :valeur";
try {
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':valeur', $_SESSION['examChoiceID']);
    $stmt->execute();
    $totalQuestions = $stmt->fetch();
} catch (PDOException $e) {
    echo "Error coming from the database : " . $e->getMessage();
}
$halfQuestions = round($totalQuestions[0] / 2);
$sql = "SELECT * FROM QUESTION WHERE qcm_ID = :valeur ORDER BY RAND() LIMIT :totalQuestions;";
try {
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':valeur', $_SESSION['examChoiceID']);
    $stmt->bindParam(':totalQuestions', $halfQuestions, PDO::PARAM_INT);
    $stmt->execute();
    $questioncontentList = $stmt->fetchAll($mode = PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error taking questions content : " . $e->getMessage();
}
$sql = "INSERT INTO EXAMEN (utilisateur_ID, qcm_ID, Etat, Resultat, NbQuestions) VALUES (:U_ID, :QCM_ID, 'en cours','null',:Nbquestion)
        ON DUPLICATE KEY UPDATE 
        Etat = VALUES(Etat), 
        Resultat = VALUES(Resultat), 
        NbQuestions = VALUES(NbQuestions)";
try {
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':U_ID', $_SESSION['userID']);
    $stmt->bindParam(':QCM_ID', $_SESSION['examChoiceID']);
    $stmt->bindParam(':Nbquestion', $halfQuestions);
    $stmt->execute();
} catch (PDOException $e) {
    echo "Error coming from the database : " . $e->getMessage();
}
$sql = "SELECT examenID FROM EXAMEN WHERE utilisateur_ID = :U_ID AND qcm_ID = :QCM_ID";
try {
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':U_ID', $_SESSION['userID']);
    $stmt->bindParam(':QCM_ID', $_SESSION['examChoiceID']);
    $stmt->execute();
    $examen = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($examen) {
        $_SESSION['examenID'] = $examen['examenID'];
    }
} catch (PDOException $e) {
    echo "Error coming from the database : " . $e->getMessage();
}
$_SESSION['NbQuestions'] = $totalQuestions[0]/2;
$questionReponses = array();
foreach ($questioncontentList as $question) {
    $sql = "SELECT * FROM REPONSE WHERE question_ID = :question_ID ORDER BY RAND()";
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':question_ID', $question['questionID']);
        $stmt->execute();
        $reponses = $stmt->fetchAll($mode = PDO::FETCH_ASSOC);
        $questionReponses[$question['questionID']] = $reponses;
    } catch (PDOException $e) {
        echo "Error coming from the database: " . $e->getMessage();
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
<body class="longpage">
<h3 id="timer"></h3>
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
    <section id="qcm-exam">
        <h1><?php $sql = "SELECT Titre FROM QCM WHERE qcmID = :Valeur;";
            try {
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':Valeur', $_SESSION['examChoiceID']);
                $stmt->execute();
                $examen = $stmt->fetch();
                $_SESSION['examTitle'] = $examen['Titre'];
            } catch (PDOException $e) {
                echo "Error coming from the database : " . $e->getMessage();
            }
            echo "QCM : ".$examen['Titre']; ?>
        </h1>
        <form class='form-qcm' method='post' action='exit.php'>
            <?php foreach ($questioncontentList as $key => $question) {
                echo "<fieldset class='question-boxes'>
                    <legend>Question " . ($key + 1) . "</legend>
                    <h4>" . $question['Contenu'] . "</h4>
                    <ol>
                        <li>";
                $reponses = $questionReponses[$question['questionID']];
                foreach ($reponses as $index => $reponse) {
                    echo "<div class='answer'>
                        <input required type='radio' name='{$question['questionID']}' id='q{$question['questionID']}a$index' value='{$reponse['reponseID']}'>
                        <label for='q{$question['questionID']}a$index'>" . htmlspecialchars($reponse['Contenu']) . "</label></div>";
                }
                echo "<div class='answer'>
                    <input type='radio' name='{$question['questionID']}' id='q{$question['questionID']}noanswer' value='idk'>
                    <label for='q{$question['questionID']}noanswer'>Je ne sais pas</label></div>";

                echo "</li>
                    </ol>
                </fieldset>";
            }
            ?>
            <input type="submit" class="button" id="endqcm">
        </form>
    </section>
</main>
<footer>
    <img class="logo" src="images/logo.png" alt="Logo Henallux" >
</footer>
<script src="scripts/jscripts.js"></script>
</body>
</html>