<?php
session_start();
require('./db.php');
/*$sql = "UPDATE EXAMEN SET Etat = 'fini' WHERE EXAMEN.examenID = :examID;";
try {
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':examID', $_SESSION['examenID']);
    $stmt->execute();
} catch (PDOException $e) {
    echo "Error coming from the database : " . $e->getMessage();
}
unset($_SESSION['examenID']);*/
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
            echo "Error coming from the database : " . $e->getMessage();
        }
    } else { // if the user doesn't know the answer
        $userAnswer = array(array('reponseID' => null, 'isCorrecte' => 0));
    }
    $alluserAnswer[] = $userAnswer;
    // Put into CHOIX_UTILISATEUR
    if ($userAnswer[0]['isCorrecte'] == 1) {
        $total++;
    }
    $sql = "INSERT INTO CHOIX_UTILISATEUR (examen_ID, question_ID, reponse_ID, isCorrecte) VALUES (:examen_ID, :question_ID, :reponse_ID, :isCorrecte)";
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':examen_ID', $_SESSION['examenID']);
        $stmt->bindParam(':question_ID', $key);
        $stmt->bindParam(':reponse_ID', $value);
        $stmt->bindParam(':isCorrecte', $userAnswer[0]['isCorrecte']);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Error coming from the database : " . $e->getMessage();
    }
}










function printmultidimensionalarray ($array) {
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            printmultidimensionalarray($value);
        } else {
            echo $key . " : " . $value . "<br>";
        }
    }
}
print_r($alluserAnswer);
