<?php
namespace Models;

include_once 'Database.php';

class Connexion extends Database{
    
    //Connecte l'utilisateur
    public function setSession( $user, $id, $sessionKey )
    {
        $_SESSION['username'] = $user;
        $_SESSION['id'] = $id;
        $_SESSION['sessionKey'] = $sessionKey;
    }
    
    //Deconnecte l'utilisateur
    public function closeSession(){
        session_destroy();
    }
}
?>