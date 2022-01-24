<section id="updateAccount" class="updateAccount">
    <article class="mainContent">
        <div class="column">
            <h1>Modifier mon compte</h1>
            
            <?php if( isset( $_GET['error'] ) ) : ?>
                <p class="error column"><?= htmlspecialchars($_GET['error']) ?></p>
            <?php endif; ?>
            
            <?php if( isset( $_GET['success'] ) ) : ?>
                <p class="success column"><?= htmlspecialchars($_GET['success']) ?></p>
            <?php endif; ?>
            
            <form action="index.php?route=updateAccount" method="POST" enctype="multipart/form-data">

                <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']) ?>" hidden>
                
                <fieldset>
                    <legend>Changer l'avatar :</legend>
                    <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar"></img>
                    <div>
                        <input type="file" name= "avatar">
                        <p>Uniquement .jpg, .gif et .png sont autorisés.</p>
                        <p>Taille maximum autorisée : 2 Mo</p>
                    </div>
                </fieldset>
                
                <fieldset>
                    <legend>Changer l'adresse mail :</legend>
                    <input type="text" name= "email" placeholder="<?= htmlspecialchars($user['email']) ?>">
                </fieldset>

                <fieldset>
                    <legend>Changer le mot de passe :</legend>
                    <input class="password" type="password" name="currentPassword" placeholder="Mot de passe actuel">
                    <input type="password" name="password" placeholder="Nouveau mot de passe">
                    <input type="password" name="passwordConfirm" placeholder="Confirmez le nouveau mot de passe">
                </fieldset>
                
                <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>"/>
                <p><span>Champ obligatoire</span></p>
                <div class="column">
                    <button class="btn" type="submit">Modifier mon compte</button>
                </div>
            </form>
        </div>
    </article>
</section>