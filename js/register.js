document.addEventListener("DOMContentLoaded", function() {

    const roleSelect = document.getElementById("role");
    const idField = document.getElementById("idField");
    const form = document.getElementById("registerForm");
    const emailInput = document.getElementById("email");
    const emailError = document.getElementById("emailError");

    // Afficher ou cacher le champ Identification
    function toggleIdField() {
        if (roleSelect.value === "fournisseur") {
            idField.classList.remove("hidden");
            idField.required = true;
        } else {
            idField.classList.add("hidden");
            idField.required = false;
            idField.value = "";
        }
    }

    // Appel au chargement pour gérer les valeurs déjà sélectionnées
    toggleIdField();

    // Au changement du select
    roleSelect.addEventListener("change", toggleIdField);

    // Validation email avant soumission
    form.addEventListener("submit", function(e) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!regex.test(emailInput.value)) {
            emailError.textContent = "Email invalide";
            e.preventDefault(); // Empêche l'envoi
        } else {
            emailError.textContent = "";
        }
    });

});