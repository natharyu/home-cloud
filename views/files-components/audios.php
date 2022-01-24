<section id="audios" class="audios">
    <article class="mainContent">
            <div class="flexTop">
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

    <article class="mainContent filesView">
            <h3 class="column">Vos Fichiers audios</h3>

        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" name="selectAllFile[]" id="selectAllFiles" class="selectAllFiles"></th>
                    <th>Nom</th>
                    <th>Date d'import</th>
                    <th>Taille (en Mb)</th>
                </tr>
            </thead>
            <tbody>

                    <form method="POST" action="index.php?route=moveToTrash">
                        <?php foreach($files as $file) :?>
                        
                            <tr>
                                <td class="selectFile">
                                        <input type="checkbox" name="selectFile[]" id="selectFile" class="selectFileCheckbox" value="<?= htmlspecialchars($file['id'])?>">
                                        <input type="submit" id="submitMoveToTrashHidden" hidden>   
                                </td>
                                <td id="fileLink">
                                   <a data-type="<?= htmlspecialchars(substr($file['type'], 0, 5 )) ?>" data-fileid="<?= htmlspecialchars($file['id']) ?>" data-href="<?=htmlspecialchars($file['path']) ?>/<?= htmlspecialchars($file['name'])?>"><i class="fas fa-music"></i> <?= htmlspecialchars($file['name'])?></a>
                                <a href="index.php?route=downloadOneFile&id=<?= htmlspecialchars($file['id']) ?>"><i class="fas fa-download"></i></a>
                                </td>
                                <td><?= htmlspecialchars($file['created_at']) ?></td>
                                <td><?= htmlspecialchars(round($file['size'] / 1024 / 1024, 1))." Mo" ?></td>
                            </tr>
                        
                        <?php endforeach;?>
                    </form>

            </tbody>
        </table>
    </article>
</section>