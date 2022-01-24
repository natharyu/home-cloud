<?php
namespace Models;

include_once 'Database.php';

class Upload extends Database{
    
    //Récupère tous les fichiers de la BDD
    public function getAllFiles()
    {
        return $this->getAll('files');
    }

    //Récupère tous les fichiers de la BDD selon un utilisateur, un chemin et leur statut deleted
    public function getAllFilesByPath($userID, $path, $deleted)
    {
        return $this->getAllOneManyConditions('files', ['user_id' => $userID, 'path' => $path, 'deleted' => $deleted], 'created_at DESC');
    }

    //Récupère tous les fichiers de la BDD selon un utilisateur et un chemin 
    public function getAllFilesByOnlyPath($userID, $path)
    {
        return $this->getAllOneManyConditions('files', ['user_id' => $userID, 'path' => $path]);
    }

    //Récupère tous les fichiers de la BDD selon un utilisateur et un type de fichier et un statut deleted
    public function getAllFilesByUserByType($userID, $deleted, $type)
    {
        return $this->getAllOneByUserByType('files', 'user_id = '. $userID, 'deleted = '. $deleted,  'type',  $type);
    }

    //Récupère tous les fichiers de la BDD selon un utilisateur, un chemin, un type de fichier et un statu deleted
    public function getAllFilesByPathByType($userID, $path, $deleted, $type)
    {
        return $this->getAllOneManyConditions('files', ['user_id' => $userID, 'path' => $path, 'deleted' => $deleted, 'type' => $type]);
    }

    //Récupère tous les fichiers de la BDD selon un utilisateur et un statut deleted
    public function getAllFilesByUserId($userID, $deleted)
    {
        return $this->getAllOneManyConditions('files', ['user_id' => $userID, 'deleted' => $deleted], 'created_at DESC');
    }
    
    //Récupère tous les fichiers de la BDD selon un utilisateur pour l'affichage sur le dashboard
    public function getAllFilesByUserIdForDashboard($userID)
    {
        return $this->getAllOneManyConditions('files', ['user_id' => $userID]);
    }
    
    //Récupère un fichier de la BDD selon un utilisateur et un nom
    public function getOneFile($userID, $value )
    {
        return $this->getOneManyConditions( 'files', ['user_id' => $userID, 'name' => $value] );
    }
    
    //Récupère un fichier de la BDD selon un utilisateur et un id
    public function getOneFileById($userID, $value )
    {
        return $this->getOneManyConditions( 'files',['user_id' => $userID, 'id'=> $value] );
    }

    //Récupère un fichier de la BDD selon un utilisateur, un chemin et un nom
    public function getOneFileByPathAndName($userID, $path, $name)
    {
        return $this->getOneManyConditions( 'files', ['user_id' => $userID, 'path' =>$path, 'name'=> $name]);
    }

    //Récupère le dossier racine d'un utilisateur de la BDD
    public function getUserRootFiles( $username )
    {
        return $this->getOne( 'files', 'path','uploads/' . $username );
    }

    //Récupère les fichiers de la BDD selon un type
    public function getAllFilesByType( $value )
    {
        return $this->getAllOneByType( 'files', 'type', $value );
    }
    
    //Ajoute un fichier à la BDD
    public function addOneFile( $data )
    {
        $this->addOne( 'files', 'user_id, path, name, type, size, deleted', '?, ?, ?, ?, ?, ?', $data );
    }
    
    //Modifie un fichier de la BDD
    public function updateOneFile( $data, $id )
    {
        $this->updateOne( 'files', $data, 'id', $id );
    }

    //Supprime un fichier de la BDD
    public function deleteOneFile($id)
    {
        $this->deleteOne( 'files', 'id', $id );
    }
    
    //Supprime tous les fichiers de la BDD d'un utilisateur
    public function deleteFilesByUserId($id)
    {
        $this->deleteOne( 'files', 'user_id', $id );
    }
    
    //Permet la création et le telechargmeent de la BDD via dashboard
    public function backupDB()
    {
        return $this->backupTables();
    }
}

?>