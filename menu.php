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
        <section id="menu" class="section-menu">
            <h2>QCM : L'informatique de base</h2>
            <section id="panels" class="section-panels">
                <article class="start">
                    <div>
                        <h5>Informations</h5>
                        <p>Cet examen se compose de 10 questions choisies au hasard sur les 20 que vous avez étudiés. Il n’y a qu’une seule reponse possible par question</p>
                    </div>
                    <a href="target" class="button">Commencer</a>
                </article>
                <article class="result">
                    <div>
                        <h5>Resultats</h5>
                        <p>Pour verifier les resultat après avoir fini l’examen appuyer ici</p>
                    </div>
                    <a href="target" class="button">Resultats</a>
                </article>              
            </section>
        </section>        
    </main>
    <footer>
        <img src="images/logo.png" alt="Logo Henallux" >
    </footer>
</body>
</html>