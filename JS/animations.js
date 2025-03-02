document.addEventListener("DOMContentLoaded", function () {
    let h2Element = document.querySelector("h2");

    if (h2Element) {
        h2Element.classList.add("visible");
    } else {
        console.error("H2 element not found. Check your HTML structure.");
    }
});
