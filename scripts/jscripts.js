// const width = document.getElementById('nameofpage').offsetWidth;
// document.getElementsByClassName('logo')[0].style.marginRight = width - 77 + "px";

const vw = document.documentElement.clientWidth;
const padding = 25;
const width = document.getElementById('nameofpage1').offsetWidth;
document.getElementById('nameofpage1').style.marginRight = Math.round((vw/2) - (width/2) - padding) + "px";

function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(e) {
    if (!e.target.matches('.dropbtn')) {
        var myDropdown = document.getElementById("myDropdown");
        if (myDropdown.classList.contains('show')) {
            myDropdown.classList.remove('show');
        }
    }
}