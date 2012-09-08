<?php

class Rounds extends CI_Model {
	function __construct() 
	{
        parent::__construct();
     	$tableName = 'rounds';
     	$primaryKey = 'id';
		
    	// Connect to Mongo
    	$this->connection = new Mongo('localhost:27017');

    	// Select a database
    	$this->db = $this->connection->tictactoe;
		
    	// Select a collection
    	$this->posts = $this->db->$tableName;
    }

	function add($userId, $friendId)
	{
		$validateGame = $this->posts->find(array('userId' => $userId));
		foreach($validateGame as $game)
		{
			if(($game['friendId'] == $friendId)&&($game['isOver'] == 0))
			{
				return $game['_id'];
			}
		}
		
		$validateGame2 = $this->posts->find(array('friendId' => $userId));
		foreach($validateGame2 as $game)
		{
			if(($game['userId'] == $friendId)&&($game['isOver'] == 0))
				{
					return $game['_id'];
				}
		}
			
		$game = array();
		
		$game['isDraw'] = 0;
		$game['isOver'] = 0;
		$game['hasWinner'] = 0;
		//to determine who start the game, and whose turn to make a move
		$game['userId'] = $userId;
		$game['friendId'] = $friendId;
		$game['dateCreated'] = time();
		
		$this->posts->insert($game);
		return $game['_id'];
	}
	
	function getByID($id)
	{
		$game = $this->posts->findOne(array('_id' => new MongoId($id)));
        if ($game) {
        	return $game;
        }
        return false;
	}
	
	function validateEndRound($gameId)
	{
		$game = $this->getByID($gameId);
		if($game)
		{
			if($game['isOver'] != 0)
				return false;
		}else
			return false;
		return true;
	}
	
	function hasGames($userId, $friendId)
	{
		$rounds_1 = $this->posts->find(array('userId' => $userId));
		$count = 0;
		foreach($rounds_1 as $round1)
		{
			if(($round1['friendId'] == $friendId)&&($round1['isOver'] == 0))
			{
				$count++;
			}
		}
		
		$rounds_2 = $this->posts->find(array('friendId' => $userId));
		foreach($rounds_2 as $round2)
		{
			if(($round2['userId'] == $friendId)&&($round2['isOver'] == 0))
			{
				$count++;
			}
		}
					
		if($count > 0)
			return false;
		return true;
	}
	
	function hasFriendsGame($userId, $friendId)
	{
		$rounds_1 = $this->posts->findOne(array('userId' => $userId));
		if($rounds_1)
			if(($rounds_1['friendId'] == $friendId)&&($rounds_1['isOver'] == 0))
				return $rounds_1;
		
		$rounds_2 = $this->posts->findOne(array('friendId' => $userId));
		if($rounds_2)
			if(($rounds_2['userId'] == $friendId)&&($rounds_2['isOver'] == 0))
				return $rounds_2;
		
		return false;
	}
	
	function getAllByUserId($userId)
	{
		$rounds = $this->posts->find(array('userId' => $userId));
		return $rounds;
	}
	
	function getAllByFriendId($userId)
	{
		$rounds2 = $this->posts->find(array('friendId' => $userId));
		return $rounds2;
	}
	
	function getAllOldByUserId($userId)
	{
		$rounds = $this->posts->find(array('userId' => $userId, 'isOver' => 1));
		return $rounds;
	}
	
	function getAllOldByFriendId($userId)
	{
		$rounds2 = $this->posts->find(array('friendId' => $userId, 'isOver' => 1));
		return $rounds2;
	}
		
	function draw($gameId)
	{
		$game = $this->getByID($gameId);
		
		$game['isDraw'] = 1;
		$game['isOver'] = 1;
		
		$this->posts->update(array('_id' => new MongoId($gameId)), $game, array("multiple" => false));
	}
	
	function end($gameId, $userId)
	{
		$game = $this->getByID($gameId);
		
		$game['isDraw'] = 0;
		$game['isOver'] = 1;
		$game['hasWinner'] = $userId;
		
		$this->posts->update(array('_id' => new MongoId($gameId)), $game, array("multiple" => false));
	}
}

?>