let layoutToggle = document.getElementById("darkmodeToggleButton");
layoutToggle.addEventListener("click", setLayout);
let isIg = false;

let currentLayout = localStorage.getItem("layout") ? localStorage.getItem("layout") : null;
if (currentLayout) {
    if (currentLayout === "instagram") {
        setLayout();
    }
}

function switchLayout() {
    document.querySelector(".instagram-container").classList.toggle("hidden")
    document.querySelector(".default-container").classList.toggle("hidden")
    document.querySelector(".new-image-form-container").classList.toggle("hidden")
}

function setLayout() {
    if (!isIg) {
        layoutToggle.classList.replace("fa-toggle-off", "fa-toggle-on");
        isIg = true;
        localStorage.setItem("layout", "instagram");
        switchLayout()
    } else {
        layoutToggle.classList.replace("fa-toggle-on", "fa-toggle-off");
        isIg = false;
        localStorage.setItem("layout", "default");
        switchLayout()
    }
}
