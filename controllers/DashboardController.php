<?php

namespace Controllers;

class DashboardController
{
    //View dashboard vur générale
    public function adminPanel()
    {
        $newUser = new \Models\User();
        $users = $newUser->getAllUsers();
        $nbOfUsers = count($users);

        $newUpload = new \Models\Upload();
        $files = $newUpload->getAllFiles();
        $nbOfFiles = count($files);
        $totalFilesSize = 0;
        
        //Conversion des unités selon la taille totale des fichiers présents sur le serveur
        for ($i=0; $i < $nbOfFiles ; $i++) {
         $totalFilesSize += $files[$i]['size'];
        }
        if(round($totalFilesSize  / 1024, 2) < 1000)
        {
            $unit = "Ko";
            $totalFilesSize = round($totalFilesSize  / 1024, 2) . $unit;
        }
        elseif(round($totalFilesSize / 1024 / 1024, 2) < 1000)
        {
            $unit = "Mo";
            $totalFilesSize = round($totalFilesSize / 1024 / 1024, 2) . $unit;
        }
        elseif(round($totalFilesSize / 1024 / 1024 / 1024, 2) < 1000)
        {
            $unit = "Go";
            $totalFilesSize = round($totalFilesSize / 1024 / 1024 / 1024, 2) . $unit;
        }

        $view = 'overview.php';
        include_once 'views/dashboard/template.php' ;
    }

    //View  dashboard utilisateurs
    public function adminPanelUsers()
    {
        $newUser = new \Models\User();
        $users = $newUser->getAllUsers();
        
        $view = 'users.php';
        include_once 'views/dashboard/template.php' ;
    }
    
    //View dashboard detail d'un utilisateur
    public function adminPanelUserDetails()
    {
        $newUser = new \Models\User();
        $user = $newUser->getOneUserById($_GET['id']);
        
        $view = 'userDetails.php';
        include_once 'views/dashboard/template.php' ;
    }

    //View dashboard modifier un utilisateur via details utilisateur
    public function adminPanelUserModify()
    {
        $newUser = new \Models\User();
        $user = $newUser->getOneUserById($_GET['id']);
        
        $view = 'userModify.php';
        include_once 'views/dashboard/template.php' ;
    }

    //View dashboard système
    public function adminPanelSystem()
    {
        $view = 'dashboardSystem.php';
        include_once 'views/dashboard/template.php' ;
    }
    
    //Supprime un utilisateur et l'ensemble de ses fichiers du serveur et de la BDD
    public function deleteOneUserFromDashboard()
    {
        $newUser = new \Models\User();
        $user = $newUser->getOneUserById($_GET['id']);
        $newFiles = new \Models\Upload();
        $files = $newFiles->getAllFilesByUserIdForDashboard($user['id']);
        
        $dir = "uploads/".$user['username'];
        
        $this->deleteUserRootFolder($dir);
        
        $avatarDir = "uploads/avatars/".$user['username'];
        if (is_dir($avatarDir))
        {
            $files = scandir($avatarDir);
            
            foreach ($files as $file)
            {
                if ($file != '.' && $file != '..')
                {
                    if (filetype($avatarDir.'/'.$file) == 'dir')
                    {
                        rmdir($avatarDir.'/'.$file);
                    }
                    else
                    {
                        unlink($avatarDir.'/'.$file);
                    }
                }
            }
            
            reset($files);
            rmdir($avatarDir);
        }
        
        $newFiles->deleteFilesByUserId($user['id']);
        $newUser->deleteOneUser($user['id']);
        
        header('Location: index.php?route=adminPanelUsers');
        exit();
    }
    
    //Supprime le dossier racine de l'utilisateur en question ainsi que tout ce qui est dedans
    public function deleteUserRootFolder($dir)
    {
        $dirContent = scandir($dir);
        if($dirContent !== FALSE)
        {
            foreach ($dirContent as $file)
            {
                if(!in_array($file, array('.','..')))
                {
                    $file = $dir . '/' . $file;
                    
                    if(!is_dir($file))
                    {
                        unlink($file);
                    }
                    else
                    {
                        $this->deleteUserRootFolder($file);
                    }
                }
            }
        }
        rmdir($dir);
    }
    
    //Permet de telecharger l'intégralité des fichiers de tous les utilisateurs présents sur le serveur
    public function createAndDownloadZipOfAllServerFiles()
    {
        $source='uploads/';
        $today = date("dmY_His");
        $archive = 'backupFiles-'.$today.'.zip';
        $destination='uploads/'.$archive; 
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
    
    //Permet de telecharger la base de donnée entière (sauvegarde) ou forme .sql
    public function backupDatabase()
    {
        $newUpload = new \Models\Upload();
        $newUpload->backupDB();
    }
}
?>