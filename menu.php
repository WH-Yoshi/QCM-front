<?php

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
                    <h5>Informations</h5>
                    <p>Cet examen se compose de 10 questions choisies au hasard sur les 20 que vous avez étudiés. Il n’y a qu’une seule reponse possible par question</p>
                    <a href="target"><button>Commencer</button></a>
                </article>
                <article class="result">
                    <h5>Reslutats</h5>
                    <p>Pour verifier les resultat après avoir fini l’examen appuyer ici</p>
                    <a href="target"><button>Resultats</button></a>
                </article>
                <!-- <div style="width: 1596px; height: 665px; position: relative">
                    <div style="left: 514px; top: 0px; position: absolute; color: white; font-size: 40px; font-family: Inter; font-weight: 600; word-wrap: break-word">QCM : L’informatique de base </div>
                    <div style="width: 626px; height: 495px; left: 970px; top: 170px; position: absolute">
                        <div style="width: 145px; height: 39px; left: 240px; top: 51px; position: absolute; color: white; font-size: 32px; font-family: Inter; font-weight: 600; word-wrap: break-word">Resultats</div>
                        <div style="width: 626px; height: 495px; left: 0px; top: 0px; position: absolute; border: 3px black solid"></div>
                        <div style="width: 304px; height: 92px; left: 161px; top: 341px; position: absolute; background: black; border-radius: 35px"></div>
                        <div style="width: 114px; height: 39px; left: 256px; top: 367px; position: absolute; color: white; font-size: 32px; font-family: Inter; font-weight: 600; word-wrap: break-word">Vérifier</div>
                        <div style="width: 560px; left: 33px; top: 132px; position: absolute; color: white; font-size: 20px; font-family: Inter; font-weight: 600; word-wrap: break-word">Pour verifier les resultat après avoir fini l’examen appuyer ici</div>
                    </div>
                    <div style="width: 626px; height: 495px; left: 0px; top: 170px; position: absolute">
                        <div style="width: 304px; height: 92px; left: 161px; top: 341px; position: absolute; background: black; border-radius: 35px"></div>
                        <div style="width: 626px; height: 495px; left: 0px; top: 0px; position: absolute; border: 3px black solid"></div>
                    </div> -->
                </div>                
            </section>
        </section>


        <!-- <section id="connexion" class="section-connexion">
            <h2>Page de connexion</h2>
            <form id="form" method="post" action="./scripts/login.php">
                <label for="Identifiant">Identifiant</label>
                <input type="text" name="Identifiant" id="Identifiant">
                <label for="Motdepasse">Mot de passe</label>
                <input type="password" name="Motdepasse" id="Motdepasse">
                <input type="submit" value="Se connecter" class="cta">
                <h4 id="error"></h4>
            </form>
        </section> -->
        
    </main>
    <footer>
        <img src="images/logo.png" alt="Logo Henallux" >
    </footer>
</body>
</html>