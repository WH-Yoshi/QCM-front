<?php
session_start();
require('./db.php');

if (!isset($_POST['Identifiant']) || !isset($_POST['Motdepasse']))
{
    $_SESSION['message'] = "Veuillez remplir les champs";
    header('Location: ../connection.php');
    exit();
}

$identifiant = $_POST['Identifiant'];
$mdp = $_POST['Motdepasse'];

if (empty($identifiant)) {
    $_SESSION['message'] = "Identifiant ou mot de passe invalide";
    header('Location: ../connection.php');
    exit();
}

$sql = "SELECT Motdepasse,Prenom,Role FROM UTILISATEUR WHERE Identifiant = :Identifiant";
try {
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':Identifiant', $identifiant);
    $stmt->execute();
    $resultats = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error at statement: ". $e->getMessage();
}

if (password_verify($mdp, $resultats['Motdepasse'])) {
    $_SESSION['Identifiant'] = $identifiant;
    $_SESSION['Role'] = $resultats['Role'];
    $_SESSION['Prenom'] = $resultats['Prenom'];
    if ($_SESSION['Role'] == "admin") {
        header('Location: ../admin.php');
    } elseif ($_SESSION['Role'] == "prof") {
        header('Location: ../prof.php');
    }
    header('Location: ../menu.php');
} else {
    $_SESSION['message'] = "Identifiant ou mot de passe invalide";
    header('Location: ../connection.php');
}
exit();