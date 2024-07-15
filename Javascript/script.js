const bookingButton = document.getElementById('booking_button');
const bookingContainer = document.getElementById('booking_container');
const overlay = document.getElementById('overlay');
const body = document.body;

bookingButton.addEventListener('click', () => {
    bookingContainer.classList.add('show');
    overlay.classList.add('show');
    body.classList.add('no-scroll');
});

overlay.addEventListener('click', () => {
    bookingContainer.classList.remove('show');
    overlay.classList.remove('show');
    body.classList.remove('no-scroll');
});

document.addEventListener("scroll", function() {
    const header = document.querySelector(".landing_header");
    if (window.scrollY > 50) {
        header.style.backgroundColor = "rgba(134, 100, 65, 0.4)"; // Background color after scrolling
    } else {
        header.style.backgroundColor = "transparent"; // Default background color
    }
});

function openInNewTab(url) {
    window.open(url, '_blank').focus();
}

document.querySelectorAll('.navigation a').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();

        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});

window.onload = function () {
    // Ensure the element exists before trying to scroll to it
    const landingPage = document.getElementById('landing_page');
    if (landingPage) {
        landingPage.scrollIntoView({ behavior: 'smooth' });
    }
};

