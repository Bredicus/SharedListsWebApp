<?php
class ListItem extends Model 
{
    //$item_id, $created_on
    var $list_id;
    var $item_data;
    var $priority;
    var $is_complete;
    var $created_by;
    var $deadline;

    public function find($item_id) {
        $SQL = 'SELECT * FROM listitem WHERE item_id = :item_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['item_id' => $item_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'ListItem');
        return $stmt->fetch();
    }   	    
    
    public static function findAll($list_id) {
        $SQL = 'SELECT * FROM listitem WHERE list_id = :list_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['list_id' => $list_id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'ListItem');
        return $stmt->fetchAll();
    }    
    
    public function create() {
        $SQL = 'INSERT INTO listitem (list_id, item_data, priority, is_complete, created_by, deadline) 
                VALUE (:list_id, :item_data, :priority, 0, :created_by, :deadline)';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['list_id' => $this->list_id, 'item_data' => $this->item_data, 'priority' => $this->priority, 
                'created_by' => $this->created_by, 'deadline' => $this->deadline]);
        return $stmt->rowCount();	
    } 
        
    public function setComplete() {
        $SQL = 'UPDATE listitem SET is_complete = 1 WHERE item_id = :item_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['item_id' => $this->item_id]);
        return $stmt->rowCount();	 
    } 
                
    public function setIncomplete() {
        $SQL = 'UPDATE listitem SET is_complete = 0 WHERE item_id = :item_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['item_id' => $this->item_id]);
        return $stmt->rowCount();	 
    }    
    
    public function edit() {
        $SQL = 'UPDATE listitem SET item_data = :item_data, priority = :priority, deadline = :deadline WHERE item_id = :item_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['item_id' => $this->item_id, 'item_data' => $this->item_data, 'priority' => $this->priority, 'deadline' => $this->deadline]);
        return $stmt->rowCount();	 
    } 

    public function delete() {
        $SQL = 'DELETE FROM listitem WHERE item_id = :item_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['item_id' => $this->item_id]);
        return $stmt->rowCount();	        
    }    

    public static function listitemDeleteAllForList($list_id) {
		$SQL = 'DELETE FROM listitem WHERE list_id = :list_id';
		$stmt = self::$_connection->prepare($SQL);
		$stmt->execute(['list_id' => $list_id]);
		return $stmt->rowCount();	        
    }    
}
?>