import {sessionLayoutToggle} from "./ajaxFunctions";

window.onload = () => {
    let layoutToggle = document.getElementById("darkmodeToggleButton");
    if (layoutToggle) {
        layoutToggle.addEventListener("click", setLayout);
        let isIg = (layoutToggle.getAttribute("toggle")==="true");

        function switchLayout() {
            let instagramContainer = document.querySelector(".instagram-container")
            instagramContainer.classList.toggle("hidden")
            let defaultContainer = document.querySelector(".default-container")
            defaultContainer.classList.toggle("hidden")
            let newImageFormContainer = document.querySelector(".new-image-form-container")
            if (newImageFormContainer) {
                newImageFormContainer.classList.toggle("hidden")
            }
            let albumFormsContainer = document.querySelector(".album-forms-container")
            if (albumFormsContainer) {
                albumFormsContainer.classList.toggle("hidden")
            }
        }

        function setLayout() {
            if (!isIg) {
                layoutToggle.classList.replace("fa-toggle-off", "fa-toggle-on");
                sessionLayoutToggle(true);
                isIg = true;
                switchLayout()
            } else {
                layoutToggle.classList.replace("fa-toggle-on", "fa-toggle-off");
                sessionLayoutToggle(false);
                isIg = false;
                switchLayout()
            }
        }
    }
}