<section id="images" class="images">
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
            <h3 class="column">Vos Images</h3>
            
                    <form class="imagesWrap" method="POST" action="index.php?route=moveToTrash">
                        <ul>
                        <?php foreach($files as $file) :?>
                        
                            <li id="fileLink">
                                <div class="selectFile">
                                        <input type="checkbox" name="selectFile[]" id="selectFile" class="selectFileCheckbox" value="<?= htmlspecialchars($file['id'])?>">
                                        <input type="submit" id="submitMoveToTrashHidden" hidden>   
                                </div>
                                <div class="downloadFile">
                                    <a href="index.php?route=downloadOneFile&id=<?= htmlspecialchars($file['id']) ?>"><i class="btn fas fa-download"></i></a>
                                </div>
                                
                                <a data-type="<?= htmlspecialchars(substr($file['type'], 0, 5 )) ?>" data-fileid="<?= htmlspecialchars($file['id']) ?>" data-href="<?=htmlspecialchars($file['path']) ?>/<?= htmlspecialchars($file['name'])?>" title="<?= htmlspecialchars($file['name'])?>" data-title="<?= htmlspecialchars($file['name'])?>" path="<?= htmlspecialchars(substr($file['path'], 7)) ?>">
                                    <img src="<?=htmlspecialchars($file['path']) ?>/<?= htmlspecialchars($file['name'])?>" alt="<?= htmlspecialchars($file['name'])?>" loading="lazy">
                                </a>
                            </li>
                        
                        <?php endforeach;?>
                        </ul>
                    </form>

            </tbody>
        </table>
    </article>
</section>