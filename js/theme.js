const html = document.documentElement;
const btn = document.getElementById("theme-toggle");
const icon = document.getElementById("theme-icon");

btn.addEventListener("click", () => {
    const isDark = html.getAttribute("data-theme") === "dark";

    if (isDark) {
        html.setAttribute("data-theme", "light");
        icon.src = "assets/icons/moon.svg"; // tu luna
    } else {
        html.setAttribute("data-theme", "dark");
        icon.src = "assets/icons/sun.svg"; // tu sol
    }
});
