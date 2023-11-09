
const examDuration = 10 * 60;

let remainingTime = localStorage.getItem('remainingTime');
if (remainingTime === null) {
remainingTime = examDuration;
localStorage.setItem('remainingTime', remainingTime);
}

const timerElement = document.getElementById('timer');

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
const timerInterval = setInterval(updateTimer, 1000);