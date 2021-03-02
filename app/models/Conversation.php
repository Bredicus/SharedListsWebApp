<?php
class Conversation extends Model 
{
    //$message_id, $sent_on
    var $list_id;
    var $message_text;
    var $sender;

    public function find($message_id) {
        $SQL = 'SELECT * FROM conversation WHERE message_id = :message_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['message_id' => $message_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Conversation');
        return $stmt->fetch();
    }   	    
    
    public static function findAll($list_id) {
        $SQL = 'SELECT * FROM conversation WHERE list_id = :list_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['list_id' => $list_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Conversation');
        return $stmt->fetchAll();
    }    
    
    public function create() {
        $SQL = 'INSERT INTO conversation (list_id, message_text, sender) VALUE (:list_id, :message_text, :sender)';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['list_id' => $this->list_id, 'message_text' => $this->message_text, 'sender' => $this->sender]);
        return $stmt->rowCount();	
    } 

    public function delete() {
        $SQL = 'DELETE FROM conversation WHERE message_id = :message_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['message_id' => $this->message_id]);
        return $stmt->rowCount();	        
    }    

    public static function conversationDeleteAllForList($list_id) {
		$SQL = 'DELETE FROM conversation WHERE list_id = :list_id';
		$stmt = self::$_connection->prepare($SQL);
		$stmt->execute(['list_id' => $list_id]);
		return $stmt->rowCount();	        
    }
}
?>