<?php
    include_once 'Session.php';
    include 'Database.php';
    class User {
        private $db;
        function __construct(){
            $this->db = new Database();
        }
        
        function userRegister($data){
            $name = $data['name'];
            $usrName = $data['username'];
            $email = $data['email'];
            $check_mail = $this->emailCheck($email);
            
            $pass = $data['password'];
            
            if($name == '' || $usrName == '' || $email == '' || $pass == '') {
                $mgs = '<div class="alert alert-danger"><strong>Error ! </strong> Field must not be Empty</div>';
                return $mgs;
            }
            
            if(strlen($usrName) < 3 || strlen($usrName) > 15){ 
                $mgs = '<div class="alert alert-danger"><strong>Error ! </strong> User name is too short. You can not use Username less then 3 character or more then 15 character.</div>';
                return $mgs;
            } elseif(preg_match('/[^a-z0-9_-]+/i', $usrName)){
                 $mgs = '<div class="alert alert-danger"><strong>Error ! </strong> Invalid keyword used. Supported contain [\'a-z\', \'0-9\', \'_\', \'-\']</div>';
                return $mgs;
            }
            
            if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
                $mgs = '<div class="alert alert-danger"><strong>Error ! </strong> Email Address is not valid.</div>';
                return $mgs;
            }
            
            if($check_mail == true) {
                $mgs = '<div class="alert alert-danger"><strong>Error ! </strong> Email Address allready exist!</div>';
                return $mgs;
            }
            
            if(strlen($pass) < 6){ 
                $mgs = '<div class="alert alert-danger"><strong>Error ! </strong> User Password is too short. You can not use Password less then 6 character.</div>';
                return $mgs;
            }
            $password = md5($pass);
            
            $register = 'INSERT INTO tbl_user(name, username, email, password) VALUES(:name, :username, :email, :password)';
            $query = $this->db->pdo->prepare($register);
            $query->bindValue(':name', $name);
            $query->bindValue(':username', $usrName);
            $query->bindValue(':email', $email);
            $query->bindValue(':password', $password);
            $result = $query->execute();
            if($result){
                $mgs = '<div class="alert alert-success"><strong>Success ! </strong> You have successfully register!</div>';
                return $mgs;
            }else{
                $mgs = '<div class="alert alert-danger"><strong>Error ! </strong> Some thing went wrong!</div>';
                return $mgs;
            }
        }
        
        function emailCheck($email){
            $sql = 'SELECT email FROM tbl_user WHERE email = :email';
            $query = $this->db->pdo->prepare($sql);
            $query->bindValue(':email', $email);
            $query->execute();
            if($query->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
            
            if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
                $mgs = '<div class="alert alert-danger"><strong>Error ! </strong> Email Address is not valid.</div>';
                return $mgs;
            }
            
            if($check_mail == true) {
                $mgs = '<div class="alert alert-danger"><strong>Error ! </strong> Email Address allready exist!</div>';
                return $mgs;
            }
        }
        
        function userLogin($data){
            $email = $data['email'];
            $check_mail = $this->emailCheck($email);
            $password = md5($data['password']);
            
            if($email == '' || $password == '') {
                $mgs = '<div class="alert alert-danger"><strong>Error ! </strong> Field must not be Empty</div>';
                return $mgs;
            }
            
            if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
                $mgs = '<div class="alert alert-danger"><strong>Error ! </strong> Email Address is not valid.</div>';
                return $mgs;
            }
            
            if($check_mail == false) {
                $mgs = '<div class="alert alert-danger"><strong>Error ! </strong> Email Address Not exist!</div>';
                return $mgs;
            }
            
            $result = $this->getLoginUser($email, $password);
            if($result){
                Session::init();
                Session::set('login', true);
                Session::set('id', $result->id);
                Session::set('name', $result->name);
                Session::set('username', $result->userName);
                Session::set('loginMgs', '<div class="alert alert-success"><strong>Success ! </strong> You are logged in!</div>');
                header('Location: index.php');
            } else{
                $mgs = '<div class="alert alert-danger"><strong>Error ! </strong> Data not found!</div>';
                return $mgs; 
            }
        }
        
        function getLoginUser($email, $password){
            $login = 'SELECT * FROM tbl_user WHERE email = :email AND password = :password LIMIT 1';
            $query = $this->db->pdo->prepare($login);
            $query->bindValue(':email', $email);
            $query->bindValue(':password', $password);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_OBJ);
            return $result;
        }
        
        function getUserData(){
            $sql = 'SELECT * FROM tbl_user ORDER BY id DESC';
            $query = $this->db->pdo->prepare($sql);
            $query->execute();
            $result = $query->fetchAll();
            return $result;
        }
        
        function getUserById($id){
            $sql = 'SELECT * FROM tbl_user WHERE id = '.$id;
            $query = $this->db->pdo->prepare($sql);
            $query->execute();
            $result = $query->fetchAll();
            return $result;
        }
        
        function updateUserData($id, $data){
            $name = $data['name'];
            $usrName = $data['username'];
            $email = $data['email'];
            $check_mail = $this->emailCheck($email);
            
            if($name == '' || $usrName == '' || $email == '') {
                $mgs = '<div class="alert alert-danger"><strong>Error ! </strong> Field must not be Empty</div>';
                return $mgs;
            }
            
            if(strlen($usrName) < 3 || strlen($usrName) > 15){ 
                $mgs = '<div class="alert alert-danger"><strong>Error ! </strong> User name is too short. You can not use Username less then 3 character or more then 15 character.</div>';
                return $mgs;
            } elseif(preg_match('/[^a-z0-9_-]+/i', $usrName)){
                 $mgs = '<div class="alert alert-danger"><strong>Error ! </strong> Invalid keyword used. Supported contain [\'a-z\', \'0-9\', \'_\', \'-\']</div>';
                return $mgs;
            }
            
            $update = 'UPDATE tbl_user SET 
                name = :name,
                username = :username,
                email = :email
                WHERE id = :id;
            ';
            $query = $this->db->pdo->prepare($update);
            $query->bindValue(':name', $name);
            $query->bindValue(':username', $usrName);
            $query->bindValue(':email', $email);
            $query->bindValue(':id', $id);
            $result = $query->execute();
            if($result){
                $mgs = '<div class="alert alert-success"><strong>Success ! </strong> Your data successfully updated!</div>';
                return $mgs;
            }else{
                $mgs = '<div class="alert alert-danger"><strong>Error ! </strong> Some thing went wrong!</div>';
                return $mgs;
            }
        }
        
        function checkPassword($id, $old_pass){
            $old_password = md5($old_pass);
            $sql = 'SELECT password FROM tbl_user WHERE id = :id AND password = :password';
            $query = $this->db->pdo->prepare($sql);
            $query->bindValue(':id', $id);
            $query->bindValue(':password', $old_password);
            $query->execute();
            if($query->rowCount() > 0) {
                return true;
            } else {
                return false;
            }
        }
        
        function updatePassword($id, $data){
            $old_pass = $data['old_pass'];
            $new_pass = $data['password'];
            $check_pass = $this->checkPassword($id, $old_pass);
            
            if($old_pass == '' || $new_pass == '') {
                $mgs = '<div class="alert alert-danger"><strong>Error ! </strong> Field must not be Empty</div>';
                return $mgs;
            }
            
            if($check_pass == false){
                 $mgs = '<div class="alert alert-danger"><strong>Error ! </strong> Password didn\'t match!</div>';
                return $mgs;
            }
            
            if(strlen($new_pass) < 6){ 
                $mgs = '<div class="alert alert-danger"><strong>Error ! </strong> User Password is too short. You can not use Password less then 6 character.</div>';
                return $mgs;
            }
            
            $password = md5($new_pass);
            
            $update = 'UPDATE tbl_user SET 
                password = :password
                WHERE id = :id;
            ';
            $query = $this->db->pdo->prepare($update);
            $query->bindValue(':password', $password);
            $query->bindValue(':id', $id);
            $result = $query->execute();
            if($result){
                $mgs = '<div class="alert alert-success"><strong>Success ! </strong>Password successfully updated!</div>';
                return $mgs;
            }else{
                $mgs = '<div class="alert alert-danger"><strong>Error ! </strong> Password updated field!</div>';
                return $mgs;
            }
            
        }
    }
?>
