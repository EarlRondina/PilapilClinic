const Profile = document.querySelector(".Profile-drop");
const Drop = document.querySelector(".drop-icon");

Drop.addEventListener("click", () => {
	Profile.classList.toggle("active");
});
