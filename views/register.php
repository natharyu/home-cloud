<section id="register" class="register">
    <article class="column">
        
        <?php if( isset( $_GET['error'] ) ) : ?>
            <p class="error"><?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>

        <form  class="column registerForm" method="POST" action="index.php?route=addUser">
            <svg aria-hidden="true" role="img" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M 18.978 10.03 C 18.316 6.523 15.394 3.998 12 4 C 9.256 4 6.874 5.64 5.687 8.03 C 2.795 8.364 0.607 10.937 0.608 14 C 0.608 17.314 3.158 20 6.304 20 L 18.646 20 C 21.267 20 23.392 17.761 23.392 15 C 23.392 12.36 21.446 10.22 18.978 10.03 Z" fill="rgba(50, 130, 184, 1)" stroke-width="0.5px" style="stroke: rgb(255, 255, 255);"/>
            </svg>
            
            <h1><a href="index.php?route=home">Home-Cloud</a></h1>
            
            <input class="username" type="text" name="username" placeholder="Utilisateur">
            <input class="password" type="password" name="password" placeholder="Mot de passe">
            <input class="confirmPassword" type="password" name="confirmPassword" placeholder="Confirmer le mot de passe">
            <input class="mail" type="mail" name="mail" placeholder="Adresse mail">
            
            <button class="btn" type="submit">Créer un compte</button>  
        </form>
        
        <p>Déjà inscrit ?
            <a class="link" href="index.php?route=login">Se connecter</a>
        </p>
    </article>
</section>