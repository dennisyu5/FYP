<?php
class Board {
	
	public function __construct() {
		$this->size = 8;
		$this->b = array();
		
		for ($i = 0; $i < $this->size+2; $i++) {
			for ($j = 0; $j < $this->size+2; $j++) {
				//set wall
				if ($i == 0 || $i == $this->size+1 || $j == 0 || $j == $this->size+1) {
					$this->b[$i][$j] = 3;
				//set empty
				} else {
					$this->b[$i][$j] = 0;
				}
			}
		}
	}
	
	public function setBoard($b) {
		foreach($b->black as $location) {
			list($y, $x) = str_split($location);
			$this->setLocation($x, $y, 1);
		}
		
		foreach($b->white as $location) {
			list($y, $x) = str_split($location);
			$this->setLocation($x, $y, 2);
		}
	}
	
	public function setLocation($x, $y, $chess) {
		$this->b[$x][$y] = $chess;
	}
	
	public function getLocationValue($x, $y) {
		return $this->b[$x][$y];
	}
	
	public function getSize() {
		return $this->size;
	}
	
	public function getChessList($player) {
		$list = array();
		for($i = 1; $i < count($this->b)-1; $i++) {
			for($j = 1; $j < count($this->b)-1; $j++) {
				if ($this->b[$i][$j] == $player)
					array_push($list, $j . $i);
			}
		}
		return $list;
	}
	
	public function getTotalChessList() {
		$list = array();
		for($i = 1; $i < count($this->b)-1; $i++) {
			for($j = 1; $j < count($this->b)-1; $j++) {
				if ($this->b[$i][$j] != 0)
					array_push($list, $j . $i);
			}
		}
		return $list;
	}
	
	public function move($y, $x, $player) {
		$this->b[$x][$y] = $player;
		return $this->updateBoard($y, $x, $player);
	}
	
	public function isEnd() {
		return !($this->canMove(1) || $this->canMove(2));
	}
	
	public function canMove($player) {
		return count($this->getListOfValidLocation($player)) != 0;
	}
	
	public function getListOfValidLocation($player) {
		$listOfValidLocation = array();
		
		for ($i = 0; $i < $this->size; $i++) {
			for ($j = 0; $j < $this->size; $j++) {
				if ($this->isValidMove($j+1, $i+1, $player)) {
					array_push($listOfValidLocation, ($j+1).($i+1));
				}
			}
		}

		return $listOfValidLocation;
	}
	
	//A valid move is one where at least one piece is reversed
	public function isValidMove($x, $y, $player) {
		if (!$this->isValidSelection($x, $y) || !$this->checkAnyOpponentChess($x, $y, $player)) {
			return false;
		}
		return count($this->getTotalNumberOfReverseChessOfOpponent($x, $y, $player)) != 0;
	}
	
	public function isValidSelection($y, $x) {
		return $this->b[$x][$y] == 0;
	}
	
	public function checkAnyOpponentChess($y, $x, $player) {
		for ($i = $x-1; $i < $x+2; $i++) {
			for ($j = $y-1; $j < $y+2; $j++) {
				if ($this->b[$i][$j] != 0 && $this->b[$i][$j] != $player && $this->b[$i][$j] != 3) {
					return true;
				}
			}
		}
		return false;
	}
	
	public function getTotalNumberOfReverseChessOfOpponent($y, $x, $player) {
		$list = array();
		$list = array_merge($list, $this->getLocationListOfReverseChessOfOpponent($x, $y, 0, $y, $player));
		$list = array_merge($list, $this->getLocationListOfReverseChessOfOpponent($x, $y, ($x+$y-1>$this->size)?$x-abs($this->size-$y)-1:0, ($x+$y>$this->size)?$this->size+1:$x+$y, $player));
		$list = array_merge($list, $this->getLocationListOfReverseChessOfOpponent($x, $y, $x, $this->size+1, $player));
		$list = array_merge($list, $this->getLocationListOfReverseChessOfOpponent($x, $y, ($x-$y+1>1)?$this->size+1:$x+($this->size-$y)+1, ($x-$y+1>1)?$y+($this->size-$x)+1:$this->size+1, $player));
		$list = array_merge($list, $this->getLocationListOfReverseChessOfOpponent($x, $y, $this->size+1, $y, $player));
		$list = array_merge($list, $this->getLocationListOfReverseChessOfOpponent($x, $y, ($x+$y>$this->size)?$this->size+1:$x+$y, ($x+$y-1>$this->size)?$y-($this->size-$x)-1:0, $player));
		$list = array_merge($list, $this->getLocationListOfReverseChessOfOpponent($x, $y, $x, 0, $player));
		$list = array_merge($list, $this->getLocationListOfReverseChessOfOpponent($x, $y, ($y-$x+1>1)?0:$x-$y, ($y-$x+1>1)?$y-$x:0, $player));

		return $list;
	}
	
	public function getLocationListOfReverseChessOfOpponent($x1, $y1, $x2, $y2, $player) {
		$x1Offset = 0;
		$y1Offset = 0;
		$realx2 = 0;
		$realy2 = 0;
		$list = array();
		
		do {
			if ($x1+$x1Offset<$x2) $x1Offset++; else if($x1+$x1Offset>$x2) $x1Offset--;
			if ($y1+$y1Offset<$y2) $y1Offset++; else if($y1+$y1Offset>$y2) $y1Offset--;
			
			if ($this->b[$x1+$x1Offset][$y1+$y1Offset] == 0 || $this->b[$x1+$x1Offset][$y1+$y1Offset] == 3) {
				return $list;
			} else {
				$realx2 = $x1+$x1Offset;
				$realy2 = $y1+$y1Offset;
				if ($this->b[$x1+$x1Offset][$y1+$y1Offset] == $player) {
					break;
				}
			}
		} while (true);
		
		$x1Offset = 0;
		$y1Offset = 0;
		
		do {
			if ($x1+$x1Offset<$realx2) $x1Offset++; else if($x1+$x1Offset>$realx2) $x1Offset--;
			if ($y1+$y1Offset<$realy2) $y1Offset++; else if($y1+$y1Offset>$realy2) $y1Offset--;
			
			if ($this->b[$x1+$x1Offset][$y1+$y1Offset] == $player) 
				break;
			else
				array_push($list, ($y1+$y1Offset) . ($x1+$x1Offset));
		} while (true);
		
		return $list;
	}
	
	public function isCorner($x, $y, $size) {
		if (($x == 1 && $y == 1) ||
			($x == 1 && $y == $size) ||
			($x == $size && $y == 1) ||
			($x == $size && $y == $size)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function getCornerCount($player) {
		$count = 0;
		if ($this->b[1][1]==$player) $count++;
        if ($this->b[$this->size][1]==$player) $count++;
        if ($this->b[1][$this->size]==$player) $count++;
        if ($this->b[$this->size][$this->size]==$player) $count++;
		return $count;
	}
	
	public function updateBoard($x, $y, $player) {
		$list = $this->getTotalNumberOfReverseChessOfOpponent($x, $y, $player);
		
		foreach ($list as $location) {
			list($x2, $y2) = str_split($location);
			$this->b[$x2][$y2] = $player;
		}
		
		return $list;
	}
	
	public function __clone() {
		foreach($this as $key => $val) {
			if (is_object($val) || (is_array($val))) {
				$this->{$key} = unserialize(serialize($val));
			} else {
				$this->{$key} = $val;
			}
		}
	}
}
?>