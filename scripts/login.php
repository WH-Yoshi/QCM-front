<?php
// session_start();
require('./db.php');

if (!isset($_POST['identifiant']) || !isset($_POST['mdp']))
{
    $_SESSION['message'] = "Veuillez remplir tous les champs";
    header('Location: ../login-page.php');
    exit();
}

$identifiant = $_POST['$identifiant'];
$mdp = $_POST['mdp'];

if (empty($identifiant)) {
    $_SESSION['message'] = "Identifiant invalide";
    header('Location: ../login-page.php');
    exit();
}

$sql = "SELECT * FROM utilisateur WHERE identitifiant = :identifiant";
$stmt = $pdo->prepare($sql);

$valeur = $identifiant;
$stmt->bindParam(':identifiant', $valeur, PDO::PARAM_STR);
$stmt->execute();

$resultats = $stmt->fetchAll();


if (password_verify($mdp, $res['mdp'])) {
    // $_SESSION['identifiant'] = $identifiant;
    header('Location: ./test.php');
    exit();
} else {
    $_SESSION['message'] = "Identifiant ou mot de passe invalide";
    header('Location: ../index.html');
    exit();
}
?>