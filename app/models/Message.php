<?php
class Message extends Model 
{
    //$message_id, $sent_on
    var $sender;
	var $receiver;    
    var $message_text;
    var $is_read;
    var $read_on;
	
	/**
	 * Admin profile must be made before this is functional
	 * Admin profile id hardcoded as 2, modify as needed
	 */
    public function messageAdmin() { 
		$SQL = 'INSERT INTO message(receiver, message_text, is_read) VALUE(2,:message_text , 0)';
		$stmt = self::$_connection->prepare($SQL);
		$stmt->execute(['message_text' => $this->message_text]);
		return $stmt->rowCount();	
	} 

    public function newMessage() { 
		$SQL = 'INSERT INTO message(sender, receiver, message_text, is_read) VALUE(:sender, :receiver, :message_text , 0)';
		$stmt = self::$_connection->prepare($SQL);
		$stmt->execute(['sender' => $this->sender, 'receiver' => $this->receiver, 'message_text' => $this->message_text]);
		return $stmt->rowCount();	
	} 	

    public static function getAllMessages($profile_id) {
        $SQL = 'SELECT * FROM message WHERE sender = :id OR receiver = :id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['id' => $profile_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Message');
        return $stmt->fetchAll();
    }

    public static function getConversation($profile1, $profile2) {
        $SQL = 'SELECT * FROM message WHERE sender = :id1 AND receiver = :id2 OR sender = :id2 AND receiver = :id1';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['id1' => $profile1, 'id2'=> $profile2]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Message');
        return $stmt->fetchAll();
    }

	public function getMessage($message_id) {
        $SQL = 'SELECT * FROM message WHERE message_id = :message_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['message_id' => $message_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Message');
        return $stmt->fetch();
    } 

	public function setMessageRead($message_id) { 
        date_default_timezone_set("Canada/Eastern");
        $SQL = 'UPDATE message SET is_read = 1, read_on = :read_on WHERE message_id = :message_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['read_on' => date("Y-m-d H:i:s"), 'message_id' => $message_id]);
        return $stmt->rowCount();	
    } 	
    
    public function isRead() {
        return $this->is_read == true ? 'true' : 'false';
    }    

    public function delete() {
		$SQL = 'DELETE FROM message WHERE message_id = :message_id';
		$stmt = self::$_connection->prepare($SQL);
		$stmt->execute(['message_id' => $this->message_id]);
		return $stmt->rowCount();	        
    }

    public static function getNewMessage($receiver) {
        $SQL = 'SELECT * FROM message WHERE receiver = :receiver AND is_read = 0';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['receiver' => $receiver]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Message');
        return $stmt->fetchAll();
    }

    public static function messageDeleteAllForProfile($profile_id) {
		$SQL = 'DELETE FROM message WHERE sender = :profile_id OR receiver = :profile_id';
		$stmt = self::$_connection->prepare($SQL);
		$stmt->execute(['profile_id' => $profile_id]);
		return $stmt->rowCount();	        
    }     
}
?>