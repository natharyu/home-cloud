<?php

namespace Controllers;

class SessionController
{
    //Permet de se connecter
    public function login()
    {
        try
        {
            if( isset( $_POST['username'] ) && !empty( $_POST['username'] ) &&
                isset( $_POST['password'] ) && !empty( $_POST['password'] ))
            {
                $newUser = new \Models\User();
                $user = $newUser->getOneUser( $_POST['username'] );
                if($user)
                {
                    if( password_verify( $_POST['password'], $user['password'] ))
                    {
                        $newConnexion = new \Models\Connexion();
                        $sessionKey = sha1(uniqid());

                        $newUserData = [
                            'username' => $user['username'],
                            'sessionKey' => $sessionKey,
                        ];

                        $newUser->updateOneUser( $newUserData, $user['id'] );

                        $newConnexion->setSession( $_POST['username'], $user['id'], $sessionKey );

                        header('Location: index.php?route=home');
                        exit();
                    }
                    else
                    {
                        throw new \Exception('Mauvais utilisateur et/ou mot de passe');
                    }
                }
                else
                {

                    throw new \Exception('Aucun utilisateur trouvé sous ce nom');
                }         
            }
            else
            {
                throw new \Exception('Tous les champs doivent être renseignés');
            }
        }
        catch( \Exception $exeption )
        {
            header('Location: index.php?route=login&error=' . $exeption->getMessage() );
            exit();
        }
    }
    
    //permet de se déconnecter
    public function logout()
    {
        $closeConnexion = new \Models\Connexion();
        $closeConnexion->closeSession();

        header('Location: index.php?route=login');
        exit();
    }
    
    //permet de verifier si l'utilisateur est connecter
    public function isLogged() : bool
    {
        if( isset( $_SESSION['sessionKey'] ) && !empty( $_SESSION['sessionKey'] ) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    //Permet de verfier si l'utilisateur est un administrateur ou non
    public function isAdmin() : bool
    {
        if( $this->isLogged()  )
        {
            $userModel = new \Models\User();
            $user      = $userModel->getOneUserBySessionKey( $_SESSION['sessionKey'] );
            
            if( $user['role'] === 'admin' )
            {
                return true;
            }
            else
            {
                return false;
            }
            
        }
        else
        {
            return false;
        }
    }
    
}
?>