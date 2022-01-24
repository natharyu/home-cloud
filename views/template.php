<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Home-Cloud</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="https://api.iconify.design/mdi/cloud.svg?color=%233282b8" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/normalize.css" type="text/css" />
    <link rel="stylesheet" href="assets/css/styles.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Lobster&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
</head>
<body>
    <header>
        <?php if( isset( $_SESSION['username'] ) ) : ?>
            <div class="nav-left">
                <svg aria-hidden="true" role="img" width="64" height="64" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24">
                    <a href="index.php?route=home">
                        <path d="M19.35 10.03A7.49 7.49 0 0 0 12 4C9.11 4 6.6 5.64 5.35 8.03A6.004 6.004 0 0 0 0 14a6 6 0 0 0 6 6h13a5 5 0 0 0 5-5c0-2.64-2.05-4.78-4.65-4.97z" stroke="rgba(255, 255, 255, 1)" stroke-width="1px" fill="rgba(50, 130, 184, 1)"/>
                    </a>
                </svg>
                <h1><a href="index.php?route=home">Home-Cloud</a></h1>
            </div>
            <nav class="navbar">
                <ul class="nav-right">
                    <li>
                        <a href="index.php?route=home">Accueil</a>
                    </li>
                    <li class="headerAccount">
                        <button class="headerAccountBtn" id="headerAccount">
                            <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar"/>
                            <span><?= htmlspecialchars($_SESSION['username']) ?> <i id="headerAccountArrow" class="headerAccountArrow fas fa-caret-down"></i></span>
                        </button>
                        <div class="headerAccountMenu" id="headerAccountMenu">
                            <a class="headerAccountMenuItem" href="index.php?route=account">Mon compte</a>
                            <a class="headerAccountMenuItem" href="index.php?route=logout">Se deconnecter</a>
                            <?php
                                $newSession = new \Models\User();
                                $user = $newSession->getOneUserBySessionKey($_SESSION['sessionKey']);
                                if(htmlspecialchars($user['role']) === 'admin'): ?>
                                <a class="headerAccountMenuItem" href="index.php?route=adminPanel">Administration</a>
                            <?php endif; ?>
                        </div>
                    </li>
                </ul>
                <?php endif; ?>
            </nav>
        </header>

        
        <main>
            <article id="addFilesModal" class="addFilesModalMain">
                <div class="addFilesModal column">
                
                    <button class="btn-danger" id="closeAddFilesModal"><i class="fas fa-times"></i></button>
                    
                    <h3 class="column">Importer un fichier</h3>
                    
                    <input type="file" name="file" id="file" multiple hidden>
                    <div class="drop-area"  id="uploadfile">
                        <p>Glisser déposer un ou plusieurs fichiers ici ou, cliquer pour sélectionner le ou les fichiers à importer</p>
                        <span>Taille maximum par fichier : <?= htmlspecialchars(ini_get('upload_max_filesize')) ?>o</span>
                        <p>Nombre maximum de fichiers par import : <?= htmlspecialchars(ini_get('max_file_uploads') - 1) ?></p>
                    </div>
                    <div id="errorUploadMsg" class="errorUploadMsg"></div>

                    <h3 class="column">Importer un dossier</h3>
                    <p>Taille maximum du dossier : <?= htmlspecialchars(ini_get('upload_max_filesize')) ?>o</p>
                    <p>Nombre maximum de fichiers présents dans le dossier : <?= htmlspecialchars(ini_get('max_file_uploads') - 1) ?></p>

                    <input type="file" name="files" id="files" webkitdirectory directory multiple hidden>
                    <a class="btn"  id="uploadfiles">
                        Choisir un dossier
                    </a>
                    <div id="errorUploadMsgFolder" class="errorUploadMsgFolder"></div>

                    <div class="progress">
                        <div class="progress-bar"></div>
                    </div>
                </div>
            </article>

            <article id="createFolderModal" class="createFolderModalMain">
                <div class="createFolderModal column">
                    <button class="btn-danger" id="closeCreateFolderModal"><i class="fas fa-times"></i></button>
                    
                    <h3 class="column">Créer un dossier</h3>
                    <label for="newFolderName">Nom du dossier à créer:</label>
                    <input type="text" name="newFolderName" id="newFolderName" autofocus>
                    <button class="btn" type="Submit" id="submitCreateFolder">Créer le dossier</button>
                </div>
            </article>
            
            <article id="oneFileModal" class="oneFileModal">
                <div class="oneFileViewModal column">
                    <button class="btn-danger" id="closeoneFileViewModal"><i class="fas fa-times"></i></button>
                    
                    <div id="preview" class="preview"></div>
                    
                </div>
            </article>

            <div class="container">
                <?php include htmlspecialchars($view); ?>
            </div>

        </main>
        
        <footer>
        </footer>
        
        <script src="assets/js/main.js"></script>
        
        <?php if($_GET['route'] === 'todolist' || $_GET['route'] === 'todolistNotDone' || $_GET['route'] === 'todolistDone'): ?>
            <script src="assets/js/todolist.js"></script>
        <?php elseif($_GET['route'] === 'files'): ?>
            <script src="assets/js/files.js"></script>
            <script src="assets/js/previewModal.js"></script>
        <?php elseif($_GET['route'] === 'images' || $_GET['route'] === 'audios' || $_GET['route'] === 'videos' || $_GET['route'] === 'trashFiles'): ?>
            <script src="assets/js/previewModal.js"></script>
        <?php endif; ?>
</body>
</html>