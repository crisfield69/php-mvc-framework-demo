<?php
 
namespace Core;

class Database 
{
    protected $connexion = null;
    static $instance = null;    
    public $result;
    
    private function __construct()
    {        
    }
    
    static function getInstance()
    {
        if(self::$instance === null){
            self::$instance = new Database();
        }
        return self::$instance;
    }    
    
    public function getConnexion()
    {   
        if($this->connexion === null)  {
            $this->connexion = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        }   
        return $this->connexion;        
    }

    public function query(string $query, bool $single=false, $pure=false)
    {  
        $connexion = $this->getConnexion();
        mysqli_query($connexion, "SET NAMES 'utf8'");
        $result = mysqli_query($connexion, $query);        
        $this->result = $result;        
        if(
            strpos($query, 'UPDATE') === 0 ||
            strpos($query, 'INSERT') === 0 ||
            strpos($query, 'DELETE') === 0
        ) {            
            $result = ($result !== false)? true : false;
            return $result;
        }
        else{            
            if(strpos($query, 'SELECT') === 0) {
                if($pure === true) return $result;
                if($result === false) return false;                
                if(mysqli_num_rows($result) === 0) return [];                
                $results = [];
                while($object = mysqli_fetch_object($result)) {
                    $results[] = $object;
                }
                if($single === true){
                    if(count($results) >= 1) return $results[0];
                }
                return $results;
            }            
        }
    }    

}