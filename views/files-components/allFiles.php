<section id="files" class="files">
    <article class="mainContent">
            <div class="flexTop">
                    <div id="addFile" class="addFile">
                        <button class="addFileBtn" id="addFileBtn">
                            <i id="plusIcon" class="fas fa-plus"></i>
                        </button>
                        <nav id="addFileBtnMenu" class="addFileBtnMenu">
                            <button id="addFiles"><i class="fas fa-file-medical"></i> Ajouter un/des fichier(s)</button>
                            <button id="createFolder"><i class="fas fa-folder-plus"></i> Créer un dossier</button>  
                        </nav>
                    </div>
    
                    <a class="btn-warning" href="index.php?route=trashFiles"><i class="fas fa-trash-alt"></i> Corbeille</a>
                    <a class="btn-danger" id="submitMoveToTrash"><i class="fas fa-times"></i> Mettre à la corbeille</a>
            </div>
            
    
            <?php if( isset( $_GET['success'] ) ) : ?>
                <p class="success column"><?= htmlspecialchars($_GET['success']) ?></p>
            <?php endif; ?>
            <?php if( isset( $_GET['error'] ) ) : ?>
                <p class="error column"><?= htmlspecialchars($_GET['error']) ?></p>
            <?php endif; ?>
    </article>

    <article class="mainContent column">
        
            <div class="searchingInFiles">
                <input type="text" name="searchInFiles" id="SearchInFiles" class="SearchInFiles" placeholder="Rechercher un fichier">
                <div id="filesSearchList" class="filesSearchList"></div>
            </div>
        
    </article>

    <article class="mainContent filesView">
        <div class="flex">
            <?php if (htmlspecialchars(substr($currentFolderPath, 8)) === htmlspecialchars($_SESSION['username'])): ?>
            <?php elseif (isset($_GET['path']) || !empty($_GET['path']) || isset($_GET['folder']) || !empty($_GET['folder'])): ?>
                <a class="btn" href="index.php?route=files&path=<?= htmlspecialchars(substr($currentFolderPath, 8)) ?>" ><i class="fas fa-arrow-left"></i> Retour</a>
            <?php endif; ?>
            <h3 class="column"><?= htmlspecialchars(substr($currentFolderPath, 7)) ?></h3>
        </div>

        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" name="selectAllFile[]" id="selectAllFiles" class="selectAllFiles"></th>
                    <th>Nom</th>
                    <th>Date d'import</th>
                    <th>Taille</th>
                </tr>
            </thead>
            <tbody>
                <input type="hidden" name="path" id="path" value="<?= htmlspecialchars($currentFolderPath) ?>">

                    <form  method="POST" action="index.php?route=moveToTrash">
                            <?php foreach($files as $file) :?>
                            
                                <tr>
                                    <td class="selectFile">
                                            <input type="checkbox" name="selectFile[]" id="selectFile" class="selectFileCheckbox" value="<?= htmlspecialchars($file['id'])?>">
                                            <input type="submit" id="submitMoveToTrashHidden" hidden>   
                                    </td>
                                    <td id="fileLink" class="fileLink">
                                        <?php if (htmlspecialchars($file['type']) == 'dir'): ?>
                                        
                                            <a data-type="<?= htmlspecialchars($file['type']) ?>" href="index.php?route=files&folder=<?= htmlspecialchars($file['id'])?>"><i class="fas fa-folder"></i> <?= htmlspecialchars($file['name'])?></a>
                                            <a href="index.php?route=downloadOneFolder&id=<?= htmlspecialchars($file['id']) ?>"><i class="fas fa-download"></i></a>
                                            
                                        <?php elseif (htmlspecialchars(substr($file['type'], 0, 5)) === 'audio'): ?>
                                        
                                            <a data-type="<?= htmlspecialchars(substr($file['type'], 0, 5 )) ?>" data-fileid="<?= htmlspecialchars($file['id']) ?>" data-href="<?=htmlspecialchars($file['path']) ?>/<?= htmlspecialchars($file['name'])?>"><i class="fas fa-music"></i> <?= htmlspecialchars($file['name'])?></a>
                                            <a href="index.php?route=downloadOneFile&id=<?= htmlspecialchars($file['id']) ?>"><i class="fas fa-download"></i></a>
                                            
                                        <?php elseif (htmlspecialchars(substr($file['type'], 0, 5)) === 'image'): ?>
                                        
                                            <a data-type="<?= htmlspecialchars(substr($file['type'], 0, 5 )) ?>" data-fileid="<?= htmlspecialchars($file['id']) ?>" data-href="<?=htmlspecialchars($file['path']) ?>/<?= htmlspecialchars($file['name'])?>"><i class="fas fa-image"></i> <?= htmlspecialchars($file['name'])?></a>
                                            <a href="index.php?route=downloadOneFile&id=<?= htmlspecialchars($file['id']) ?>"><i class="fas fa-download"></i></a>
                                            
                                        <?php elseif (htmlspecialchars(substr($file['type'], 0, 5)) === 'video'): ?>
                                        
                                            <a data-type="<?= htmlspecialchars(substr($file['type'], 0, 5 )) ?>" data-fileid="<?= htmlspecialchars($file['id']) ?>" data-href="<?=htmlspecialchars($file['path']) ?>/<?= htmlspecialchars($file['name'])?>"><i class="fas fa-film"></i> <?= htmlspecialchars($file['name'])?></a>
                                            <a href="index.php?route=downloadOneFile&id=<?= htmlspecialchars($file['id']) ?>"><i class="fas fa-download"></i></a>
                                            
                                        <?php else: ?>
                                        
                                            <a data-type="<?= htmlspecialchars(substr($file['type'], 0, 5 )) ?>" data-fileid="<?= htmlspecialchars($file['id']) ?>" data-href="<?=htmlspecialchars($file['path']) ?>/<?= htmlspecialchars($file['name'])?>"><i class="fas fa-file"></i> <?= htmlspecialchars($file['name'])?></a>
                                            <a href="index.php?route=downloadOneFile&id=<?= htmlspecialchars($file['id']) ?>"><i class="fas fa-download"></i></a>
                                            
                                        <?php endif; ?>
                                    </td>
    
                                    <td>
                                        <?= htmlspecialchars($file['created_at']) ?>
                                    </td>
                                    
                                    <?php if(round($file['size']  / 1024, 2) < 1000)
                                            {
                                                $unit = "Ko";
                                                $size = round($file['size']  / 1024, 2) . $unit;
                                            }
                                            elseif(round($file['size'] / 1024 / 1024, 2) < 1000)
                                            {
                                                $unit = "Mo";
                                                $size = round($file['size'] / 1024 / 1024, 2) . $unit;
                                            }
                                            elseif(round($file['size'] / 1024 / 1024 / 1024, 2) < 1000)
                                            {
                                                $unit = "Go";
                                                $size = round($file['size'] / 1024 / 1024 / 1024, 2) . $unit;
                                            }
                                    ?>
                                    <td><?= htmlspecialchars($size) ?></td>
                                </tr>
                            
                            <?php endforeach;?>
                    </form>

            </tbody>
        </table>
    </article>
</section>