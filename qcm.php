<?php
session_start();
require('./scripts/db.php');
if (!isset($_SESSION['Identifiant'])) {
    $_SESSION['message'] = "Vous devez vous connecter pour accéder à cette page";
    header("Location: ./connection.php");
    exit();
}
$exam = $_GET['examen'];
if ($exam == 0) {
    $_SESSION['message'] = "Vous devez choisir un examen";
    header("Location: ./menu.php");
    exit();
}
$sql = "SELECT QUESTION.* FROM QUESTION JOIN QCM ON QUESTION.qcm_ID = QCM.qcmID WHERE QCM.Valeur = 'general' ORDER BY RAND() LIMIT 10";
try {
    $stmt = $db->prepare($sql);
//    $stmt->bindParam(':Valeur', $exam);
    $stmt->execute();
    $questioncontentList = $stmt->fetchAll($mode = PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error coming from the database : " . $e->getMessage();
}
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
</head>
<body class="longpage">
<header>
    <nav>
        <img class="logo" src="images/logo.png" alt="Logo Henallux">
        <?php
        if (isset($_SESSION['Prenom'])) {
            echo $_SESSION['Prenom'] . " connecté";
        }
        ?>
        <h2 id="nameofpage">QCM - Technologie WEB</h2>
    </nav>
</header>
<main>
    <section id="qcm-exam">
        <h1><?php $sql = "SELECT Titre FROM QCM WHERE Valeur = 'general';";
            try {
                $stmt = $db->prepare($sql);
//    $stmt->bindParam(':Valeur', $exam);
                $stmt->execute();
                $examen = $stmt->fetch();
            } catch (PDOException $e) {
                echo "Error coming from the database : " . $e->getMessage();
            }
            echo "QCM : ".$examen['Titre']; ?>
        </h1>
        <form class='form-qcm' method='post' action='scripts/testreciever.php'>
            <?php foreach ($questioncontentList as $key => $question) {
                echo "<fieldset class='question-boxes'>
                    <legend>Question " . ($key + 1) . "</legend>
                    <h4>" . $question['Contenu'] . "</h4>
                    <ol>
                        <li>";
                $reponses = $questionReponses[$question['questionID']];
                foreach ($reponses as $index => $reponse) {
                    echo "<div class='answer'> <!-- Answers -->
                        <input required type='radio' name='q{$question['questionID']}' id='q{$question['questionID']}a{$index}' value='{$reponse['reponseID']}'>
                        <label for='q{$question['questionID']}a{$index}'>" . $reponse['Contenu'] . "</label></div>";
                }
                echo "<div class='answer'>
                    <input type='radio' name='q{$question['questionID']}' id='q{$question['questionID']}noanswer' value='q{$question['questionID']}noanswer'>
                    <label for='q{$question['questionID']}noanswer'>Je ne sais pas</label></div>";

                echo "</li>
                    </ol>
                </fieldset>";
            }
            ?>
            <input type="submit" value="Valider mes réponses" id="button">
        </form>
    </section>
</main>
<footer>
    <img class="logo" src="images/logo.png" alt="Logo Henallux" >
</footer>
<script src="scripts/jscripts.js"></script>
</body>
</html>