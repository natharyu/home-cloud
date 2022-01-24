<?php

namespace Controllers;

class FilesController
{
    //View fichiers
    public function files()
    {
        $newUser = new \Models\User();
        $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);
        $newFiles = new \Models\Upload();
        $userID= $user['id'];

        if(isset($_GET['path']) && !empty($_GET['path']))
        {
            $decomposePath = explode('/', $_GET['path']);
            $name = array_pop($decomposePath);
            $path = "uploads/".implode("/", $decomposePath);
            $currentFolder = $newFiles->getOneFileByPathAndName($userID, $path, $name);
            $currentFolderPath = $currentFolder['path'];
            if($currentFolder === false && $currentFolderPath === null)
            {
                header('Location: index.php?route=files');
                exit();
            }
        }
        elseif(!isset($_GET['folder']) || empty($_GET['folder']))
        {
            $currentFolderPath = "uploads/". $_SESSION['username'];
        }
        else
        {  
            $currentFolder = $newFiles->getOneFileById($userID, $_GET['folder']);
            
            if($currentFolder === false)
            {
                header('Location: index.php?route=home');
                exit();
            }
            
            $currentFolderPath = $currentFolder['path']."/". $currentFolder['name'];
        }
        $files = $newFiles->getAllFilesByPath($userID, $currentFolderPath ,0);

