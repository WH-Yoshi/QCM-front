<?php
session_start();
$db = require('./scripts/db.php');
if (!isset($_SESSION['identifiant'])) {
    $_SESSION['message'] = "Vous devez vous connecter pour accéder à cette page";
    header("Location: ./connection.php");
    exit();
}
// this code will show the 10 questions and the answers chosen by the user, and show also the right answer
$examID = $_GET['examID'];
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
if (empty($userChoices)) {
    echo "<h1>Vous avez abandonné l'examen</h1>";
} else {
    // To continue
}