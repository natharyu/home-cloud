<section id="trash" class="trash">
    <article class="mainContent">
            <div class="flexTop">
                <a class="btn-warning" href="index.php?route=files"><i class="fas fa-file"></i> Mes fichiers</a>
                <a class="btn-success" id="submitRestoreFromTrash"><i class="fas fa-trash-restore-alt"></i> Restaurer le(s) fichier(s)</a>
                <a class="btn-danger" href="index.php?route=deleteFromTrash" id="deleteFromTrash"><i class="fas fa-times"></i> Vider la corbeille</a>
            </div>
    
            <?php if( isset( $_GET['success'] ) ) : ?>
                <p class="success column"><?= htmlspecialchars($_GET['success']) ?></p>
            <?php endif; ?>
            <?php if( isset( $_GET['error'] ) ) : ?>
                <p class="error column"><?= htmlspecialchars($_GET['error']) ?></p>
            <?php endif; ?>
    </article>

    <article class="mainContent filesView">
            <h3 class="column">Corbeille</h3>
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" name="selectAllFile[]" id="selectAllFiles" class="selectAllFiles"></th>
                    <th>Nom</th>
                    <th>Taille (en Mb)</th>
                </tr>
            </thead>
            <tbody>
                <input type="hidden" name="path" id="path" value="<?= htmlspecialchars($currentFolderPath) ?>">

                    <form method="POST" action="index.php?route=restoreFromTrash">
                        <?php foreach($files as $file) :?>
                        
                            <tr>
                                <td class="selectFile">
                                        <input type="checkbox" name="selectFileToRestore[]" id="selectFileToRestore" class="selectFileToRestore" value="<?= htmlspecialchars($file['id'])?>">
                                        <input type="submit" id="submitRestoreFromTrashHidden"hidden>   
                                </td>
                                <td id="fileLink" class="fileLink">
                                        <?php if (htmlspecialchars($file['type']) == 'dir'): ?>
                                        
                                            <a data-type="<?= htmlspecialchars($file['type']) ?>" href="index.php?route=files&folder=<?= htmlspecialchars($file['id'])?>"><i class="fas fa-folder"></i> <?= htmlspecialchars($file['name'])?></a>
                                            <a href="index.php?route=downloadOneFolder&id=<?= htmlspecialchars($file['id']) ?>"></a>
                                            
                                        <?php elseif (htmlspecialchars(substr($file['type'], 0, 5)) === 'audio'): ?>
                                        
                                            <a data-type="<?= htmlspecialchars(substr($file['type'], 0, 5 )) ?>" data-fileid="<?= htmlspecialchars($file['id']) ?>" data-href="<?=htmlspecialchars($file['path']) ?>/<?= htmlspecialchars($file['name'])?>"><i class="fas fa-music"></i> <?= htmlspecialchars($file['name'])?></a>
                                            <a href="index.php?route=downloadOneFile&id=<?= htmlspecialchars($file['id']) ?>"></a>
                                            
                                        <?php elseif (htmlspecialchars(substr($file['type'], 0, 5)) === 'image'): ?>
                                        
                                            <a data-type="<?= htmlspecialchars(substr($file['type'], 0, 5 )) ?>" data-fileid="<?= htmlspecialchars($file['id']) ?>" data-href="<?=htmlspecialchars($file['path']) ?>/<?= htmlspecialchars($file['name'])?>"><i class="fas fa-image"></i> <?= htmlspecialchars($file['name'])?></a>
                                            <a href="index.php?route=downloadOneFile&id=<?= htmlspecialchars($file['id']) ?>"></a>
                                            
                                        <?php elseif (htmlspecialchars(substr($file['type'], 0, 5)) === 'video'): ?>
                                        
                                            <a data-type="<?= htmlspecialchars(substr($file['type'], 0, 5 )) ?>" data-fileid="<?= htmlspecialchars($file['id']) ?>" data-href="<?=htmlspecialchars($file['path']) ?>/<?= htmlspecialchars($file['name'])?>"><i class="fas fa-film"></i> <?= htmlspecialchars($file['name'])?></a>
                                            <a href="index.php?route=downloadOneFile&id=<?= htmlspecialchars($file['id']) ?>"></a>
                                            
                                        <?php else: ?>
                                        
                                            <a data-type="<?= htmlspecialchars(substr($file['type'], 0, 5 )) ?>" data-fileid="<?= htmlspecialchars($file['id']) ?>" data-href="<?=htmlspecialchars($file['path']) ?>/<?= htmlspecialchars($file['name'])?>"><i class="fas fa-file"></i> <?= htmlspecialchars($file['name'])?></a>
                                            <a href="index.php?route=downloadOneFile&id=<?= htmlspecialchars($file['id']) ?>"></a>
                                            
                                        <?php endif; ?>
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
                                    <td><?= htmlspecialchars($size) ?></td>                            </tr>
                        
                        <?php endforeach;?>
                    </form>
            </tbody>
        </table>
    </article>
</section>