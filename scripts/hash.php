<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Formulaire de hachage de mot de passe</title>
</head>
<body>
<form method="post">
    <label for="password">Mot de passe : </label>
    <input type="password" name="password" id="password" required>
    <input type="submit" name="hash" value="Hasher le mot de passe">
</form>

<?php
if (isset($_POST['hash'])) {
    $userInputPassword = $_POST['password'];
    $hashedPassword = password_hash($userInputPassword, PASSWORD_DEFAULT);

    echo "Mot de passe haché : " . $hashedPassword;
}
?>
</body>
</html>
