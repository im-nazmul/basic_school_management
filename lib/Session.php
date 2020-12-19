<?php
    class Session {
        // Initialize Session
        public static function init(){
            // Comparing version
            if(version_compare(phpversion(), '5.4.0', '<')){
                if(session_id() == ''){
                    session_start();
                }
            } else{
                if(session_status() == PHP_SESSION_NONE){
                    session_start();
                }
            }
        }
        
        // Initialize Session
        public static function set($key, $value){
           $_SESSION[$key] = $value;
        }
        
        // Get session id
        public static function get($key){
           if(isset($_SESSION[$key])){
               return $_SESSION[$key];
           }else{
               return false;
           }
        }
        
        // Unset Session
        public static function unset(){
            session_unset();
        }
        
        // Checking Session Existence 
        public static function checkSession(){
           if(self::get('login') == false){
                self::destroy();
                header("Location: login.php");
           }
        }
        
        // Check pre-login
        public static function checkLogin(){
           if(self::get('login') == true){
                header("Location: index.php");
           }
        }
        
       // Destroy Session
        public static function destroy(){
            session_destroy();
            session_unset();
            header('Location: login.php');
        }
    }
?>
