<section id="userModify" class="userModify">
    <article class="dashboardContent">

            <?php if( isset( $_GET['error'] ) ) : ?>
                <p class="error column"><?= htmlspecialchars($_GET['error']) ?></p>
            <?php endif; ?>
        
        <a class="btn" href="index.php?route=adminPanelUserDetails&id=<?= htmlspecialchars($user['id']) ?>"><i class="fas fa-arrow-left"></i> Retour</a>
        <form action="index.php?route=updateAccountAsAdmin" method="POST">
                
                <h2>Utilisateur : <?= htmlspecialchars($user['username']) ?></h2>

                <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" hidden>


                <fieldset>
                    <legend>Changer l'adresse mail :</legend>
                    <input type="text" name= "email" placeholder="<?= htmlspecialchars($user['email']) ?>">
                </fieldset>

                <fieldset>
                    <legend>Changer le mot de passe :</legend>
                    <input type="password" name="password" placeholder="Nouveau mot de passe">
                    <input type="password" name="passwordConfirm" placeholder="Confirmez le nouveau mot de passe">
                </fieldset>
                
                <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>"/>
                <div class="column">
                    <button class="btn-success" type="submit">Modifier l'utilisateur</button>
                </div>
            </form>

    </article>

</section>