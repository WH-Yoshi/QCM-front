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
    $_SESSION['message'] = "Identifiant invalide";
    header('Location: ../connection.php');
    exit();
}

$sql = "SELECT Motdepasse FROM UTILISATEUR WHERE Identifiant = :Identifiant";
$stmt = $db->prepare($sql);

$stmt->bindParam(':Identifiant', $identifiant);
$stmt->execute();

$resultats = $stmt->fetch(PDO::FETCH_ASSOC);

if (password_verify($mdp, $resultats['Motdepasse'])) {
    $_SESSION['Identifiant'] = $identifiant;
    header('Location: ../menu.php');
} else {
    $_SESSION['message'] = "Identifiant ou mot de passe invalide";
    header('Location: ../connection.php');
}
exit();
?>