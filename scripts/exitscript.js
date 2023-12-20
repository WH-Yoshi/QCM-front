localStorage.removeItem('remainingTime');

let timer = setTimeout(redirectToHome, 2 * 60 * 1000); // 2 minutes en millisecondes

function redirectToHome() {
    window.location.href = 'home.html';
}

document.addEventListener('click', function() {
    clearTimeout(timer);
    timer = setTimeout(redirectToHome, 2 * 60 * 1000);
});