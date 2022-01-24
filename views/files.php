<?php 

if($_GET['route'] == 'files')
{
    include_once 'views/files-components/allFiles.php' ;
}
elseif($_GET['route'] == 'images')
{
    include_once 'views/files-components/images.php' ;
}
elseif($_GET['route'] == 'audios')
{
    include_once 'views/files-components/audios.php' ;
}
elseif($_GET['route'] == 'videos')
{
    include_once 'views/files-components/videos.php' ;
}
elseif($_GET['route'] == 'trashFiles')
{
    include_once 'views/files-components/trash.php';
}
?>