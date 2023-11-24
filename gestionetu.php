<?php
session_start();
function print_r2($val){
    echo '<pre>';
    print_r($val);
    echo  '</pre>';
}
$db = require('./scripts/db.php');
if (!isset($_SESSION['identifiant'])) {
    $_SESSION['message'] = "Vous devez vous connecter pour accéder à cette page";
    header("Location: ./connection.php");
    exit();
}
$sql = "SELECT utilisateurID,Prenom,Identifiant FROM UTILISATEUR WHERE Role = 'etudiant';";
try {
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $etudiants = $stmt->fetchAll($mode = PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error coming from the database : " . $e->getMessage();
}
if (empty($etudiants)) {
    $_SESSION['message'] = "Il n'y a pas encore d'étudiants dans la base de données";
    header("Location: ./prof.php");
    exit();
}
$sql = "SELECT qcmID,Valeur FROM QCM;";
try {
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $qcms = $stmt->fetchAll($mode = PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error coming from the database : " . $e->getMessage();
}
if (empty($qcms)) {
    $_SESSION['message'] = "Il n'y a pas encore de QCM dans la base de données";
    header("Location: ./prof.php");
    exit();
}
foreach ($qcms as $qcm) {
    foreach ($etudiants as $index => $etudiant) { // Compter le nombre d'examens passés par l'étudiant
        $sql = "SELECT examenID FROM EXAMEN WHERE utilisateur_ID = :user AND qcm_ID = :qcmid;";
        $stmt = $db->prepare($sql);
        try {
            $stmt->bindParam(':user', $etudiant['utilisateurID']);
            $stmt->bindParam(':qcmid', $qcm['qcmID']);
            $stmt->execute();
            $etudiants[$index][$qcm['Valeur']]['NbExams'] = $stmt->fetch(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            echo "Error getting exam user from the database : " . $e->getMessage();
        }
        $sql = "SELECT count(Q.Valeur) FROM EXAMEN E JOIN QCM Q on Q.qcmID = E.qcm_ID WHERE qcm_ID = :qcmid AND utilisateur_ID = :user AND Etat = 'en cours';";
        $stmt = $db->prepare($sql);
        try {
            $stmt->bindParam(':qcmid', $qcm['qcmID']);
            $stmt->bindParam(':user', $etudiant['utilisateurID']);
            $stmt->execute();
            if($stmt->fetch(PDO::FETCH_COLUMN) == null) {
                $etudiants[$index][$qcm['Valeur']]['EnCours'] = 0;
            } else {
                $etudiants[$index][$qcm['Valeur']]['EnCours'] = true;
            }
        } catch (PDOException $e) {
            echo "Error coming from the database : " . $e->getMessage();
        }
    }
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
    <script src="https://kit.fontawesome.com/147d135573.js" crossorigin="anonymous"></script>
</head>

<body>
<header>
    <div class="leftnav">
        <img class="logo" src="images/logo.png" alt="Logo Henallux">
        <h2 id="nameofpage">QCM - Technologie WEB</h2>
    </div>
    <div class="dropdown">
        <button type="button" class="dropbtn">
            <i class="fa-solid fa-user"></i>
            <?php if (isset($_SESSION['Prenom'])) {
                echo "<h4>" . $_SESSION['Prenom'] . "</h4>";
            } ?>
            <i class="fa fa-caret-down"></i>
        </button>
        <div class="dropdown-content" id="myDropdown">
            <a href="./scripts/logout.php">Déconnexion</a>
        </div>
    </div>
</header>
<main style="position: relative" id="results">
    <a href='./prof.php' class='button' style='position: absolute; top: 20px; left: 20px'><i class="fa-solid fa-chevron-left"></i>Menu</a>
    <a class='button' style='position: absolute; bottom: 20px; margin: auto' onClick="window.location.reload();"><i class="fa-solid fa-rotate-right"></i>Rafraichir la page</a>
    <h1>Etudiants de <?php echo $_SESSION['identifiant'] ?></h1>
    <section id="qcm-result">
        <?php
        foreach ($etudiants as $etu) {
            $sql = "SELECT Titre FROM QCM WHERE qcmID = :qcmid;";
            echo '<div class="result">
                <div id="ontop">
                    <h3>' . $etu['Prenom'] . '</h3>
                    <p>Identifiant d\'étudiant: ' . $etu['Identifiant'] . '</p>
                </div>
                <div id="onbot">';
            foreach ($qcms as $qcm) {
                echo "<div class='qcm'>";
                if ($etu[$qcm['Valeur']]['NbExams'] == null) {
                    echo '<p>' . $etu['Prenom'] . ' n\'a pas encore passé d\'examen ' . $qcm['Valeur'] . '</p>';
                } elseif ($etu[$qcm['Valeur']]['NbExams'] > 0) {
                    if ($etu[$qcm['Valeur']]['EnCours'] == 1) {
                        echo '<p>' . $etu['Prenom'] . ' a un examen ' . $qcm['Valeur'] . ' en cours</p>';
                    } else {
                        echo '<p>' . $etu['Prenom'] . ' à passés l\'examen ' . $qcm['Valeur'] . '</p><a class="button" href="details.php?examID=' . $etu[$qcm['Valeur']]['NbExams'] . '">Détails</a>';
                    }
                }
                echo '</div>';
            }
                echo '</div>
            </div>';

        }
        ?>
    </section>
</main>
<footer>
    <img class="logo" src="images/logo.png" alt="Logo Henallux" >
</footer>
</body>
</html>
