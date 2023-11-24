<?php
$db = require('db.php');

if (isset($_SESSION['examenID'])) {
    $sql = "SELECT Etat FROM EXAMEN WHERE utilisateur_ID = :U_ID";
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':U_ID', $_SESSION['userID']);
        $stmt->execute();
        $exam = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error coming from the database: " . $e->getMessage() . "<br>";
    }
    if(empty($exam)) {
        return;
    } else {
        if ($exam['Etat'] == 'en cours') {
            $sql = "UPDATE EXAMEN SET Etat = 'fini', Resultat = '0' WHERE utilisateur_ID = :U_ID AND Etat = 'en cours'";
            try {
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':U_ID', $_SESSION['userID']);
                $stmt->execute();
            } catch (PDOException $e) {
                echo "Error coming from the database: " . $e->getMessage() . "<br>";
            }
        }
    }
}
echo '<script src="../scripts/exitscript.js">removeRemainingTime()</script>';


