<?php
class Profile extends Model 
{
    //$profile_id, $created_on
    var $user_id;
    var $first_name;
    var $last_name;
    var $gender;
    var $privacy_flag;
    var $phone;
    var $email;
    var $picture_path;
    var $last_modified;
    var $last_login;
    var $recent_login;
    
	public function findProfile($profile_id) {
        $SQL = 'SELECT * FROM profile WHERE profile_id = :profile_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['profile_id' => $profile_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Profile');
        return $stmt->fetch();
    } 

	public function findProfile_User_id($user_id) {
        $SQL = 'SELECT * FROM profile WHERE user_id = :user_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['user_id' => $user_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Profile');
        return $stmt->fetch();
    }        

    public function privacy() {
        return $this->privacy_flag == true ? 'private' : 'public';
    }

    public function loginUpdate() {
        $this->lastLogin();
        $this->recentLogin();
    }

    private function lastLogin() {
        date_default_timezone_set("Canada/Eastern");
        $SQL = 'UPDATE profile SET last_login = :recent_login WHERE profile_id = :profile_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['recent_login' => $this->recent_login, 'profile_id' => $this->profile_id]);
        return $stmt->rowCount();	        
    }

    private function recentLogin() {
        date_default_timezone_set("Canada/Eastern");
        $SQL = 'UPDATE profile SET recent_login = :recent_login WHERE profile_id = :profile_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['recent_login' => date("Y-m-d H:i:s"), 'profile_id' => $this->profile_id]);
        return $stmt->rowCount();	        
    }

    public function create() {
        $SQL = 'INSERT INTO profile(user_id, first_name, last_name, gender, privacy_flag, phone, email) 
                    VALUE(:user_id, :first_name, :last_name, :gender, :privacy_flag, :phone, :email)';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['user_id' => $_SESSION['user_id'],
                            'first_name' => $this->first_name,
                            'last_name' => $this->last_name,
                            'gender' => $this->gender,
                            'privacy_flag' => $this->privacy_flag,
                            'phone' => $this->phone,
                            'email' => $this->email]);
        return $stmt->rowCount();	
    }
    
    public function setPicturePath($path) {
        $SQL = 'UPDATE profile SET picture_path = :picture_path WHERE profile_id = :profile_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['picture_path' => $path, 'profile_id' => $this->profile_id]);
        return $stmt->rowCount();	 
    }

    public function editProfile() {
        date_default_timezone_set("Canada/Eastern");
        $SQL = 'UPDATE profile SET first_name = :first_name, 
                                    last_name = :last_name,
                                    gender = :gender,
                                    privacy_flag = :privacy_flag,
                                    phone = :phone,
                                    email = :email,
                                    last_modified = :today
                WHERE profile_id = :profile_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['first_name' => $this->first_name,
                        'last_name' => $this->last_name,
                        'gender' => $this->gender,
                        'privacy_flag' => $this->privacy_flag,
                        'phone' => $this->phone,
                        'email' => $this->email,
                        'today' => date("Y-m-d H:i:s"),
                        'profile_id' => $this->profile_id]);
        return $stmt->rowCount();	        
    }

    public function delete() {
		$SQL = 'DELETE FROM profile WHERE profile_id = :profile_id';
		$stmt = self::$_connection->prepare($SQL);
		$stmt->execute(['profile_id' => $this->profile_id]);
		return $stmt->rowCount();	        
    }

    public static function searchProfiles($search) {
        $SQL = 'SELECT * FROM profile WHERE first_name LIKE :search OR last_name LIKE :search';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['search' => '%' . $search . '%']);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Profile');
        return $stmt->fetchAll();
    }   
}
?>