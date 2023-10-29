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
$sql = "SELECT QUESTION.* FROM QUESTION JOIN QCM ON QUESTION.qcm_ID = QCM.qcmID WHERE QCM.Valeur = :Valeur ORDER BY RAND() LIMIT 10";
try {
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':Valeur', $exam);
    $stmt->execute();
    $questioncontentList = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error coming from the database : " . $e->getMessage();
}
$questionReponses = array();

foreach ($questioncontentList as $question) {
    $sql = "SELECT Contenu FROM REPONSE WHERE question_ID = :question_ID";
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':question_ID', $question['questionID']);
        $stmt->execute();
        $reponses = $stmt->fetchAll();
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
    <link href="https://fonts.googleapis.com/css2?family=Manrope&family=Montserrat&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <nav>
            <img class="logo" src="images/logo.png" alt="Logo Henallux" >
            <h3>QCM - Technologie WEB</h3>
        </nav>
    </header>
    <main>
        <section class="qcm-exam">
            <h2>QCM : L'informatique de base</h2>
            <article class="question-list">
                <?php foreach ($questioncontentList as $key => $question) {
                    echo "<form class='form-qcm' method='post' action='./scripts/qcm.php'>
                            <fieldset class='question-boxes'>
                                <legend>Question " . ($key + 1) . "</legend>
                                <ol>
                                    <li>
                                        <h4>" . $question['Contenu'] . "</h4>";
                                        $reponses = $questionReponses[$question['questionID']];
                                        foreach ($reponses as $index => $reponse) {
                                            echo "<div class='answer'>
                                                <input type='radio' name='q{$question['questionID']}' id='q{$question['questionID']}a{$index}' value='q{$question['questionID']}a{$index}'>
                                                <label for='q{$question['questionID']}a{$index}'>" . $reponse['Contenu'] . "</label>
                                            </div>";
                                        }
                                    echo "</li>
                                </ol>
                            </fieldset>
                        </form>";
                }
                ?>
            </article>
        </section>  
    </main>
    <footer>
        <img class="logo" src="images/logo.png" alt="Logo Henallux" >
    </footer>
    <script src="scripts/jscripts.js"></script>
</body>
</html>