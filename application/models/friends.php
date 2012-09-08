<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Friends extends CI_Model {
	function __construct() 
	{
        parent::__construct();
     	$tableName = 'friends';
     	$primaryKey = 'id';
		
    	// Connect to Mongo
    	$this->connection = new Mongo('localhost:27017');

    	// Select a database
    	$this->db = $this->connection->tictactoe;
		
    	// Select a collection
    	$this->posts = $this->db->$tableName;
    }

	function addFriend($user1, $user2)
	{
		
		$validateFriend1 = $this->posts->findOne(array('userId' => $user1, 'friendId' => $user2));
		if(!$validateFriend1)
		{
			$friend1['userId'] = $user1;
			$friend1['friendId'] = $user2;
			$friend1['dateCreated'] = time();

			$this->posts->insert($friend1);
		}
		
		$validateFriend2 = $this->posts->findOne(array('userId' => $user2, 'friendId' => $user1));
		if(!$validateFriend2)
		{	
			$friend2['friendId'] = $user1;
			$friend2['userId'] = $user2;
			$friend2['dateCreated'] = time();

			$this->posts->insert($friend2);
		}
		
	}
	
	function validateFriend($userId, $friendId)
	{
		$validation = $this->posts->findOne(array('userId' => $userId, 'friendId' => $friendId));
		if(!$validation)
			return false;
		return true;
	}
	
	function getAll($userId=''){
		if($userId != '') $friends = $this->posts->find(array('userId' => $userId));
		else $friends = $this->posts->find();
		return $friends;
	}
}

?>