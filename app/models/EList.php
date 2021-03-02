<?php
class EList extends Model 
{
	//$list_id, $created_on
	var $list_name;
	var $is_complete;
	var $created_by;
	var $deadline;

    public function create($profile_id) {
		$SQL = 'INSERT INTO elist(list_name, is_complete, created_by, deadline) VALUE(:list_name, 0, :profile_id, :deadline)';
		$stmt = self::$_connection->prepare($SQL);
		$stmt->execute(['list_name' => $this->list_name, 'profile_id' => $profile_id, 'deadline' => $this->deadline]);
		return self::$_connection->lastInsertId();	
	} 

	public function find($list_id) {
		$SQL = 'SELECT * FROM elist WHERE list_id = :list_id';
		$stmt = self::$_connection->prepare($SQL);
		$stmt->execute(['list_id' => $list_id]);
		$stmt->setFetchMode(PDO::FETCH_CLASS, 'EList');
		return $stmt->fetch();
	}   	       
	
	public function delete() {
		$SQL = 'DELETE FROM elist WHERE list_id = :list_id';
		$stmt = self::$_connection->prepare($SQL);
		$stmt->execute(['list_id' => $this->list_id]);
		return $stmt->rowCount();	        
	}
	
	public function setName() {
        $SQL = 'UPDATE elist SET list_name = :list_name WHERE list_id = :list_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['list_id' => $this->list_id, 'list_name' => $this->list_name]);
        return $stmt->rowCount();	 
	} 
	
	public function setDeadline() {
        $SQL = 'UPDATE elist SET deadline = :deadline WHERE list_id = :list_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['list_id' => $this->list_id, 'deadline' => $this->deadline]);
        return $stmt->rowCount();	 
	} 

	public function setComplete() {
        $SQL = 'UPDATE elist SET is_complete = 1 WHERE list_id = :list_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['list_id' => $this->list_id]);
        return $stmt->rowCount();	 
	} 

	public function setIncomplete() {
        $SQL = 'UPDATE elist SET is_complete = 0 WHERE list_id = :list_id';
        $stmt = self::$_connection->prepare($SQL);
        $stmt->execute(['list_id' => $this->list_id]);
        return $stmt->rowCount();	 
	} 
}
?>