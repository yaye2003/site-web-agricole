<div class="register-box">
    <h2>Inscription</h2>

    <form method="POST" id="registerForm">

        <input type="text" name="nom" placeholder="Nom" required>
        <input type="text" name="prenom" placeholder="Prénom" required>

        <input type="email" id="email" name="email" placeholder="Email" required>
        <div id="emailError" class="error"></div>

        <select name="role" id="role" required>
            <option value="acheteur">Acheteur</option>
            <option value="fournisseur">Fournisseur</option>
        </select>

        <!-- Champ Identification pour Fournisseur -->
        <input
            type="password"
            name="identification"
            id="idField"
            class="hidden"
            placeholder="Identification Fournisseur">

        <button type="submit">Valider</button>

        <p style="color:green"><?= $message ?? "" ?></p>
    </form>
</div>
