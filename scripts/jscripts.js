/* Timer */
const timerElement = document.getElementById('timer');
const timerInterval = setInterval(updateTimer, 1000);
const examDuration = 10 * 60;
let remainingTime = localStorage.getItem('remainingTime');
if (remainingTime === null) {
    remainingTime = examDuration;
    localStorage.setItem('remainingTime', remainingTime);
}
function updateTimer() {
    const minutes = Math.floor(remainingTime / 60);
    const seconds = remainingTime % 60;

    timerElement.textContent = `Temps restant : ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

    if (remainingTime <= 0) {
        clearInterval(timerInterval);
        timerElement.textContent = "Temps écoulé, les options non choisies seront prises en compte comme 'Je ne sais pas'";
    } else {
        remainingTime--;
        localStorage.setItem('remainingTime', remainingTime);
    }
}

updateTimer();
const element = document.getElementById("myBtn");
if (element) {
    element.addEventListener("click", myFunction)
}
function myFunction () {
    clearInterval(timerInterval);
    localStorage.removeItem('remainingTime');
}

/* Before closing window */
window.addEventListener('beforeunload', function (event) {
    event.returnValue = "Vous perdrez toute progression. Continuer ?";

    fetch('scripts/examcheck.php', {
        method: 'POST'
    })
        .then(response => response.json())
        .catch(error => {
            console.error('Erreur lors de la déconnexion :', error);
        });
});
