<?php
session_start();
$db = require('./scripts/db.php');
if (!isset($_SESSION['identifiant'])) {
    $_SESSION['message'] = "Vous devez vous connecter pour accéder à cette page";
    header("Location: ./connection.php");
    exit();
}
// this code will show the 10 questions and the answers chosen by the user, and show also the right answer
$_SESSION['examID'] = $_GET['examID'];
$sql = "SELECT q.Titre FROM EXAMEN AS e JOIN QCM as q ON e.qcm_ID=q.qcmID WHERE e.examenID = :examID;";
try {
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':examID', $_SESSION['examID']);
    $stmt->execute();
    $examTitle = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error coming from the database : " . $e->getMessage();
}
$sql = "SELECT question_ID,reponse_ID FROM CHOIX_UTILISATEUR WHERE examen_ID = :examID;";
try {
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':examID', $_SESSION['examID']);
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
        echo "<section style='position: relative; width: 100%' id='abandon'>
            <a href='javascript:history.back()' class='button' style='position: absolute; top: 20px; left: 20px'><i class='fa-solid fa-chevron-left'></i>Resultats</a>
            <h1>L'examen a été abandonné</h1>
        </section>";
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
            if ($reponseID == null) {
                $qnaofuser[$questionID]['userAnswer'] = array(array('Contenu' => '"Je ne sais pas"', 'isCorrecte' => 0));
                $sql = "SELECT Contenu, isCorrecte FROM REPONSE WHERE question_ID = :questionID";
                try {
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':questionID', $questionID);
                    $stmt->execute();
                    $qnaofuser[$questionID]['otherAnswer'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    echo "Error coming from the database : " . $e->getMessage();
                }
                continue;
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
        echo "<section style='position: relative; width: 100%' id='details-exam'>
        <a href='javascript:history.back()' class='button' style='position: absolute; top: 20px; left: 20px'><i class='fa-solid fa-chevron-left'></i>Retour</a>
        <h1>Correction : " . $examTitle['Titre'] . "</h1>";
        $QNumber = 1;
        foreach ($qnaofuser as $key => $qanda) {
            echo "<fieldset class='question-boxes'>
                <legend>Question " . $QNumber . "</legend>
                <h4>" . $qanda['Contenu'] . "</h4>";
            if ($qanda['userAnswer'][0]['isCorrecte'] == 1) {
                echo "<diva>
                    <h3>Réponse choisie: <ul><li>" . htmlspecialchars($qanda['userAnswer'][0]['Contenu']) . "</li></ul></h3>
                    <i style='color: green' class='fa-solid fa-check'></i>
                </diva>";
            } else {
                echo "<diva>
                    <h3>Réponse choisie: " . htmlspecialchars($qanda['userAnswer'][0]['Contenu']) . "</h3>
                    <i style='color: darkred' class='fa-solid fa-xmark'></i>
                </diva>";
            }
            echo "<h3>Les autres réponses étaient: </h3>
                <ul>";
            foreach ($qanda['otherAnswer'] as $otherAnswer) {
                if ($otherAnswer['isCorrecte'] == 1) echo "<li>" . htmlspecialchars($otherAnswer['Contenu']) . "<i style='color: green' class='fa-solid fa-check'></i></li>";
                else echo "<li>" . htmlspecialchars($otherAnswer['Contenu']) . "</li>";
            }
            echo "</ul>
                </fieldset>";
            $QNumber++;
        }
    }
    ?>
</main>
<footer>
    <img class="logo" src="images/logo.png" alt="Logo Henallux" >
</footer>
</body>
</html>