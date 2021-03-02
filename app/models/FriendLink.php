<?php
class FriendLink extends Model 
{
    //sent_on
    var $sender;
    var $receiver; 
    var $is_read;
    var $approved;
    var $approved_on;   
	
    public function create() { 
		$SQL = 'INSERT INTO friendlink(sender, receiver, is_read, approved) VALUE(:sender, :receiver, 0, 0)';
		$stmt = self::$_connection->prepare($SQL);
		$stmt->execute(['sender' => $this->sender, 'receiver' => $this->receiver]);
		return $stmt->rowCount();	
    } 
    
    public function delete($user1, $user2) {
		$SQL = 'DELETE FROM friendlink WHERE sender = :user1 AND receiver = :user2 OR sender = :user2 AND receiver = :user1';
		$stmt = self::$_connection->prepare($SQL);
		$stmt->execute(['user1' => $user1, 'user2' => $user2]);
		return $stmt->rowCount();	        
    }

    public function approve() {
        date_default_timezone_set("Canada/Eastern");
        $SQL = 'UPDATE friendlink SET approved_on = :approved_on, approved = 1 WHERE sender = :sender AND receiver = :receiver';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['approved_on' => date("Y-m-d H:i:s"), 'sender' => $this->sender, 'receiver' => $this->receiver]);
        return $stmt->rowCount();	        
    }

    public function read() {
        $SQL = 'UPDATE friendlink SET is_read = 1 WHERE sender = :sender AND receiver = :receiver';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['sender' => $this->sender, 'receiver' => $this->receiver]);
        return $stmt->rowCount();	        
    }

    public static function findLink($currentUserId, $otherUserId) {
        $SQL = 'SELECT * FROM friendlink WHERE sender = :user1 AND receiver = :user2 OR sender = :user2 AND receiver = :user1';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['user1' => $currentUserId, 'user2' => $otherUserId]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'FriendLink');
        return $stmt->fetchAll();
    }

    public static function getLink($sender, $receiver) {
        $SQL = 'SELECT * FROM friendlink WHERE sender = :sender AND receiver = :receiver';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['sender' => $sender, 'receiver' => $receiver]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'FriendLink');
        return $stmt->fetch();
    }    

    public static function getFriends($id) {
        $SQL = 'SELECT * FROM friendlink WHERE sender = :id AND approved = true OR receiver = :id AND approved = true';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'FriendLink');
        return $stmt->fetchAll();
    }

    public static function getSentPending($sender) {
        $SQL = 'SELECT * FROM friendlink WHERE sender = :sender AND approved = false';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['sender' => $sender]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'FriendLink');
        return $stmt->fetchAll();
    }

    public static function getreceivedPending($receiver) {
        $SQL = 'SELECT * FROM friendlink WHERE receiver = :receiver AND approved = false';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['receiver' => $receiver]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'FriendLink');
        return $stmt->fetchAll();
    }

    public static function getNewreceivedPending($receiver) {
        $SQL = 'SELECT * FROM friendlink WHERE receiver = :receiver AND approved = false AND is_read = 0';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['receiver' => $receiver]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'FriendLink');
        return $stmt->fetchAll();
    }

    public static function friendlinkDeleteAllForProfile($profile_id) {
		$SQL = 'DELETE FROM friendlink WHERE sender = :profile_id OR receiver = :profile_id';
		$stmt = self::$_connection->prepare($SQL);
		$stmt->execute(['profile_id' => $profile_id]);
		return $stmt->rowCount();	        
    }    
}
?>