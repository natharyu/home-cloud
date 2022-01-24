<?php

namespace Models;

include_once 'Database.php';

class Todolist extends Database{
    
    //Recupère toutes les données de la table todolist
    public function getAllTodolist()
    {
        return $this->getAll('todolist');
    }
    
    //Récupère une tàche en particulier via son nom
    public function getOneTodolist( $value )
    {
        return $this->getOne( 'todolist', 'task', $value );
    }
    
    //Récupère une tàche en particulier via son id
    public function getOneTaskById( $value )
    {
        return $this->getOne( 'todolist', 'id', $value );
    }

    //Récupère toutes les tàches d'un utilisateur
    public function getTasksByUserId( $value )
    {
        return $this->getAllOne( 'todolist', 'user_id', $value );
    }
    
    //Récupère toutes les tàches selon le statut done
    public function getTasksByDoneStatus( $done_value )
    {
        return $this->getAllOne( 'todolist', 'done', $done_value );
    }
    
    //Récupère toutes les tàches d'un utilisateur selon leur statut done
    public function getAllOneTwoConditions( $userID, $done )
    {
        return $this->getAllOneManyConditions( 'todolist', ['user_id ' => $userID, 'done ' => $done] );
    }
    
    //Ajoute une tàche en BDD
    public function addOneTask( $data )
    {
        $this->addOne( 'todolist', 'task, done, user_id', '?, ?, ?', $data );
    }
    
    //Modifie une tàche en BDD
    public function updateOneTask( $data, $id )
    {
        $this->updateOne( 'todolist', $data, 'id', $id );
    }
    
    //supprime une tàche en BDD
    public function deleteOneTask($value)
    {
        $this->deleteOne( 'todolist', 'id' , $value);
    }
    
    //Supprime toutes les tàches d'un utilisateur
    public function deleteAllTasksForOneUser($value)
    {
        $this->deleteOne( 'todolist', 'user_id' , $value);
    }
}

?>