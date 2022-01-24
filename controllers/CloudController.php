<?php

namespace Controllers;

class CloudController
{
    //View création de compte
    public function registerForm()
    {
        $view = 'register.php';
        include_once 'views/template.php' ;
    }

    //Ajoute un utilisateur à la base de donnée
    public function addUser()
    {
        try
        {
        
            if( isset( $_POST['username'] ) && !empty( $_POST['username'] ) &&
                isset( $_POST['mail'] ) && !empty( $_POST['mail'] ) &&
                isset( $_POST['password'] ) && !empty( $_POST['password'] ) &&
                isset( $_POST['confirmPassword'] ) && !empty( $_POST['confirmPassword'] ))
            {
                $newUser = new \Models\User();
                $users = $newUser->getAllUsers();
                $username = $_POST['username'];
                
                //Validation du format de l'email
                $email = $_POST["mail"];
                if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    throw new \Exception('Le format de l\'email est invalide');
                }
                
                //Vérification du format du mot de passe (8 caractères minimum, 1 majucule, 1 nombre, 1 symbole)
                $uppercase = preg_match('@[A-Z]@', $_POST['password']);
                $lowercase = preg_match('@[a-z]@', $_POST['password']);
                $number    = preg_match('@[0-9]@', $_POST['password']);
                $specialChars = preg_match('@[^\w]@', $_POST['password']);

                if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($_POST['password']) < 8)
                {
                    throw new \Exception('Le  mot de passe doit faire au moins 8 caractères, doit inclure une majuscule, un nombre et un symbole.');
                }
                else
                {
                    //Vérification si mail ou utilisateur existe déjà dans la BDD
                    $email = $_POST['mail'];
                    $existingUser =[];
                    $existingEmail =[];
                    foreach ($users as $user){
                        $existingUser[] = $user['username'];
                        $existingEmail[] = $user['email'];
                    }
                    if(in_array($username, $existingUser))
                    {
                        throw new \Exception( 'Cet utilisateur existe déjà, veuillez choisir un autre nom d\'utilisateur');
                    }
                    elseif(in_array($email, $existingEmail))
                    {
                        throw new \Exception('Un compte avec cet email existe déjà');
                    }
                    else
                    {
                        if($_POST['password'] === $_POST['confirmPassword'])
                        {
                            $sessionKey = sha1(uniqid());
                            $newUser->addOneUser(['uploads/avatars/default-avatar.svg', $_POST['username'], password_hash( $_POST['password'], PASSWORD_BCRYPT ), $_POST['mail'], $sessionKey ] );
                            $userId = $newUser->getOneUserBySessionKey($sessionKey);
    
                            //Création des dossier dédiés à l'utilisateur à la création de son compte
                            $dirname = "uploads/".$_POST['username'];
                            mkdir($dirname);
                            
                            $avatarFolder = "uploads/avatars/".$_POST['username'];
                            mkdir($avatarFolder);
    
                            $newUpload = new \Models\Upload();
                            
                            $dirData =[
                                $userId['id'],
                                "uploads",
                                $_POST['username'],
                                'dir',
                                0,
                                0
                            ];
                            $newUpload->addOneFile($dirData);
    
    
                            $success = "Compte créé avec succès ! Veuillez-vous connecter.";
                            header('Location: index.php?route=login&success=' . $success);
                            exit();
                        }
                        else
                        {
                            throw new \Exception( 'Les mots de passe ne sont pas identiques');
                        }
                    }
                }
            }
            else
            {
                throw new \Exception( 'Tous les champs doivent être remplis !');
            }
        }
        catch( \Exception $exeption )
            {
                header('Location: index.php?route=register&error=' . $exeption->getMessage() );
                exit();
            }
    }
   
    //View se connecter
    public function loginForm()
    {
        $view = 'login.php';
        include_once 'views/template.php';
    }
    
    //View home
    public function home()
    {
        $newUser = new \Models\User();
        $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);
        
        $view = 'home.php';
        include_once 'views/template.php';
    }

    //View mon compte
    public function account()
    {
        $newUser = new \Models\User();
        $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);
        $newFile = new \Models\Upload();
        $files = $newFile->getAllFilesByUserId($user['id'], 0);
        $nbOfFiles = count($files) - 1;
        $totalFilesSize = 0;
        
        //Conversion des tailles de fichiers
        for ($i=0; $i < $nbOfFiles ; $i++) {
         $totalFilesSize += $files[$i]['size'];
        }
        if(round($totalFilesSize  / 1024, 2) < 1000)
        {
            $unit = " Ko";
            $totalFilesSize = round($totalFilesSize  / 1024, 2) . $unit;
        }
        elseif(round($totalFilesSize / 1024 / 1024, 2) < 1000)
        {
            $unit = " Mo";
            $totalFilesSize = round($totalFilesSize / 1024 / 1024, 2) . $unit;
        }
        elseif(round($totalFilesSize / 1024 / 1024 / 1024, 2) < 1000)
        {
            $unit = " Go";
            $totalFilesSize = round($totalFilesSize / 1024 / 1024 / 1024, 2) . $unit;
        }
        
        $view = 'account.php';
        include_once 'views/template.php';
    }

    //View modifier mon compte
    public function modifyAccount()
    {
        $newUser = new \Models\User();
        $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);

        $view = 'modify_account.php';
        include_once 'views/template.php';
    }

    //Modification du compte dans la BDD
    public function updateAccount()
    {
        try
        {
            if( isset( $_POST['id'] ) && !empty( $_POST['id'] ) &&
                isset( $_POST['username'] ) && !empty( $_POST['username'] ) &&
                isset( $_POST['currentPassword'] ) && !empty( $_POST['currentPassword']))
            {
                $newUser = new \Models\User();
                $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);

                $users = $newUser->getAllUsers();
                
                    if( isset($_POST['password']) && !empty($_POST['password']) &&
                        isset($_POST['passwordConfirm']) && !empty($_POST['passwordConfirm']) &&
                        $_POST['email'] === "" &&
                        $_FILES['avatar']['error'] === 4)
                    {
                                
                                if( password_verify( $_POST['currentPassword'], $user['password'] ) )
                                {   
                                    //Vérification du format du mot de passe (8 caractères minimum, 1 majucule, 1 nombre, 1 symbole)
                                    $uppercase = preg_match('@[A-Z]@', $_POST['password']);
                                    $lowercase = preg_match('@[a-z]@', $_POST['password']);
                                    $number    = preg_match('@[0-9]@', $_POST['password']);
                                    $specialChars = preg_match('@[^\w]@', $_POST['password']);
                    
                                    if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($_POST['password']) < 8)
                                    {
                                        throw new \Exception('Le  mot de passe doit faire au moins 8 caractères, doit inclure une majuscule, un nombre et un symbole.');
                                    }
                                    
                                    if($_POST['password'] === $_POST['passwordConfirm'])
                                    {
                                        $sessionKey = sha1(uniqid());
                                        $newUserData = [
                                                        'username' => $_POST['username'],
                                                        'password' => password_hash( $_POST['password'], PASSWORD_BCRYPT ),
                                                        'sessionKey' => $sessionKey
                                                        ];

                                        $newUser->updateOneUser( $newUserData, $_POST['id'] );
            
                                        $newConnexion = new \Models\Connexion();
                                        $newConnexion->setSession( $_POST['username'], $_POST['id'], $sessionKey );
            
                                        header( 'Location: index.php?route=account' );
                                        exit();
                                    }
                                    else
                                    {
                                        throw new \Exception('Le nouveau mot de passe et sa confimation ne correspondent pas');
                                    }
                                }
                                else
                                {
                                    throw new \Exception('Le mot de passe actuel ne correspond pas');
                                }
                    }
                    
                    elseif( isset($_POST['password']) && !empty($_POST['password']) &&
                            isset($_POST['passwordConfirm']) && !empty($_POST['passwordConfirm']) &&
                            isset($_POST['email']) && !empty($_POST['email']) &&
                            $_FILES['avatar']['error'] === 4)
                    {
                                //Validation du format de l'email
                                $email = $_POST['email'];
                                if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                                {
                                    throw new \Exception('Le format de l\'email est invalide');
                                }
                                
                                $existingEmail =[];
                                foreach ($users as $userCompare){
                                    $existingEmail[] = $userCompare['email'];
                                }
                                if($_POST['email'] != "" &&  in_array($email, $existingEmail))
                                {
                                    throw new \Exception('Un compte avec cet email existe déjà');
                                }
                                else
                                {
                                    if( password_verify( $_POST['currentPassword'], $user['password'] ) )
                                    {   
                                        //Vérification du format du mot de passe (8 caractères minimum, 1 majucule, 1 nombre, 1 symbole)
                                        $uppercase = preg_match('@[A-Z]@', $_POST['password']);
                                        $lowercase = preg_match('@[a-z]@', $_POST['password']);
                                        $number    = preg_match('@[0-9]@', $_POST['password']);
                                        $specialChars = preg_match('@[^\w]@', $_POST['password']);
                        
                                        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($_POST['password']) < 8)
                                        {
                                            throw new \Exception('Le  mot de passe doit faire au moins 8 caractères, doit inclure une majuscule, un nombre et un symbole.');
                                        }
                                        
                                        if($_POST['password'] === $_POST['passwordConfirm'])
                                        {
                                            $sessionKey = sha1(uniqid());
                                            $newUserData = [
                                                            'username' => $_POST['username'],
                                                            'password' => password_hash( $_POST['password'], PASSWORD_BCRYPT ),
                                                            'email' => $_POST['email'],
                                                            'sessionKey' => $sessionKey
                                                            ];
    
                                            $newUser->updateOneUser( $newUserData, $_POST['id'] );
                
                                            $newConnexion = new \Models\Connexion();
                                            $newConnexion->setSession( $_POST['username'], $_POST['id'], $sessionKey );
                
                                            header( 'Location: index.php?route=account' );
                                            exit();
                                        }
                                        else
                                        {
                                            throw new \Exception('Le nouveau mot de passe et sa confimation ne correspondent pas');
                                        }
                                    }
                                    else
                                    {
                                        throw new \Exception('Le mot de passe actuel ne correspond pas');
                                    }
                                }
                    }
                    
                    elseif( isset($_POST['password']) && !empty($_POST['password']) &&
                            isset($_POST['passwordConfirm']) && !empty($_POST['passwordConfirm']) &&
                            $_POST['email'] === "" &&
                            $_FILES['avatar']['error'] !== 4)
                    {
                                
                                if( password_verify( $_POST['currentPassword'], $user['password'] ) )
                                    { 
                                        //Vérification du format du mot de passe (8 caractères minimum, 1 majucule, 1 nombre, 1 symbole)
                                        $uppercase = preg_match('@[A-Z]@', $_POST['password']);
                                        $lowercase = preg_match('@[a-z]@', $_POST['password']);
                                        $number    = preg_match('@[0-9]@', $_POST['password']);
                                        $specialChars = preg_match('@[^\w]@', $_POST['password']);
                        
                                        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($_POST['password']) < 8)
                                        {
                                            throw new \Exception('Le  mot de passe doit faire au moins 8 caractères, doit inclure une majuscule, un nombre et un symbole.');
                                        }
                                        
                                        if($_POST['password'] === $_POST['passwordConfirm'])
                                        {
                                            
                                            $allowedImage = array("image/jpeg", "image/gif", "image/png");
                                            if(!in_array($_FILES['avatar']['type'], $allowedImage))
                                            {
                                                throw new \Exception('Uniquement .jpg, .gif et .png sont autorisés');
                                            }
                                            else
                                            {
                                                if( $_FILES['avatar']['error'] === 0 )
                                                {
                                                    $file = $_FILES['avatar']['name'];
                                                    $path = "uploads/avatars/" . $_POST['username'] . "/";
                            
                                                    if( !file_exists( $path . $file ) )
                                                    {
                                                        $uploads_folder = $path;
                                                        $temp_name      = $_FILES['avatar']['tmp_name'];
                                                        $file           = $_FILES['avatar']['name'];
                                                        $destination    = $uploads_folder . $file;
                            
                                                        move_uploaded_file( $temp_name, $destination );
                            
                                                    }
                                                }
                                                elseif( $_FILES['avatar']['error'] === 1 || $_FILES['avatar']['error'] === 2)
                                                {
                                                    throw new \Exception('Le fichier est supérieur à 2 Mo');
                                                }
                                                
                                                $sessionKey = sha1(uniqid());
                                                $newUserData = [
                                                    'avatar' => $path . $file,
                                                    'username' => $_POST['username'],
                                                    'password' => password_hash( $_POST['password'], PASSWORD_BCRYPT ),
                                                    'email' => $_POST['email'],
                                                    'sessionKey' => $sessionKey
                                                ];
                    
                                                $newUser->updateOneUser( $newUserData, $_POST['id'] );
                    
                                                $newConnexion = new \Models\Connexion();
                                                $newConnexion->setSession( $_POST['username'], $_POST['id'], $sessionKey );
                    
                                                header( 'Location: index.php?route=account' );
                                                exit();
                                            }
                                        }
                                        else
                                        {
                                            throw new \Exception('Le nouveau mot de passe et sa confimation ne correspondent pas');
                                        }
                                    }
                                    else
                                    {
                                        throw new \Exception('Le mot de passe actuel ne correspond pas');
                                    }
                    }
                    
                    elseif( isset($_POST['password']) && !empty($_POST['password']) &&
                            isset($_POST['passwordConfirm']) && !empty($_POST['passwordConfirm']) &&
                            isset($_POST['email']) && !empty($_POST['email']) &&
                            $_FILES['avatar']['error'] !== 4)
                    {
                                //Validation du format de l'email
                                $email = $_POST['email'];
                                if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                                {
                                    throw new \Exception('Le format de l\'email est invalide');
                                }
                                
                                $existingEmail =[];
                                foreach ($users as $userCompare){
                                    $existingEmail[] = $userCompare['email'];
                                }
                                if(in_array($email, $existingEmail))
                                {
                                    throw new \Exception('Un compte avec cet email existe déjà');
                                }
                                else
                                {
                                    if( password_verify( $_POST['currentPassword'], $user['password'] ) )
                                    {   
                                        //Vérification du format du mot de passe (8 caractères minimum, 1 majucule, 1 nombre, 1 symbole)
                                        $uppercase = preg_match('@[A-Z]@', $_POST['password']);
                                        $lowercase = preg_match('@[a-z]@', $_POST['password']);
                                        $number    = preg_match('@[0-9]@', $_POST['password']);
                                        $specialChars = preg_match('@[^\w]@', $_POST['password']);
                        
                                        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($_POST['password']) < 8)
                                        {
                                            throw new \Exception('Le  mot de passe doit faire au moins 8 caractères, doit inclure une majuscule, un nombre et un symbole.');
                                        }
                                        
                                        if($_POST['password'] === $_POST['passwordConfirm'])
                                        {
                                            $allowedImage = array("image/jpeg", "image/gif", "image/png");
                                            if(!in_array($_FILES['avatar']['type'], $allowedImage))
                                            {
                                                throw new \Exception('Uniquement .jpg, .gif et .png sont autorisés');
                                            }
                                            else
                                            {
                                                if( $_FILES['avatar']['error'] === 0 )
                                                {
                                                    $file = $_FILES['avatar']['name'];
                                                    $path = "uploads/avatars/" . $_POST['username'] . "/";
                            
                                                    if( !file_exists( $path . $file ) )
                                                    {
                                                        $uploads_folder = $path;
                                                        $temp_name      = $_FILES['avatar']['tmp_name'];
                                                        $file           = $_FILES['avatar']['name'];
                                                        $destination    = $uploads_folder . $file;
                            
                                                        move_uploaded_file( $temp_name, $destination );
                            
                                                    }
                                                }
                                                elseif( $_FILES['avatar']['error'] === 1 || $_FILES['avatar']['error'] === 2)
                                                {
                                                    throw new \Exception('Le fichier est supérieur à 2 Mo');
                                                }
                                                
                                                $sessionKey = sha1(uniqid());
                                                $newUserData = [
                                                    'avatar' => $path . $file,
                                                    'username' => $_POST['username'],
                                                    'password' => password_hash( $_POST['password'], PASSWORD_BCRYPT ),
                                                    'email' => $_POST['email'],
                                                    'sessionKey' => $sessionKey
                                                ];
                    
                                                $newUser->updateOneUser( $newUserData, $_POST['id'] );
                    
                                                $newConnexion = new \Models\Connexion();
                                                $newConnexion->setSession( $_POST['username'], $_POST['id'], $sessionKey );
                    
                                                header( 'Location: index.php?route=account' );
                                                exit();
                                            }
                                        }
                                        else
                                        {
                                            throw new \Exception('Le nouveau mot de passe et sa confimation ne correspondent pas');
                                        }
                                    }
                                    else
                                    {
                                        throw new \Exception('Le mot de passe actuel ne correspond pas');
                                    }
                                }
                    }
                    
                    elseif( $_POST['password'] === "" &&
                            $_POST['passwordConfirm'] === "" &&
                            $_POST['email'] === "" &&
                            $_FILES['avatar']['error'] !== 4)
                    {
                    
                                
                                $allowedImage = array("image/jpeg", "image/gif", "image/png");
                                if(!in_array($_FILES['avatar']['type'], $allowedImage))
                                {
                                    throw new \Exception('Uniquement .jpg, .gif et .png sont autorisés');
                                }
                                else
                                {
                                    if( $_FILES['avatar']['error'] === 0 )
                                    {
                                        $file = $_FILES['avatar']['name'];
                                        $path = "uploads/avatars/" . $_POST['username'] . "/";
                
                                        if( !file_exists( $path . $file ) )
                                        {
                                            $uploads_folder = $path;
                                            $temp_name      = $_FILES['avatar']['tmp_name'];
                                            $file           = $_FILES['avatar']['name'];
                                            $destination    = $uploads_folder . $file;
                
                                            move_uploaded_file( $temp_name, $destination );
                
                                        }
                                    }
                                    elseif( $_FILES['avatar']['error'] === 1 || $_FILES['avatar']['error'] === 2)
                                    {
                                        throw new \Exception('Le fichier est supérieur à 2 Mo');
                                    }
                                    
                                    $sessionKey = sha1(uniqid());
                                    $newUserData = [
                                        'avatar' => $path . $file,
                                        'username' => $_POST['username'],
                                        'password' => password_hash( $_POST['currentPassword'], PASSWORD_BCRYPT ),
                                        'sessionKey' => $sessionKey
                                    ];
        
                                    $newUser->updateOneUser( $newUserData, $_POST['id'] );
        
                                    $newConnexion = new \Models\Connexion();
                                    $newConnexion->setSession( $_POST['username'], $_POST['id'], $sessionKey );
        
                                    header( 'Location: index.php?route=account' );
                                    exit();
                                }
                    }
                    
                    elseif( $_POST['password'] === "" &&
                            $_POST['passwordConfirm'] === "" &&
                            isset($_POST['email']) && !empty($_POST['email']) &&
                            $_FILES['avatar']['error'] !== 4)
                    {
                                //Validation du format de l'email
                                $email = $_POST['email'];
                                if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                                {
                                    throw new \Exception('Le format de l\'email est invalide');
                                }
                                
                                $existingEmail =[];
                                foreach ($users as $userCompare){
                                    $existingEmail[] = $userCompare['email'];
                                }
                                if($_POST['email'] != "" &&  in_array($email, $existingEmail))
                                {
                                    throw new \Exception('Un compte avec cet email existe déjà');
                                }
                                
                                $allowedImage = array("image/jpeg", "image/gif", "image/png");
                                if(!in_array($_FILES['avatar']['type'], $allowedImage))
                                {
                                    throw new \Exception('Uniquement .jpg, .gif et .png sont autorisés');
                                }
                                else
                                {
                                    if( $_FILES['avatar']['error'] === 0 )
                                    {
                                        $file = $_FILES['avatar']['name'];
                                        $path = "uploads/avatars/" . $_POST['username'] . "/";
                
                                        if( !file_exists( $path . $file ) )
                                        {
                                            $uploads_folder = $path;
                                            $temp_name      = $_FILES['avatar']['tmp_name'];
                                            $file           = $_FILES['avatar']['name'];
                                            $destination    = $uploads_folder . $file;
                
                                            move_uploaded_file( $temp_name, $destination );
                
                                        }
                                    }
                                    elseif( $_FILES['avatar']['error'] === 1 || $_FILES['avatar']['error'] === 2)
                                    {
                                        throw new \Exception('Le fichier est supérieur à 2 Mo');
                                    }
                                    
                                    $sessionKey = sha1(uniqid());
                                    $newUserData = [
                                        'avatar' => $path . $file,
                                        'username' => $_POST['username'],
                                        'password' => password_hash( $_POST['currentPassword'], PASSWORD_BCRYPT ),
                                        'email' => $_POST['email'],
                                        'sessionKey' => $sessionKey
                                    ];
        
                                    $newUser->updateOneUser( $newUserData, $_POST['id'] );
        
                                    $newConnexion = new \Models\Connexion();
                                    $newConnexion->setSession( $_POST['username'], $_POST['id'], $sessionKey );
        
                                    header( 'Location: index.php?route=account' );
                                    exit();
                                }
                    }
                    
                    elseif( $_POST['password'] === "" &&
                            $_POST['passwordConfirm'] === "" &&
                            isset($_POST['email']) && !empty($_POST['email']) &&
                            $_FILES['avatar']['error'] === 4)
                    {
                                //Validation du format de l'email
                                $email = $_POST['email'];
                                if (!filter_var($email, FILTER_VALIDATE_EMAIL))
                                {
                                    throw new \Exception('Le format de l\'email est invalide');
                                }
                                
                                $existingEmail =[];
                                foreach ($users as $userCompare){
                                    $existingEmail[] = $userCompare['email'];
                                }
                                if($_POST['email'] != "" &&  in_array($email, $existingEmail))
                                {
                                    throw new \Exception('Un compte avec cet email existe déjà');
                                }
                                
                                $sessionKey = sha1(uniqid());
                                $newUserData = [
                                    'username' => $_POST['username'],
                                    'password' => password_hash( $_POST['currentPassword'], PASSWORD_BCRYPT ),
                                    'email' => $_POST['email'],
                                    'sessionKey' => $sessionKey
                                ];
    
                                $newUser->updateOneUser( $newUserData, $_POST['id'] );
    
                                $newConnexion = new \Models\Connexion();
                                $newConnexion->setSession( $_POST['username'], $_POST['id'], $sessionKey );
    
                                header( 'Location: index.php?route=account' );
                                exit();
                    }
                
            }
            else
            {
                throw new \Exception('Remplissez les champs obligatoires');
            }
        }
        catch( \Exception $exeption )
        {
            header('Location: index.php?route=modifyAccount&error=' . $exeption->getMessage() );
            exit();
        }
    }
    
    //Permet de supprimer son compte ainsi que tous ses dossiers
    public function deleteMyAccount()
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
        
        
        header('Location: index.php?route=logout');
        exit();
    }
    
    //Supprime le dossier racine de l'utilisateur et tout ce qu'il y a dedans
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

    //Met a jour les informations du compte via le dashboard
    public function updateAccountAsAdmin()
    {
        try
        {
            if( isset( $_POST['id'] ) && !empty( $_POST['id'] ) &&
                isset( $_POST['username'] ) && !empty( $_POST['username'] ))
            {
                $newUser = new \Models\User();
                $user = $newUser->getOneUserBySessionKey($_SESSION['sessionKey']);

                $users = $newUser->getAllUsers();
                $email = $_POST['email'];

                $existingEmail =[];
                foreach ($users as $userCompare){
                    $existingEmail[] = $userCompare['email'];
                }
                if($_POST['email'] != "" &&  in_array($email, $existingEmail))
                {
                    throw new \Exception('Un compte avec cet email existe déjà');
                }
                else
                {   
                    if( isset( $_POST['password'] ) && !empty( $_POST['password'])&&
                        isset( $_POST['passwordConfirm'] ) && !empty( $_POST['passwordConfirm']))
                    {
                        if($_POST['email'] == "" && $_POST['password'] === $_POST['passwordConfirm'])
                        {
                            $sessionKey = sha1(uniqid());
                            $newUserData = [
                                'username' => $_POST['username'],
                                'password' => password_hash( $_POST['password'], PASSWORD_BCRYPT ),
                                'sessionKey' => $sessionKey
                            ];

                            $newUser->updateOneUser( $newUserData, $_POST['id'] );
                            header( 'Location: index.php?route=adminPanelUserDetails&id='.$user['id']);
                            exit();
                        }
                        elseif($_POST['email']!= "" && $_POST['password'] === $_POST['passwordConfirm'] )
                        {
                            $sessionKey = sha1(uniqid());
                            $newUserData = [
                                'username' => $_POST['username'],
                                'password' => password_hash( $_POST['password'], PASSWORD_BCRYPT ),
                                'email' => $_POST['email'],
                                'sessionKey' => $sessionKey
                            ];
                            $newUser->updateOneUser( $newUserData, $_POST['id'] );
                            header( 'Location: index.php?route=adminPanelUserDetails&id='.$user['id']);
                            exit();
                        }
                        else
                        {
                            throw new \Exception('Le nouveau mot de passe et sa confimation ne correspondent pas');
                        }
                    }
                    else
                    {
                        $sessionKey = sha1(uniqid());
                            $newUserData = [
                                'username' => $_POST['username'],
                                'email' => $_POST['email'],
                                'sessionKey' => $sessionKey
                            ];
                            $newUser->updateOneUser( $newUserData, $_POST['id'] );

                            header( 'Location: index.php?route=adminPanelUserDetails&id='.$user['id']);
                            exit();
                    }
                }
            }
            else
            {
                throw new \Exception('Tous les champs obligatoires doivent être remplis');
            }
        }
        catch( \Exception $exeption )
        {
            header('Location: index.php?route=adminPanelUserModify&id='. $_POST['id'] .'&error=' . $exeption->getMessage() );
            exit();
        }
    }
    
    //permet de compresser et de telecharger tous les fichiers de l'utilisateur via la page mon compte
    public function createAndDownloadZipFile()
    {
        $newUser = new \Models\User();
        $user = $newUser->getOneUserById($_GET['id']);
        
        $today = date("dmY_His");
        $source='uploads/' . $user['username'];
        $archive = $user['username']. $today .'.zip';
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