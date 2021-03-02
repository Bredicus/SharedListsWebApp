<?php
class Model
{	
    protected static $_connection = null;
    
    public function __construct() {
        if (self::$_connection == null) {
			$host = 'localhost';
			$dbname = 'database';
			$username = 'username';
			$password = 'password';			
			
            self::$_connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        }
    }
}
?>