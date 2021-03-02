<?php
class ListUser extends Model 
{
    //$created_on
    var $list_id;
    var $profile_id;
    var $sender_id;
    var $is_owner;
    var $is_bookmarked;
    var $accepted;
    var $accepted_on;

    public function create() {
		$SQL = 'INSERT INTO listuser(list_id, profile_id, sender_id, is_owner, is_bookmarked, accepted) VALUE(:list_id, :profile_id, :sender_id, 0, 0, 0)';
		$stmt = self::$_connection->prepare($SQL);
		$stmt->execute(['list_id' => $this->list_id, 'profile_id' => $this->profile_id, 'sender_id' => $this->sender_id]);
		return $stmt->rowCount();	
    } 	       
    
	public function makeOwner() {
        $SQL = 'UPDATE listuser SET is_owner = 1 WHERE list_id = :list_id AND profile_id = :profile_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['list_id' => $this->list_id, 'profile_id' => $this->profile_id]);
        return $stmt->rowCount();	 
    }    

    public function removeOwner() {
        $SQL = 'UPDATE listuser SET is_owner = 0 WHERE list_id = :list_id AND profile_id = :profile_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['list_id' => $this->list_id, 'profile_id' => $this->profile_id]);
        return $stmt->rowCount();	 
    }  
    
	public function setAccepted() {
        date_default_timezone_set("Canada/Eastern");
        $SQL = 'UPDATE listuser SET accepted = 1, accepted_on = :today WHERE list_id = :list_id AND profile_id = :profile_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['today' => date("Y-m-d H:i:s"), 'list_id' => $this->list_id, 'profile_id' => $this->profile_id]);
        return $stmt->rowCount();	 
    } 

    public function setBookmark() {
        $SQL = 'UPDATE listuser SET is_bookmarked = 1 WHERE list_id = :list_id AND profile_id = :profile_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['list_id' => $this->list_id, 'profile_id' => $this->profile_id]);
        return $stmt->rowCount();	 
    } 

    public function unsetBookmark() {
        $SQL = 'UPDATE listuser SET is_bookmarked = 0 WHERE list_id = :list_id AND profile_id = :profile_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['list_id' => $this->list_id, 'profile_id' => $this->profile_id]);
        return $stmt->rowCount();	 
    } 

    public static function findAllLists($profile_id) {
        $SQL = 'SELECT * FROM listuser WHERE profile_id = :profile_id AND accepted = 1';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['profile_id' => $profile_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'ListUser');
        return $stmt->fetchAll();
    }    

    public static function findAllProfiles($list_id) {
        $SQL = 'SELECT * FROM listuser WHERE list_id = :list_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['list_id' => $list_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'ListUser');
        return $stmt->fetchAll();
    }  

    public static function findAllActiveProfiles($list_id) {
        $SQL = 'SELECT * FROM listuser WHERE list_id = :list_id AND accepted = 1';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['list_id' => $list_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'ListUser');
        return $stmt->fetchAll();
    }  

    public static function findAllBookmarked($profile_id) {
        $SQL = 'SELECT * FROM listuser WHERE profile_id = :profile_id AND is_bookmarked = 1';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['profile_id' => $profile_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'ListUser');
        return $stmt->fetchAll();
    }     

    public static function findAllOwners($list_id) {
        $SQL = 'SELECT * FROM listuser WHERE list_id = :list_id AND is_owner = 1';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['list_id' => $list_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'ListUser');
        return $stmt->fetchAll();
    }   

    public static function findAllPending($profile_id) {
        $SQL = 'SELECT * FROM listuser WHERE profile_id = :profile_id AND accepted = 0';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['profile_id' => $profile_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'ListUser');
        return $stmt->fetchAll();
    }  

	public function find($list_id, $profile_id) {
		$SQL = 'SELECT * FROM listuser WHERE list_id = :list_id AND profile_id = :profile_id';
		$stmt = self::$_connection->prepare($SQL);
		$stmt->execute(['list_id' => $list_id, 'profile_id' => $profile_id]);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'ListUser');
		return $stmt->fetch();
    }   
    
    public function delete() {
		$SQL = 'DELETE FROM listuser WHERE list_id = :list_id AND profile_id = :profile_id';
		$stmt = self::$_connection->prepare($SQL);
		$stmt->execute(['list_id' => $this->list_id, 'profile_id' => $this->profile_id]);
		return $stmt->rowCount();	        
    }

    public static function findAllListsOwned($profile_id) {
		$SQL = 'SELECT * FROM listuser WHERE profile_id = :profile_id AND is_owner = 1';
		$stmt = self::$_connection->prepare($SQL);
		$stmt->execute(['profile_id' => $profile_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'ListUser');
        return $stmt->fetchAll();	        
    }

    public static function listuserDeleteAllForProfile($profile_id) {
		$SQL = 'DELETE FROM listuser WHERE profile_id = :profile_id';
		$stmt = self::$_connection->prepare($SQL);
		$stmt->execute(['profile_id' => $profile_id]);
		return $stmt->rowCount();	        
    }

    public static function listuserDeleteAllForList($list_id) {
		$SQL = 'DELETE FROM listuser WHERE list_id = :list_id';
		$stmt = self::$_connection->prepare($SQL);
		$stmt->execute(['list_id' => $list_id]);
		return $stmt->rowCount();	        
    }
}
?>