<style>
    .layout-header {
        display: none;
    }
</style>
<div class="layout-login">

    <h1>Connexion</h1>

    <form method="post">

        <fieldset>            
            <legend>Identifiez-vous</legend>
            <p class="layout-message"><?= @$message ?></p>
            <p>
                <label>Login</label>
                <input type="text" name="login" value="<?= @$login ?>" required>
            </p>

            <p>
                <label>Mot de passe</label>
                <input type="password" name="password" value="<?= @$password ?>" required>
            </p>

            <p class="right">
                <button type="submit" name="action" value="sign-in">Connexion</button>
            </p>

        </fieldset>

    </form>

</div>