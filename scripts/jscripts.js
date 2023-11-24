/* Timer */
const timerElement = document.getElementById('timer');
const timerInterval = setInterval(updateTimer, 1000);
const examDuration = 5 * 60;
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
        timerElement.textContent = "Temps écoulé ! Vous devez vous deconnecter";
        disablePageInteractions();
    } else {
        remainingTime--;
        localStorage.setItem('remainingTime', remainingTime);
    }
}
function disablePageInteractions() {
    const elements = document.getElementsByTagName('input');
    for (let i = 0; i < elements.length; i++) {
        elements[i].disabled = true;
    }
    const element = document.getElementById('myBtn');
    if (element) {
        element.disabled = true;
    }
}

updateTimer();
const element = document.getElementById("endqcm");
element.addEventListener("click", clearTimer);

function clearTimer () {
    clearInterval(timerInterval);
    localStorage.removeItem('remainingTime');
}