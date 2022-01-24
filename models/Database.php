<?php
namespace Models;

abstract class Database {
    
    private static $_dbConnect;
    
    //Connexion à la BDD
    private static function setDb()
    {
        self::$_dbConnect = new \PDO( 'mysql:host=database:3306;dbname=home_cloud;charset=utf8', 'root', 'Tit@ncheat287');
        self::$_dbConnect->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_WARNING );
    }
    
    protected function getDb()
    {
        if( self::$_dbConnect == null)
        {
            self::setdB();
        }
        
        return self::$_dbConnect;
    }
    
    //recupère toutes les données d'une table
    protected function getAll( $table )
    {
        $sql = 'SELECT * FROM ' . $table;
        $query = $this->getDb()->prepare( $sql );
        $query->execute();
        
        return $query->fetchAll( \PDO::FETCH_ASSOC );
    }
    
    //recupère une donnée d'une table selon une condition
    protected function getOne( $table, $condition, $value )
    {
        $sql = 'SELECT * FROM '. $table .' WHERE '. $condition .' = ?';
        $query = $this->getDb()->prepare( $sql );
        $query->execute( [$value] );

        return $query->fetch( \PDO::FETCH_ASSOC );
    }
    
    //recupère une donnée d'une table selon plusieurs conditions
    protected function getOneManyConditions( $table, $args, $order = "" )
    {
        $conditions = '';
        $values = [];
        foreach( $args as $key => $value )
        {
            $conditions .= "$key = ? AND ";
            array_push( $values, $value );
        }
        $conditions = substr( $conditions, 0, -5 );

        $sql = 'SELECT * FROM '. $table .' WHERE '. $conditions;
        if($order !== ""){
            $sql .= " ORDER BY ".$order;
        }
        $query = $this->getDb()->prepare( $sql );
        $query->execute($values);
        return $query->fetch( \PDO::FETCH_ASSOC );
    }
    
    //recupère les données d'une table selon une condition
    protected function getAllOne( $table, $condition, $value )
    {
        $sql = 'SELECT * FROM '. $table .' WHERE '. $condition .' = ?';
        $query = $this->getDb()->prepare( $sql );
        $query->execute( [$value] );
        return $query->fetchAll( \PDO::FETCH_ASSOC );
    }

    //recupère les données d'une table selon plusieurs conditions
    protected function getAllOneManyConditions( $table, $args, $order = "" )
    {
        $conditions = '';
        $values = [];
        foreach( $args as $key => $value )
        {
            $conditions .= "$key = ? AND ";
            array_push( $values, $value );
        }
        $conditions = substr( $conditions, 0, -5 );

        $sql = 'SELECT * FROM '. $table .' WHERE '. $conditions;
        if($order !== ""){
            $sql .= " ORDER BY ".$order;
        }
        $query = $this->getDb()->prepare( $sql );
        $query->execute($values);
        return $query->fetchAll( \PDO::FETCH_ASSOC );
    }

    //recupère les données d'une table selon l'utilisateur et le type de fichier
    protected function getAllOneByUserByType( $table, $condition1, $condition2, $condition3, $value )
    {
        $sql = 'SELECT * FROM '. $table .' WHERE '. $condition1 . " AND " . $condition2 . " AND " . $condition3 .' LIKE ?';
        $query = $this->getDb()->prepare( $sql );
        $query->execute( [$value] );
        return $query->fetchAll( \PDO::FETCH_ASSOC );
    }
    
    //ajoute une donnée en BDD
    protected function addOne( $table, $columns, $values, $data )
    {
        $sql = 'INSERT INTO '. $table .' ( '. $columns .' ) VALUES ('. $values .')';
        $query = $this->getDb()->prepare( $sql );
        $query->execute( $data );
    }
    
    //modifie une/des données en BDD
    protected function updateOne($table, $newData, $condition, $uniq )
    {
        $sets = '';
        foreach( $newData as $key => $value )
        {
            $sets .= "$key = :$key,";
        }
        $sets = substr( $sets, 0, -1 );
        
        $sql = "UPDATE $table SET $sets WHERE $condition = :$condition";
        $query = $this->getDb()->prepare( $sql );
        
        foreach( $newData as $key => $value )
        {
            $query->bindvalue( ":$key", $value );
        }
        
        $query->bindvalue( ":$condition", $uniq );
        $query->execute();
    }

    //supprime une donnée de la BDD
    protected function deleteOne( $table, $column, $value )
    {
        $sql = 'DELETE FROM ' . $table . ' WHERE ' . $column . ' = ?';
        $query = $this->getDb()->prepare( $sql );
        $query->execute([$value]);
    }
    
    //sauvegarde de la BDD
    protected function backupTables($tables = '*')
    {  
        $today = date('dmY_His');
        $output = "-- database backup - ".date('Y-m-d H:i:s').PHP_EOL;
        $output .= "SET NAMES utf8;".PHP_EOL;
        $output .= "SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';".PHP_EOL;
        $output .= "SET foreign_key_checks = 0;".PHP_EOL;
        $output .= "SET AUTOCOMMIT = 0;".PHP_EOL;
        $output .= "START TRANSACTION;".PHP_EOL;
        if($tables == '*') {
          $tables = [];
          $sql = 'SHOW TABLES';
          $query = $this->getDb()->prepare( $sql );
          $query->execute();
          while($row = $query->fetch(\PDO::FETCH_NUM)) {
            $tables[] = $row[0];
          }
          $query->closeCursor();
        }
        else {
          $tables = is_array($tables) ? $tables : explode(',',$tables);
        }
        
        foreach($tables as $table) {
            
          $sql = "SELECT * FROM `$table`";
          $query = $this->getDb()->prepare( $sql );
          $query->execute();
          $output .= "DROP TABLE IF EXISTS `$table`;".PHP_EOL;
          
          $sql2 = "SHOW CREATE TABLE `$table`";
          $query2 = $this->getDb()->prepare( $sql );
          $query2->execute();
          $row2 = $query2->fetch(\PDO::FETCH_NUM);
        
            while($row = $query->fetch(\PDO::FETCH_NUM)) {
              $output .= "INSERT INTO `$table` VALUES(";
              for($j=0; $j<count($row); $j++) {
                $row[$j] = addslashes($row[$j]);
                $row[$j] = str_replace("\n","\\n",$row[$j]);
                if (isset($row[$j]))
                  $output .= "'".$row[$j]."'";
                else $output .= "''";
                if ($j<(count($row)-1))
                 $output .= ',';
              }
              $output .= ");".PHP_EOL;
            }
          }
          $output .= PHP_EOL.PHP_EOL;
        
        $output .= "COMMIT;";
        
        $filename = 'backup_DB_'.$today.'.sql';
        $this->writeAndDownloadDBBackup($filename,$output);
    }

    //ecriture et telechargement de la BDD en format .sql
    private function writeAndDownloadDBBackup($filenamename,$content)
    {
        $f=fopen($filenamename,"w+"); 
            
        fwrite($f, pack("CCC",0xef,0xbb,0xbf)); 
        fwrite($f,$content); 
        fclose($f);
        
        header('Content-Type: application/octet-stream');
        header('Content-disposition: attachment; filename='.$filenamename);
        header('Content-Length: '.filesize($filenamename));
        readfile($filenamename);
        unlink($filenamename);
    }
}
?>
