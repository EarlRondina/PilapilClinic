const wrapper = document.querySelector(".wrapper");
const loginLink = document.querySelector(".login-link");
const registerLink = document.querySelector(".register-link");
const btnPopup = document.querySelector(".btnLogin-popup");
const iconClose = document.querySelector(".icon-close");
const logo = document.querySelector(".logo");

registerLink.addEventListener("click", () => {
	wrapper.classList.add("active");
});

loginLink.addEventListener("click", () => {
	wrapper.classList.remove("active");
});

btnPopup.addEventListener("click", () => {
	wrapper.classList.add("active-popup");
	btnPopup.classList.add("active-popup");
	logo.classList.add("active-popup");
});

iconClose.addEventListener("click", () => {
	wrapper.classList.remove("active-popup");
	btnPopup.classList.remove("active-popup");
	logo.classList.remove("active-popup");
});
