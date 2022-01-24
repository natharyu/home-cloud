<?php
namespace Models;

include_once 'Database.php';

class User extends Database{
    
    //Récupère tous les utilisateurs de la BDD
    public function getAllUsers()
    {
        return $this->getAll('users');
    }
    
    //Récupère un utilisateur de la BDD via son username
    public function getOneUser( $value )
    {
        return $this->getOne( 'users', 'username', $value );
    }
    
    //Récupère un utilisateur de la BDD via son id
    public function getOneUserById( $value )
    {
        return $this->getOne( 'users', 'id', $value );
    }
    
    //Récupère un utilisateur de la BDD via sa sessionKey
    public function getOneUserBySessionKey( $value )
    {
        return $this->getOne( 'users', 'sessionKey', $value );
    }
    
    //Ajoute un utilisateur à la BDD
    public function addOneUser( $data )
    {
        $this->addOne( 'users', 'avatar, username, password, email, sessionKey', '?, ?, ?, ?, ?', $data );
    }
    
    //Modifie un utilisaeur dans la BDD
    public function updateOneUser( $data, $id )
    {
        $this->updateOne( 'users', $data, 'id', $id );
    }
    
    //Supprime un utilisateur de la BDD
    public function deleteOneUser($id)
    {
        $this->deleteOne( 'users', 'id', $id );
    }
}

?>