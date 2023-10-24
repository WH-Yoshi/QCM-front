<?php
session_start();
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
</head>

<body>
    <header>
        <nav>
            <img src="images/logo.png" alt="Logo Henallux" >
            <h3>QCM - Technologie WEB</h3>
        </nav>
    </header>
    <main>
        <section class="qcm-exam">
            <article class="question-list">
                <h2>QCM : L'informatique de base</h2>
                <form id="form-qcm" method="post" action="./scripts/qcm.php">
                    <fieldset class="question-boxes">
                        <legend>Question 1</legend>
                        <ol>
                            <li>
                                <h4>Quelle est la signification de l'acronyme "CPU" en informatique ?</h4>
                                <div class="answer">
                                    <input type="radio" name="q1" id="q1a1" value="q1a1">
                                    <label for="q1a1">Central Processing Unit</label>
                                </div>
                                <div class="answer">
                                    <input type="radio" name="q1" id="q1a2" value="q1a2">
                                    <label for="q1a2">Computer Peripheral Unit</label>
                                </div>
                                <div class="answer">
                                    <input type="radio" name="q1" id="q1a3" value="q1a3">
                                    <label for="q1a3"> Control Panel Utility</label>
                                </div>
                                <div class="answer">
                                    <input type="radio" name="q1" id="q1a4" value="q1a4">
                                    <label for="q1a4">Central Power Unit</label>
                                </div>
                            </li>
                        </ol>
                    </fieldset>
                </form>
            </article>
        </section>  
    </main>
    <footer>
        <img src="images/logo.png" alt="Logo Henallux" >
    </footer>
</body>
</html>