        $view = 'files.php';
        include_once 'views/template.php';
    }

    //View corbeille
    public function trashFiles() 
    {
        $newUser = new \Models\User();
        $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);
        $userID= $user['id'];
        $newFiles = new \Models\Upload();
        $rootFiles = $newFiles->getUserRootFiles($_SESSION['username']);

        if(!isset($_GET['folder']) || empty($_GET['folder']))
        {
            $currentFolderPath = "uploads/". $_SESSION['username'];
        }
        else
        {  
            $currentFolder = $newFiles->getOneFileById($userID, $_GET['folder']);
            $currentFolderPath = $currentFolder['path']. "/". $currentFolder['name'];
        }
        $files = $newFiles->getAllFilesByUserId($userID ,1);

        $view = 'files.php';
        include_once 'views/template.php';
    }

    //permet de mettre un ou des fichiers à la corbeille via checkbox
    public function moveToTrash()
    {
        try
        {
            $newFiles = new \Models\Upload();

            if(isset($_POST['selectFile']))
            {
            foreach($_POST['selectFile'] as $value)
            {
                if(isset($_POST['selectFile']))
                {
                    $newData=[
                        'deleted' => 1
                    ];
                    $newFiles->updateOneFile($newData ,$value);
                }
                else
                {
                    throw new \Exception('Erreur avec ce(s) fichier(s)');
                }
            }
            }
            else
            {
                throw new \Exception('Aucun fichier(s) séléctioné(s)');
            }
            $success = 'Fichier(s) mis à la corbeille';
            header("Location: ".$_SERVER['HTTP_REFERER']."&success=" . $success);
            exit();
        }
        catch( \Exception $exeption )
        {
            header('Location: index.php?route=files&error=' . $exeption->getMessage() );
            exit();
        }
    }

    //permet de restaurer un ou des fichiers de la corbeille via checkbox
    public function restoreFromTrash()
    {
        try
        {
            $newFiles = new \Models\Upload();

            if(isset($_POST['selectFileToRestore']))
            {
            foreach($_POST['selectFileToRestore'] as $value)
            {
                if(isset($_POST['selectFileToRestore']))
                {
                    $newData=[
                        'deleted' => 0
                    ];
                    $newFiles->updateOneFile($newData ,$value);
                }
                else
                {
                    throw new \Exception('Erreur avec ce(s) fichier(s)');
                }
            }
            }
            else
            {
                throw new \Exception('Aucun fichier(s) séléctioné(s)');
            }
            $success = 'Fichier(s) restauré(s)';
            header("Location: ".$_SERVER['HTTP_REFERER']."&success=" . $success);
            exit();
        }
        catch( \Exception $exeption )
        {
            header('Location: index.php?route=files&error=' . $exeption->getMessage() );
            exit();
        }
    }

    //permet de supprimer les fichiers sur le serveur et sur la BDD
    
    //ATTENTION le serveur 3wa étant bridé, la suppression massive de fichier est bloquée par Xdebug (il faut rafraichir la page et  la fonction continue et s'execute quand même sans soucis)
    public function deleteForLoop($files)
    {
        $newUser = new \Models\User();
        $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);
        $userID = $user['id'];
        $newFiles = new \Models\Upload();
        foreach ($files as $file)
        {
            if($file['type'] === 'dir')
            {
                $fullpath = $file['path'] . '/' . $file['name'];
                $files = $newFiles->getAllFilesByOnlyPath($userID, $fullpath);
                
                if($files == [])
                {
                    chmod($fullpath, 0777);
                    rmdir($fullpath);
                    $newFiles->deleteOneFile($file['id']);
                    $this->deleteFromTrash();
                }
                else
                {
                    $this->deleteForLoop($files);
                }
            }
            else
            {
                $fullpath = $file['path'] . '/' . $file['name'];
                if($fullpath !== false)
                {
                    chmod($fullpath, 0777);
                    unlink($fullpath);
                    $newFiles->deleteOneFile($file['id']);
                }
                $this->deleteFromTrash();
            }
        }
    }
    
    //Permet de vider la corbeille et donc supprimer les fichiers du serveur et de la BDD
    
    //ATTENTION le serveur 3wa étant bridé, la suppression massive de fichier est bloquée par Xdebug (il faut rafraichir la page et  la fonction continue et s'execute quand même sans soucis)
    public function deleteFromTrash()
    {
        try
        {
            $newUser = new \Models\User();
            $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);
            $userID = $user['id'];
            $newFiles = new \Models\Upload();
            $files = $newFiles->getAllFilesByUserId($userID ,1);
            
            if(!empty($files))
            {
                $this->deleteForLoop($files);
            }
            else
            {
                $success = 'Corbeille vide';
                header('Location: index.php?route=trashFiles&success=' . $success );
                exit();
            }
            
        }
        catch( \Exception $exeption )
        {
            header('Location: index.php?route=trashFiles&error=' . $exeption->getMessage() );
            exit();
        }
    }
    
    //View images
    public function images()
    {
        $newUser = new \Models\User();
        $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);
        $newFiles = new \Models\Upload();
        
        $filesType = 'image/%';
        $files = $newFiles->getAllFilesByUserByType($user['id'], 0, $filesType);

        $view = 'files.php';
        include_once 'views/template.php';
    }
    
    //View audios
    public function audios()
    {
        $newUser = new \Models\User();
        $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);
        $newFiles = new \Models\Upload();

        $filesType = 'audio/%';
        $files = $newFiles->getAllFilesByUserByType($user['id'], 0, $filesType);

        $view = 'files.php';
        include_once 'views/template.php';
    }
    
    //View videos
    public function videos()
    {
        $newUser = new \Models\User();
        $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);
        $newFiles = new \Models\Upload();
        
        $filesType = 'video/%';
        $files = $newFiles->getAllFilesByUserByType($user['id'], 0, $filesType);

        $view = 'files.php';
        include_once 'views/template.php';
    }
    
    //Permet de creer un dossier à l'endroit de l'arborescence ou l'on se situe
    public function createFolder()
    {
        try
        {
            if( isset($_POST['file']) && !empty($_POST['file']) && 
                isset($_SESSION['id']) && !empty($_SESSION['id']) &&
                isset($_POST['path']) && !empty($_POST['path']))
            {
                $newUpload = new \Models\Upload();
                $newUser = new \Models\User();
                $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);
                
                if( $_POST['file'] )
                {
                    $file = $_POST['file'];
                    if(file_exists( $_POST['path']. "/" . $file ))
                    {
                        echo json_encode('Un dossier existe déjà sous ce nom');
                        http_response_code(400);
                        exit();
                    }
                    elseif( !file_exists( $_POST['path']. "/" . $file ) )
                    {
                        $uploads_folder = $_POST['path'];
                        $destination    = $uploads_folder . '/' . $file;

                        mkdir( $destination );
                        
                        $data = [
                            $user['id'],
                            $_POST['path'],
                            $file,
                            'dir',
                            0,
                            0
                        ];
        
                        $newUpload->addOneFile( $data );
                    }
                }
                else
                {
                    $file = null;
                    echo json_encode('Une erreur est survenue');
                    http_response_code(400);
                    exit();
                }
                

                $currentFolderName = basename($_POST['path']);
                $currentFolder = $newUpload->getOneFile($user['id'], $currentFolderName);
                (object) $result = $currentFolder;

                echo json_encode($result);
            }
            else
            {
                echo json_encode('Les champs sont obligatoire');
                http_response_code(400);
                exit();
            }
        }
        catch( \Exception $exeption )
        {
            header('Location: index.php?route=home&error=' . $exeption->getMessage() );
            exit();
        }
    }
    
    //Permet d'importer un dossier à l'endroit de l'arborescence ou l'on se situe
    public function addFolder()
    {
        
        if( isset($_FILES) && !empty($_FILES) && 
            isset($_SESSION['id']) && !empty($_SESSION['id']) &&
            isset($_POST['path']) && !empty($_POST['path']))
        {
            $totalNbOfImports = count($_POST['folder']);
            $totalOfImportsAvalaible = ini_get('max_file_uploads');

            if($totalNbOfImports >= $totalOfImportsAvalaible)
            {
                echo json_encode('Limite atteinte');
                exit();
            }

            $currentPath = $_POST['path'];

            for ($i=0; $i < count($_POST['folder']); $i++) 
            { 
                $folder = dirname($_POST['folder'][$i]);
                $uploads_folder = $currentPath . "/" . $folder;
                
                if( $_FILES['file']['error'][$i] === 0 )
                {
                    if(!file_exists($uploads_folder))
                    {
                        $subFolders = explode("/", $folder);
                        $current = $currentPath;
                        foreach($subFolders as $subFolder){
                            $parent = $current;
                            $current .= "/" . $subFolder;
                            
                            $newUpload = new \Models\Upload();
                            $folderName = $newUpload->getOneFileByPathAndName($_SESSION['id'], $parent, $subFolder);
                            
                            if(!$folderName && !file_exists($folderName)){
                                $data = [
                                    $_SESSION['id'],
                                    $parent,
                                    $subFolder,
                                    "dir",
                                    0,
                                    0
                                ];
                                mkdir($current, 0755, true);
                                $newUpload = new \Models\Upload();
                                $newUpload->addOneFile( $data );        
                            }
                        }
                    }
                }
                elseif( $_FILES['file']['error'][$i] === 1 || $_FILES['file']['error'][$i] === 2)
                {
                    echo json_encode('Le fichier est supérieur à 2 Mo');
                    http_response_code(400);
                    exit();
                }
                else
                {
                    $file = null;
                    echo json_encode('Une erreur est survenue');
                    http_response_code(400);
                    exit();
                }
                
                $data = [
                    $_SESSION['id'],
                    $uploads_folder,
                    $_FILES['file']['name'][$i],
                    $_FILES['file']['type'][$i],
                    $_FILES['file']['size'][$i],
                    0
                ];
                
                $temp_name = $_FILES['file']['tmp_name'][$i];
                $file = $_FILES['file']['name'][$i];
                $destination = $current . '/' . $file;
                move_uploaded_file($temp_name, $destination);
                
                $newUpload = new \Models\Upload();
                $newUpload->addOneFile( $data );
            }
            $newUser = new \Models\User();
            $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);
            
            $currentFolderName = basename($_POST['path']);
            $currentFolder = $newUpload->getOneFile($user['id'], $currentFolderName);
            (object) $result = $currentFolder;

            echo json_encode($result);
        }
        else
        {
            echo json_encode('Les champs sont obligatoire');
            http_response_code(400);
            exit();
        }   
    }
    
    //Permet d'importer un ou plusieurs fichiers à l'endroit de l'arborescence ou l'on se situe
    public function addFile()
    {   
            if( isset($_FILES['file']) && !empty($_FILES['file']) && 
                isset($_SESSION['id']) && !empty($_SESSION['id']) &&
                isset($_POST['path']) && !empty($_POST['path']))
            {
                $totalNbOfImports = count($_FILES['file']['name']);
                $totalOfImportsAvalaible = ini_get('max_file_uploads');
                
                if($totalNbOfImports >= $totalOfImportsAvalaible)
                {
                    echo json_encode('Limite atteinte');
                    exit();
                }

                for ($i=0; $i < count($_FILES['file']['name']); $i++)
                { 
                    if( $_FILES['file']['error'][$i] === 0 )
                    {
                        $file = $_FILES['file']['name'][$i];

                        if( !file_exists( $_POST['path']. "/" . $file ) )
                        {
                            $uploads_folder = $_POST['path'];
                            $temp_name      = $_FILES['file']['tmp_name'][$i];
                            $file           = $_FILES['file']['name'][$i];
                            $destination    = $uploads_folder . '/' . $file;

                            move_uploaded_file( $temp_name, $destination );

                        }
                    }
                    elseif( $_FILES['file']['error'][$i] === 1 || $_FILES['file']['error'][$i] === 2)
                    {
                        echo json_encode('Le fichier est supérieur à 2 Mo');
                        http_response_code(400);
                        exit();
                    }
                    else
                    {
                        $file = null;
                        echo json_encode('Une erreur est survenue');
                        http_response_code(400);
                        exit();
                    }
                
                    $data = [
                        $_SESSION['id'],
                        $_POST['path'],
                        $_FILES['file']['name'][$i],
                        $_FILES['file']['type'][$i],
                        $_FILES['file']['size'][$i],
                        0
                    ];

                    $newUpload = new \Models\Upload();
                    $newUpload->addOneFile( $data );
                }
                $newUser = new \Models\User();
                $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);

                $currentFolderName = basename($_POST['path']);
                $currentFolder = $newUpload->getOneFile($user['id'], $currentFolderName);
                (object) $result = $currentFolder;

                echo json_encode($result);
            }
            else
            {
                echo json_encode('Les champs sont obligatoire');
                http_response_code(400);
                exit();
            }
        
    } 
    
    //Permet la recherche parmis tous nos fichiers
    public function searchInFiles($search)
    {
        $keyword = $search['keyword'];
        
        $newUser = new \Models\User();
        $newFiles = new \Models\Upload();
        
        $results =[];
        if ($keyword !== "")
        {
            $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);
            $files = $newFiles->getAllFilesByUserId($user['id'], 0);
            
            foreach ($files as $file)
            {
                if(strpos(strtolower($file['name']), strtolower($keyword)) !== false)
                {
                    $results[] = $file;
                }
            }
        }
        
        $objResult = (object) $results;
        $jsonResults = json_encode($objResult);

        echo $jsonResults;
        exit();
    }
    
    //Permet le telechargement de chaque fichier indépendement
    public function downloadOneFile()
    {
        $newUser = new \Models\User();
        $newFiles = new \Models\Upload();
        
        $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);
        $file = $newFiles->getOneFileById($user['id'], $_GET['id'] );
        $fullpath = $file['path']. "/" .$file['name'];
        header('Content-Disposition: attachment; filename="'.$file['name'].'"');
        readfile($fullpath);
        exit();
    }
    
    //Permet le telechargement de chaque dossier et de leur contenu indépendement en format .zip
    public function downloadOneFolder()
    {
        $newUser = new \Models\User();
        $newFiles = new \Models\Upload();
        
        $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);
        $file = $newFiles->getOneFileById($user['id'], $_GET['id'] );
        $today = date("dmY_His");
        $source= $file['path']."/".$file['name'];
        $archive = $file['name'] .'.zip';
        $destination='uploads/'. $archive .'.zip';
        $include_dir = false;

        if (!extension_loaded('zip') || !file_exists($source)) 
        {
        return false;
        }

        if (file_exists($destination)) 
        {
        unlink ($destination);
        }

        $zip = new \ZipArchive;

        if (!$zip->open($archive, \ZipArchive::CREATE)) 
        {
        return false;
        }
        $source = str_replace('\\', '/', realpath($source));
        if (is_dir($source) === true)
        {

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source), \RecursiveIteratorIterator::SELF_FIRST);

            if ($include_dir) 
            {

                $arr = explode("/",$source);
                $maindir = $arr[count($arr)- 1];

                $source = "";
                for ($i=0; $i < count($arr) - 1; $i++) { 
                $source .= '/' . $arr[$i];
            }

            $source = substr($source, 1);

            $zip->addEmptyDir($maindir);

        }

        foreach ($files as $file)
        {
            $file = str_replace('\\', '/', $file);
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
            continue;

            $file = realpath($file);

            if (is_dir($file) === true)
            {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            }
            else if (is_file($file) === true)
            {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }
    $zip->close();

    header('Content-Type: application/zip');
    header('Content-disposition: attachment; filename='.$archive);
    header('Content-Length: '.filesize($archive));
    readfile($archive);
    unlink($archive);
    }
}
?>