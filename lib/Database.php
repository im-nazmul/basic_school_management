<?php
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'db_student');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    class Database {
        private $pdo;
        public function __construct(){
            if(!isset($this->pdo)) {
                try{
                    $link = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS);
                    $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $link->exec('SET CHARACTER SET utf8');
                    $this->pdo = $link;
                } catch(PDOException $e){
                    die('Field to connect with Database'.$e->getMessage());
                }
            }
        }
        
        //select data
        public function select($table, $data = array()){
            $sql  = 'SELECT ';
            $sql .= array_key_exists('selet', $data)?$data['select']:'*';
            $sql .= ' FROM '.$table;
            if(array_key_exists('where', $data)){
                $sql .= ' WHERE ';
                $i = 0;
                foreach($data['where'] as $key => $value){
                    $add = ($i > 0)?' AND ':'';
                    $sql .= $add.$key.' =:key';
                    $i++;
                }
            }
            if(array_key_exists('start', $data) && array_key_exists('limit', $data)){
                $sql .= ' LIMIT '.$data['start'].','.$data['limit'];
            }elseif(!array_key_exists('start', $data) && array_key_exists('limit', $data)){
                $sql .= ' LIMIT '.$data['limit'];
            }
            
            $query = $this->pdo->prepare($sql);
            
            if(array_key_exists('where', $data)){
                foreach($data['where'] as $key => $value){
                    $query->bindValue(':key', $value);
                }
            }
            $query->execute();
            
            if(array_key_exists('return_type', $data)){
                switch ($data['return_type']){
                    case 'count':
                        $value = $query->rowCount();
                    break;
                    case 'single':
                        $value = $query->fetch(PDO::FETCH_ASSOC);
                    break;
                    default:
                        $value = '';
                    break;
                }
            }else{
                if($query->rowCount() > 0){
                    $value = $query->fetchAll();
                }
            }
            return !empty($value)?$value:false;
        }
        
        //insert data
        public function insert($table, $data){
            if(!empty($data) && is_array($data)){
                $keys = '';
                $values = '';
                
                $key = implode(',', array_keys($data));
                $values = ":".implode(', :', array_keys($data));
                $sql = "INSERT INTO ".$table." (".$key.") VALUES (".$values.")";
                $query = $this->pdo->prepare($sql);
                
                foreach($data as $key => $value){
                    $query->bindValue(":$key", $value);
                }
                $insertData = $query->execute();
                if($insertData){
                    $lastId = $this->pdo->lastInsertId();
                    return $lastId;
                }else{
                    return false;
                }
            }
        }
        
        //update
        public function update($table, $data, $cond){
            if(!empty($data) && is_array($data)){
                $keyValue = '';
                $whereCond = '';
                
                $i = 0;
                foreach($data as $key => $val){
                    $add = ($i > 0)?" , ":"";
                    $keyValue .= $add.$key."=:".$key;
                    $i++;
                }
                
                if(!empty($cond) && is_array($cond)){
                    $whereCond .= ' WHERE ';
                    $i = 0;
                    foreach($cond as $key => $val){
                        $add = ($i > 0)?" AND ":"";
                        $whereCond .= $add.$key."=:".$key;
                        $i++;
                    }
                }
                $sql = "UPDATE ".$table." SET ".$keyValue.$whereCond;
                
                $query = $this->pdo->prepare($sql);
                
                foreach($data as $key => $val){
                    $query->bindValue(":".$key, $val);
                }
                foreach($cond as $key => $val){
                    $query->bindValue(":".$key, $val);
                }
                $update = $query->execute();
                return $update?$query->rowCount():false;
            } else{
                return false;
            }
        }
        
        //delete
        public function delete($table, $cond){
            if(!empty($cond) && is_array($cond)){
                $whereCond .= ' WHERE ';
                $i = 0;
                foreach($cond as $key => $val){
                    $add = ($i > 0)?" AND ":"";
                    $whereCond .= $add.$key." = ".$val;
                    //$whereCond .= $add.$key."=:".$key;
                    $i++;
                }
            }
                  
            $sql = "DELETE FROM ".$table.$whereCond;
            $delete = $this->pdo->exec($sql);
            return $delete?true:false;
            
            /*
            $query = $this->pdo->prepare($sql);
            
            foreach($cond as $key => $val){
                $query->bindValue(":".$key, $val);
            }
            $delete = $query->execute();
            return $delete?true:false;
            */
        }
    }
            
?>