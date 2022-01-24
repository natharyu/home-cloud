<section id="home" class="home">
    
    
    <?php if( !isset($_SESSION['username'])) : ?>
        <?php include_once '/views/login.php' ?>
    <?php else : ?>

        <?php if( isset( $_GET['error'] ) ) : ?>
            <p class="error column"><?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>

        <?php if( isset( $_GET['success'] ) ) : ?>
            <p class="success column"><?= htmlspecialchars($_GET['success']) ?></p>
        <?php endif; ?>

        <article class="mainContent">
            <div class="homeFirstCard">
                <h3 class="column">Bienvenue sur votre stockage en ligne <?= htmlspecialchars($_SESSION['username']) ?></h3>
            </div>
        </article>

        <article class="mainContent">
            <h3 class="column">Files</h3>
            <div class="homeContent">
                <a class="files" href="index.php?route=files"><i class="fa-5x fas fa-file"></i><i class="fa-4x fas fa-file"></i>Fichiers</a>
                <a class="images" href="index.php?route=images"><i class="fa-5x fas fa-file-image"></i><i class="fa-4x fas fa-file-image"></i>Images</a>
                <a class="audio" href="index.php?route=audios"><i class="fa-5x fas fa-file-audio"></i><i class="fa-4x fas fa-file-audio"></i>Audio</a>
                <a class="video" href="index.php?route=videos"><i class="fa-5x fas fa-file-video"></i><i class="fa-4x fas fa-file-video"></i>Vidéos</a>
            </div>
        </article>

        <article class="mainContent">
            <h3 class="column">Applications</h3>
            <div class="homeContent">
                <a class="todolist" href="index.php?route=todolist"><i class="fa-5x fas fa-clipboard-list"></i><i class="fa-4x fas fa-clipboard-list"></i>TodoList</a>
                <a class="meteo" href="index.php?route=weather"><i class="fa-5x fas fa-cloud-sun-rain"></i><i class="fa-4x fas fa-cloud-sun-rain"></i>Météo</a>
            </div>
        </article>
    <?php endif; ?>
    
</section>