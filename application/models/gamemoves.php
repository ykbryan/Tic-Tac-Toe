<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gamemoves extends CI_Model{
	
	function __construct() {
        parent::__construct();
     	$tableName = 'gamemoves';
     	$primaryKey = 'id';
		
    	// Connect to Mongo
    	$this->connection = new Mongo('localhost:27017');

    	// Select a database
    	$this->db = $this->connection->tictactoe;
		
    	// Select a collection
    	$this->posts = $this->db->$tableName;
    }

	function add($userId, $gameId, $move)
	{
		$validateGame = $this->posts->findOne(array('gameId' => $gameId, 'move' => $move));
		if($validateGame)
			return $validateGame['_id'];
		
		$game = array();
		
		$game['gameId'] = $gameId;
		$game['userId'] = $userId;
		$game['move'] = $move;
		$game['dateCreated'] = time();
		
		$this->posts->insert($game);
		return $game['_id'];
		
	}
	
	function getAllMovesByGameID($gameID)
	{
		$moves = $this->posts->find(array('gameId' => $gameID));
		return $moves;
	}
	
	function countNumberOfMovesByGameID($gameId)
	{
		$moves = $this->posts->find(array('gameId' => $gameId));
		
		$count = 0;
		while($moves->hasNext()){ // While we have results
			$move = $moves->getNext();// Get the next result
			$count++;
		}
			
		
		return $count;
	}
	
}

?>