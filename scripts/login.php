<?php
session_start();
require('./db.php');

if (!isset($_POST['identifiant']) || !isset($_POST['mdp']))
{
    $_SESSION['message'] = "Veuillez remplir les champs";
    header('Location: ../index.php');
    exit();
}

$identifiant = $_POST['identifiant'];
$mdp = $_POST['mdp'];

if (empty($identifiant)) {
    $_SESSION['message'] = "Identifiant invalide";
    header('Location: ../index.php');
    exit();
}

$sql = "SELECT * FROM utilisateur WHERE Identifiant = :identifiant";
$stmt = $db->prepare($sql);

$valeur = $identifiant;
$stmt->bindParam(':identifiant', $valeur, PDO::PARAM_STR);
$stmt->execute();

$resultats = $stmt->fetch(PDO::FETCH_ASSOC);

if (password_verify($mdp, $resultats['mdp'])) {
    $_SESSION['identifiant'] = $identifiant;
    header('Location: ./test.php');
    exit();
} else {
    $_SESSION['message'] = "Identifiant ou mot de passe invalide";
    header('Location: ../index.php');
    exit();
}
?>