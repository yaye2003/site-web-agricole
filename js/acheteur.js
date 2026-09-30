// Bouton Plus/Moins
document.querySelectorAll(".toggle-desc").forEach(btn => {
    btn.addEventListener("click", () => {
        const card = btn.closest(".produit-card");
        const desc = card.querySelector(".proprietaire-longue");
        if (desc.style.display === "none" || desc.style.display === "") {
            desc.style.display = "block";
            btn.textContent = "Moins";
        } else {
            desc.style.display = "none";
            btn.textContent = "Plus";
        }
    });
});

// Bouton Commander + case à cocher
document.querySelectorAll(".commande-form").forEach(form => {
    form.addEventListener("submit", function(e) {
        e.preventDefault();

        const checkbox = this.previousElementSibling.querySelector(".produit-commandé");

        fetch(this.action, {
                method: 'POST',
                body: new FormData(this)
            })
            .then(response => response.text())
            .then(data => {
                checkbox.checked = true;
                alert("Produit commandé !");
            })
            .catch(err => console.error(err));
    });
});