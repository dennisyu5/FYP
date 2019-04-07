<?php
abstract class AI {
	
	public function __construct() {
		$this->id = 2;
	}
	
	public function getID() {
		return $this->id;
	}
	
	public function setID($id) {
		$this->id = $id;
	}
	
	public abstract function move($board);
}
?>