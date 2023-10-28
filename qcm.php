<?php
session_start();
require('./scripts/db.php');
if (!isset($_SESSION['Identifiant'])) {
    $_SESSION['message'] = "Vous devez vous connecter pour accéder à cette page";
    header("Location: ./connection.php");
    exit();
}

$sql = "SELECT * FROM QUESTION WHERE qcm_ID = 1 ORDER BY RAND() LIMIT 10";
try {
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error coming from the database : " . $e->getMessage();
}
$questionlist = array();
foreach ($questions as $question) {
    $sql = "SELECT * FROM Reponses WHERE question_ID = :question_ID";
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':question_ID', $question['ID']);
        $stmt->execute();
        $reponses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error coming from the database : " . $e->getMessage();
    }
    $questionlist[] = array(
        'question' => $question,
        'reponses' => $reponses
    );
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
            <img src="images/logo.png" alt="Logo Henallux" >
            <h3>QCM - Technologie WEB</h3>
        </nav>
    </header>
    <main>
        <section class="qcm-exam">
            <h2>QCM : L'informatique de base</h2>
            <article class="question-list">
                <form class="form-qcm" method="post" action="./scripts/qcm.php">
                    <fieldset class="question-boxes">
                        <legend>Question 1</legend>
                        <ol>
                            <li>
                                <h4>Quelle est la signification de l'acronyme "CPU" en informatique ?</h4>
                                <div class="answer">
                                    <input type="radio" name="q1" id="q1a1" value="q1a1">
                                    <label for="q1a1">Central Processing Unit</label>
                                </div>
                                <div class="answer">
                                    <input type="radio" name="q1" id="q1a2" value="q1a2">
                                    <label for="q1a2">Computer Peripheral Unit</label>
                                </div>
                                <div class="answer">
                                    <input type="radio" name="q1" id="q1a3" value="q1a3">
                                    <label for="q1a3"> Control Panel Utility</label>
                                </div>
                                <div class="answer">
                                    <input type="radio" name="q1" id="q1a4" value="q1a4">
                                    <label for="q1a4">Central Power Unit</label>
                                </div>
                                <div class="answer">
                                    <input type="radio" name="q1" id="q1?" value="q1?">
                                    <label for="q1?">Je ne sais pas</label>
                                </div>
                            </li>
                        </ol>
                    </fieldset>
                </form>
                <form class="form-qcm" method="post" action="./scripts/qcm.php">
                    <fieldset class="question-boxes">
                        <legend>Question 2</legend>
                        <ol>
                            <li>
                                <h4>Quel langage de programmation est principalement utilisé pour le développement d'applications mobiles sur la plateforme iOS ?</h4>
                                <div class="answer">
                                    <input type="radio" name="q2" id="q2a1" value="q2a1">
                                    <label for="q2a1">Java</label>
                                </div>
                                <div class="answer">
                                    <input type="radio" name="q2" id="q2a2" value="q2a2">
                                    <label for="q2a2">C++</label>
                                </div>
                                <div class="answer">
                                    <input type="radio" name="q2" id="q2a3" value="q2a3">
                                    <label for="q2a3">Swift</label>
                                </div>
                                <div class="answer">
                                    <input type="radio" name="q2" id="q2a4" value="q2a4">
                                    <label for="q2a4">Python</label>
                                </div>
                            </li>
                        </ol>
                    </fieldset>
                </form>
            </article>
        </section>  
    </main>
    <footer>
        <img src="images/logo.png" alt="Logo Henallux" >
    </footer>
</body>
</html>