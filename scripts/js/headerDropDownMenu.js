let menu = document.querySelector("#menu");
let headerUser = document.querySelector("#headerUser");

headerUser.addEventListener("click", (event) => {
    event.preventDefault;
    menu.classList.toggle("invisibleHidden");
    menu.classList.toggle("visible");
});