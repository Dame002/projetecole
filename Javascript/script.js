document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("search");
    const authors = document.querySelectorAll(".auteur");

    searchInput.addEventListener("input", function () {
        const searchValue = searchInput.value.toLowerCase();

        authors.forEach(auteur => {
            const name = auteur.getAttribute("data-name").toLowerCase();
            if (name.includes(searchValue)) {
                auteur.style.display = "block";
            } else {
                auteur.style.display = "none";
            }
        });
    });
});
