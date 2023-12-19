<?php
session_start();
$db = require('./scripts/db.php');
if (!isset($_SESSION['identifiant'])) {
    $_SESSION['message'] = "Vous devez vous connecter pour accéder à cette page";
    header("Location: ./connection.php");
    exit();
}
if ($_SESSION['examChoiceID'] == 0) {
    $_SESSION['message'] = "Vous devez choisir un examen";
    header("Location: ./menu.php");
    exit();
}
$total = 0;
$userAnswer = array();
$alluserAnswer = array();
foreach ($_POST as $key => $value) {
    // Get the user answer
    if ($value != "idk") { // if the user has answered the question
        $sql = "SELECT reponseID,isCorrecte FROM REPONSE WHERE reponseID = :reponseID";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':reponseID', $value);
            $stmt->execute();
            $userAnswer = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error getting values from DB : " . $e->getMessage();
        }
    } else { // if the user doesn't know the answer
        $userAnswer = array(array('reponseID' => null, 'isCorrecte' => 0));
    }
    $alluserAnswer[] = $userAnswer;

    // Put into CHOIX_UTILISATEUR
    if ($value == 'idk') {
        $sql = "INSERT INTO CHOIX_UTILISATEUR (examen_ID, question_ID, reponse_ID, isCorrect) VALUES (:examen_ID, :question_ID, null, :isCorrecte)";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':examen_ID', $_SESSION['examChoiceID']);
            $stmt->bindParam(':question_ID', $key);
            $stmt->bindParam(':isCorrecte', $userAnswer[0]['isCorrecte']);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error inserting to the DB : " . $e->getMessage();
        }
        continue;
    } elseif ($userAnswer[0]['isCorrecte'] == 0) {
        $total -= 0.5;
    } else {
        $total += 1;
    }
    $sql = "INSERT INTO CHOIX_UTILISATEUR (examen_ID, question_ID, reponse_ID, isCorrect) VALUES (:examen_ID, :question_ID, :reponse_ID, :isCorrecte);";
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':examen_ID', $_SESSION['examChoiceID']);
        $stmt->bindParam(':question_ID', $key);
        $stmt->bindParam(':reponse_ID', $value);
        $stmt->bindParam(':isCorrecte', $userAnswer[0]['isCorrecte']);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error coming from the database : " . $e->getMessage();
    }
}
$sql = "UPDATE EXAMEN SET Etat = 'fini', Resultat = :resultat WHERE EXAMEN.examenID = :examID;";
try {
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':resultat', $total);
    $stmt->bindParam(':examID', $_SESSION['examChoiceID']);
    $stmt->execute();
} catch (PDOException $e) {
    echo "Error coming from the database : " . $e->getMessage();
}
unset($_SESSION['examChoiceID']);
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta name="author"
          content="Luca Abs">
    <title>Henallux QCM</title>
    <link href="styles/style.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
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
    <section style="position: relative;" id="resultat">
        <a href='./menu.php' class='button' style='position: absolute; top: 20px; left: 20px'><i class="fa-solid fa-chevron-left"></i>Menu</a>
        <h1>Le resultat ont bien été envoyés à la base de données</h1>
        <fieldset class="question-boxes">
            <legend>Resultat</legend>
            <div id="qcmUserPart">
                <div id="userPart">
                    <h1><?php echo $_SESSION['Prenom'] ?></h1>
                    <h2>Utilisateur : <?php echo "<a href='./result.php'><i>".$_SESSION['identifiant']."</i></a>" ?> </h2>
                </div>
                <aside>
                    <h3>Exam :</h3>
                    <h2><?php echo $_SESSION['examTitle'] ?></h2>
                </aside>
            </div>
            <div id="redirect">
                <h2><?php echo $total; ?>/<?php echo $_SESSION['NbQuestions'] ?></h2>
                <h3><?php if($total >= round($_SESSION['NbQuestions']/2)){
                        echo "Vous avez réussi l'examen !";
                    } else {
                        echo "Vous avez raté l'examen...";
                    } ?></h3>
                <p><a href="./result.php">Appuyez ici</a> pour plus d'informations</p>
            </div>
        </fieldset>
    </section>
</main>
<footer>
    <img class="logo" src="images/logo.png" alt="Logo Henallux" >
</footer>
<script src="scripts/exitscript.js"></script>
</body>
</html>
