<?php
class User extends Model 
{
	//$user_id, $register_date;
    var $username;
	var $password_hash;
	
	public function find($user_id) {
		$SQL = 'SELECT * FROM user WHERE user_id = :user_id';
		$stmt = self::$_connection->prepare($SQL);
		$stmt->execute(['user_id' => $user_id]);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
		return $stmt->fetch();
    }   	

	public function findUser($username) {
		$SQL = 'SELECT * FROM user WHERE username = :username';
		$stmt = self::$_connection->prepare($SQL);
		$stmt->execute(['username' => $username]);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
		return $stmt->fetch();
    }        
    
    public function create() {
		$SQL = 'INSERT INTO user(username, password_hash) VALUE(:username, :password_hash)';
		$stmt = self::$_connection->prepare($SQL);
		$stmt->execute(['username' => $this->username, 'password_hash' => $this->password_hash]);
		return $stmt->rowCount();	
	} 

	public function changePassword($new_password) {
        $SQL = 'UPDATE user SET password_hash = :password_hash WHERE user_id = :user_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['password_hash' => $new_password, 'user_id' => $this->user_id]);
        return $stmt->rowCount();	 
	}
}
?>