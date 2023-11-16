<?php
session_start();
function print_r2($val){
    echo '<pre>';
    print_r($val);
    echo  '</pre>';
}
$db = require('./scripts/db.php');
if (!isset($_SESSION['identifiant'])) {
    $_SESSION['message'] = "Vous devez vous connecter pour accéder à cette page";
    header("Location: ./connection.php");
    exit();
}
// this code will show the 10 questions and the answers chosen by the user, and show also the right answer
$examID = $_GET['examID'];
$sql = "SELECT q.Titre FROM EXAMEN AS e JOIN QCM as q ON e.qcm_ID=q.qcmID WHERE e.examenID = :examID;";
try {
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':examID', $examID);
    $stmt->execute();
    $examTitle = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error coming from the database : " . $e->getMessage();
}
$sql = "SELECT question_ID,reponse_ID FROM CHOIX_UTILISATEUR WHERE examen_ID = :examID;";
try {
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':examID', $examID);
    $stmt->execute();
    $userChoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error coming from the database : " . $e->getMessage();
}
// If $userchoice is Empty it means that the user has abandoned the exam, and show a message in relation
// If $userchoices is not empty then show an exemple of exam with the good answer and the answers of the user

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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body class="longpage">
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
    <?php
    if (empty($userChoices)) {
        echo "<h1>Vous avez abandonné l'examen</h1>";
    } else {
        $qnaofuser = array();
        foreach ($userChoices as $choice) {
            $questionID = $choice['question_ID'];
            $reponseID = $choice['reponse_ID'];
            $sql = "SELECT Contenu FROM QUESTION WHERE questionID = :questionID;";
            try {
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':questionID', $questionID);
                $stmt->execute();
                $qnaofuser[$questionID] = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error coming from the database : " . $e->getMessage();
            }
            $sql = "SELECT Contenu,isCorrecte FROM REPONSE WHERE reponseID = :reponseID;";
            try {
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':reponseID', $reponseID);
                $stmt->execute();
                $qnaofuser[$questionID]['userAnswer'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error coming from the database : " . $e->getMessage();
            }
            $sql = "SELECT Contenu, isCorrecte FROM REPONSE WHERE question_ID = :questionID AND reponseID != :reponseID;";
            try {
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':questionID', $questionID);
                $stmt->bindParam(':reponseID', $reponseID);
                $stmt->execute();
                $qnaofuser[$questionID]['otherAnswer'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error coming from the database : " . $e->getMessage();
            }
        }
        echo "<section id='details-exam'>
        <h1>Correction : " . $examTitle['Titre'] . "</h1>";
        $QNumber = 1;
        foreach ($qnaofuser as $key => $qanda) {
            echo "<fieldset class='question-boxes'>
                <legend>Question " . $QNumber . "</legend>
                <h4>" . $qanda['Contenu'] . "</h4>";
            if ($qanda['userAnswer'][0]['isCorrecte'] == 1) {
                echo "<div>
                    <h3>Votre réponse: " . $qanda['userAnswer'][0]['Contenu'] . "</h3>
                    <i style='color: green' class='fa-solid fa-check'></i>
                </div>";
            } else {
                echo "<div>
                    <h3>Votre réponse: " . $qanda['userAnswer'][0]['Contenu'] . "</h3>
                    <i style='color: darkred' class='fa-solid fa-xmark'></i>
                </div>";
            }
            echo "<h3>Les autres réponses étaient: </h3>";
            foreach ($qanda['otherAnswer'] as $otherAnswer) {
                if ($otherAnswer['isCorrecte'] == 1) echo "<div><h5>" . $otherAnswer['Contenu'] . "</h5><i style='color: green' class='fa-solid fa-check'></i></div>";
                else echo "<h5>" . $otherAnswer['Contenu'] . "</h5>";
            }
            echo "</fieldset>";
            $QNumber++;
        }
    }
    ?>
</main>
<footer>
    <img class="logo" src="images/logo.png" alt="Logo Henallux" >
</footer>
<script src="scripts/jscripts.js"></script>
</body>
</html>