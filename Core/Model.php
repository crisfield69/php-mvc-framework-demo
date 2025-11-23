<?php

namespace Core;

use Core\Database;

class Model
{
    protected $database;
    protected $table;
    
    public function __construct()
    {
        $this->database = Database::getInstance();
    }

    public function query($query, $single=false)
    {
        return $this->database->query($query, $single);
    }  
    
    public function pureQuery($query, $single=false)
    {
        return $this->database->query($query, $single, true);
    }  
 
    public function select($conditions = null, $single = false) 
    {        
        if($conditions !== null) $conditions = ' WHERE ' . $conditions; 
        return $this->database->query('SELECT * FROM '. $this->table . $conditions, $single);
    }

    public function insert($datas, $conditions=null) 
    {
        $formatDatas = $this->formatDatas($datas);
        if($conditions !== null) $conditions = ' WHERE ' . $conditions;
        return $this->database->query('INSERT INTO '. $this->table .' SET '. $formatDatas . $conditions);
    }

    public function update($datas, $conditions=null)
    {
        $formatDatas =  $this->formatDatas($datas);
        if($conditions !== null) $conditions = ' WHERE ' . $conditions;
        return $this->database->query('UPDATE '. $this->table .' SET '. $formatDatas . $conditions);        
    }

    public function delete($conditions=null)
    {
        if($conditions !== null) $conditions = ' WHERE ' . $conditions;
        return $this->database->query('DELETE FROM '. $this->table . $conditions );
    }

    public function getAll($field=null) 
    {
        if($field === null) {
            $query = 'SELECT * FROM ' . $this->table;
            return $this->database->query($query);
        }
        else {
            $query = 'SELECT ' . $field . ' FROM ' . $this->table; 
            $results = $this->database->query($query);
            $all = [];
            foreach($results as $result) { 
                $all[] = $result->$field;
            }
            return $all;
        }
    }

    public function getAllOrderBy(string $columnName, string $direction=' ASC')
    {
        return $this->query('SELECT * FROM '. $this->table .' ORDER BY '. $columnName . $direction);
    } 

    public function getSingle($id)
    {
        return $this->database->query('SELECT * FROM '. $this->table .' WHERE id='.intval($id), true);
    }  
        
    public function length()
    {        
        return mysqli_num_rows($this->database->result);
    }

    public function lastId()
    {
        $connexion = $this->database->getConnexion();
        return mysqli_insert_id($connexion);
    }

    public function escape($string) 
    {
        $connexion = $this->database->getConnexion();
        return '"'.mysqli_real_escape_string($connexion, $string).'"';
    }

    private function formatDatas($datas)
    {
        $formatDatas = '';

        foreach($datas as $fieldname => $results) {
            
            $value  =   trim($results[0]);
            $type   =   $results[1];

            $formatDatas .= $fieldname . '=';

            switch($type) {

                case 's':
                    $formatDatas .= $this->escape($value) . ', ';
                break;

                case 'i':
                    $formatDatas .= intval($value) . ', ';
                break;

                case 'f':
                    $formatDatas .= floatval($value) . ', ';
                break;

                default:
                break;
            }
        }
        return substr($formatDatas, 0, -2);
    }

    
}