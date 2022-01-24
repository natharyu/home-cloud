  <section id="myAccount" class="myAccount">
      <article class="mainContent">
        <h1 class="column">Bienvenue !</h1>
        <div class="mainAccount">
          <div class="leftSide">
            <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar"></img>
            <p>Utilisateur : <?= htmlspecialchars($user['username']) ?></p>
            <p>Email : <?= htmlspecialchars($user['email']) ?></p>
            
          </div>
          <div class="rightSide">
            <p>Nombre de fichiers total (dossiers y compris) : <?= htmlspecialchars($nbOfFiles) ?></p>
            <p>Taille totale de tous les fichiers : <?= htmlspecialchars($totalFilesSize) ?></p>
            
            <form method='POST' action='index.php?route=downloadZip&id=<?= htmlspecialchars($user['id']) ?>'>
             <button class="btn-warning" type='submit' name='create'>Telecharger l'intégralité de mes fichiers</button>
            </form>
            
            <button id="DeleteMyAccount" class="btn-danger" type='submit' name='delete' data-user="<?= htmlspecialchars($user['id']) ?>">Supprimer Mon compte</button>
          </div>
        </div>
      </article>
      
      <article class="mainContent">
        <p class="column">Si vous souhaitez modifier votre compte, cliquez sur le bouton ci-dessous</p>
        <a class="btn column" href="index.php?route=modifyAccount">Modifier mon compte</a>
      </article>
  </section>

