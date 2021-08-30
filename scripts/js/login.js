for(const toggleLink of document.querySelectorAll(".loginToggle")){
    toggleLink.addEventListener("click", (event) => {
        event.preventDefault;
        document.querySelector("#loginPage").classList.toggle("invisible");
        document.querySelector("#registrationPage").classList.toggle("invisible");
    });
}