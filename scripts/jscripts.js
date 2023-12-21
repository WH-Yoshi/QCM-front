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
        timerElement.textContent = "Temps écoulé !";
        document.querySelector('.form-qcm').submit();
    } else {
        remainingTime--;
        localStorage.setItem('remainingTime', remainingTime);
    }
}
updateTimer();
const element = document.getElementById("endqcm");
element.addEventListener("click", clearTimer);

function clearTimer () {
    clearInterval(timerInterval);
    localStorage.removeItem('remainingTime');
}