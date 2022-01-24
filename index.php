<?php
session_start();

spl_autoload_register(function ($class) {
    require_once lcfirst(str_replace('\\', '/', $class)) . '.php'; 
});

// Router avec switch

if( array_key_exists( 'route', $_GET ) )
{
    $session = new \Controllers\SessionController();
    
    switch( $_GET['route'] )
    {
        case '':
            
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }
            
            header( 'Location: index.php?route=home' );
            exit();
            
            break;
            
        case 'login':

            $controller = new \Controllers\CloudController();
            $controller->loginForm();

            break;

        case 'loginCheck':

            $controller = new \Controllers\SessionController();
            $controller->login();

            break;
        
        case 'logout':

            $controller = new \Controllers\SessionController();
            $controller->logout();

            break;

        case 'register':

            $controller = new \Controllers\CloudController();
            $controller->registerForm();

            break;

        case 'addUser':

            $controller = new \Controllers\CloudController();
            $controller->addUser();

            break;

        case 'home':
            
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }
            
            $controller = new \Controllers\CloudController();
            $controller->home();
            
            break;
        
        case 'account':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }
            
            $controller = new \Controllers\CloudController();
            $controller->account();

            break;
        
        case 'modifyAccount':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\CloudController();
            $controller->modifyAccount();

            break;
        
        case 'updateAccount':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\CloudController();
            $controller->updateAccount();

            break;

        case 'updateAccountAsAdmin':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\CloudController();
            $controller->updateAccountAsAdmin();

            break;
        
        case 'deleteMyAccount':
        
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=home' );
                exit();
            }
            
            $controller = new \Controllers\CloudController();
            $controller->deleteMyAccount();

            break;
            
        case 'downloadZip':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\CloudController();
            $controller->createAndDownloadZipFile();
            
            break;

        case 'files':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\FilesController();
            $controller->files();
    
            break;
        
        case 'images':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\FilesController();
            $controller->images();
    
            break;

        case 'audios':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\FilesController();
            $controller->audios();
    
            break;

        case 'videos':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\FilesController();
            $controller->videos();
        
            break;
        
        case 'addFolder':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\FilesController();
            $controller->addFolder();
        
            break;

        case 'addFile':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\FilesController();
            $controller->addFile();
            
            break;

        case 'createFolder':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\FilesController();
            $controller->createFolder();

            break;
        
        case 'searchInFiles':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\FilesController();
            $controller->searchInFiles($_POST);

            break;
        
        case 'trashFiles':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\FilesController();
            $controller->trashFiles();

            break;
        
        case 'moveToTrash':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\FilesController();
            $controller->moveToTrash();

            break;

        case 'deleteFromTrash':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\FilesController();
            $controller->deleteFromTrash();

            break;

        case 'restoreFromTrash':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\FilesController();
            $controller->restoreFromTrash();

            break;
        
        case 'downloadOneFile':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\FilesController();
            $controller->downloadOneFile();

            break;
            
        case 'downloadOneFolder':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\FilesController();
            $controller->downloadOneFolder();

            break;

        case 'todolist':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\AppsController();
            $controller->todolist();

            break;

        case 'todolistDone':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\AppsController();
            $controller->todolistDone();

            break;

        case 'todolistNotDone':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\AppsController();
            $controller->todolistNotDone();
    
            break;

        case 'todolistAddTask':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\AppsController();
            $controller->todolistAddTask();
    
            break;

        case 'todolistModifyTaskDoneStatus':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\AppsController();
            $controller->modifyTaskDoneStatus();
        
            break;

        case 'todolistDeleteOneTask':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\AppsController();
            $controller->deleteOneTask();

            break;
        
        case 'todolistDeleteAllTasksForOneUser':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\AppsController();
            $controller->deleteAllTasksForOneUser();

            break;
        
        case 'weather':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\AppsController();
            $controller->weather();
    
            break;
        
        case 'weatherSearchCity':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\AppsController();
            $controller->weatherSearchByCity();
    
            break;
        
        case 'calendar':
            if( !$session->isLogged() )
            {
                header( 'Location: index.php?route=login' );
                exit();
            }

            $controller = new \Controllers\AppsController();
            $controller->calendar();
    
            break;

        case 'adminPanel':
            
            if( !$session->isAdmin() )
            {
                header( 'Location: index.php?route=home' );
                exit();
            }
            
            $controller = new \Controllers\DashboardController();
            $controller->adminPanel();

            break;

        case 'adminPanelUsers':
            
            if( !$session->isAdmin() )
            {
                header( 'Location: index.php?route=home' );
                exit();
            }
            
            $controller = new \Controllers\DashboardController();
            $controller->adminPanelUsers();

            break;
        
        case 'adminPanelUserDetails':
            
            if( !$session->isAdmin() )
            {
                header( 'Location: index.php?route=home' );
                exit();
            }
            
            $controller = new \Controllers\DashboardController();
            $controller->adminPanelUserDetails();

            break;

        case 'adminPanelUserModify':
        
            if( !$session->isAdmin() )
            {
                header( 'Location: index.php?route=home' );
                exit();
            }
            
            $controller = new \Controllers\DashboardController();
            $controller->adminPanelUserModify();

            break;
        
        case 'deleteOneUserFromDashboard':
        
            if( !$session->isAdmin() )
            {
                header( 'Location: index.php?route=home' );
                exit();
            }
            
            $controller = new \Controllers\DashboardController();
            $controller->deleteOneUserFromDashboard();

            break;

        case 'adminPanelSystem':
        
            if( !$session->isAdmin() )
            {
                header( 'Location: index.php?route=home' );
                exit();
            }
            
            $controller = new \Controllers\DashboardController();
            $controller->adminPanelSystem();

            break;
            
        case 'adminPanelSystemDownloadAllFiles':
        
            if( !$session->isAdmin() )
            {
                header( 'Location: index.php?route=home' );
                exit();
            }
            
            $controller = new \Controllers\DashboardController();
            $controller->createAndDownloadZipOfAllServerFiles();

            break;
            
        case 'backupDatabase':
        
            if( !$session->isAdmin() )
            {
                header( 'Location: index.php?route=home' );
                exit();
            }
            
            $controller = new \Controllers\DashboardController();
            $controller->backupDatabase();

            break;
        
        default:
            
            header( 'Location: index.php?route=home' );
            exit();
            
        break;
    }
}
else
{
    header( 'Location: index.php?route=home' );
    exit;
}
?>