<?php
require('./scripts/db.php');
if (isset($_SESSION['examenID'])) {
    $sql = "SELECT Etat FROM EXAMEN WHERE utilisateur_ID = :U_ID";
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':U_ID', $_SESSION['utilisateurID']);
        $stmt->execute();
        $exam = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error coming from the database: " . $e->getMessage() . "<br>";
    }
    if ($exam['Etat'] == 'en cours') {
        $sql = "DELETE FROM EXAMEN WHERE utilisateur_ID = :U_ID AND Etat = 'en cours'";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':U_ID', $_SESSION['utilisateurID']);
            $stmt->execute();
        } catch (PDOException $e) {
            echo "Error coming from the database: " . $e->getMessage() . "<br>";
        }
    }
}