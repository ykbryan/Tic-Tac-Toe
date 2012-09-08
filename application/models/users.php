<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Model {
	
    
    function __construct() {
        parent::__construct();
     	$tableName = 'users';
     	$primaryKey = 'id';
		
    	// Connect to Mongo
    	$this->connection = new Mongo('localhost:27017');

    	// Select a database
    	$this->db = $this->connection->tictactoe;
		
    	// Select a collection
    	$this->posts = $this->db->$tableName;
    }
	
	/** Insert new record */
	function save($member='') {
		if ($member != ''){
			if (!isset($member['id'])){ // new record
				$this->posts->insert($member);
				return $member['_id'];
			} else { // edit existing record
				$memberid = $member['id'];
				//unset($member['id']);
				$this->posts->update(array('_id' => new MongoId($memberid)), $member, array("multiple" => false));
				return $memberid;
			}
		}
    }
	
	/** Fetches all records with limit and orderby values's */
	function getAll($limit='', $orderby='') {
		$members = $this->posts->find();
		if ($limit != ''){ $members->limit($limit);}
		if ($orderby != ''){$members = $members->sort($orderby);}
		return $members;
    }
	

    /** Fetches a record by its' passed field and values's */
    function getByID($id='') {
		$member = $this->posts->findOne(array('_id' => new MongoId($id)));
        if ($member) {
        	return $member;
        }
        return false;
    }
	

    /** Fetches a record by its' passed field and values's */
    function getByColumn($field='id', $value='') {
		$member = $this->posts->findOne(array($field => $value));
        if ($member) {
        	return $member;
        }
        return false;
    }
	
    /** Deletes a record by it's primary key */
    function deleteById($id) {
		$this->posts->remove(array('_id' => new MongoId($id)), array("justOne" => true));
    }

	/** Fetches a record by its' passed username and password **/
	function login($username, $password) {
		
		$member = $this->posts->findOne(array('username' => $username));
		
        if ($member) {
			if($password != $member['password'])
				return false;
        	return $member;
        }
        return false;
	}
	
	function validateUsername($username)
	{
		$member = $this->posts->findOne(array('username' => $username));
		if($member)
			return false;
		return true;
	}

	function random($limit, $userId)
	{
		$randomFriends = $this->posts->find();
		
		//$randomFriends->limit($limit);
		//$randomFriends->sort({'random'=>1 });
		//array_rand($randomFriends, $limit);
		
		return $randomFriends;
	}
	
	function updateScore($userId, $score)
	{
		$member = $this->getByID($userId);
		if($member)
		{
			$member['score'] += intval($score);
			$this->save($member);
		}
	}
	
	function getAllScores()
	{
		$members = $this->posts->find();
		$members = $members->sort(array('score' => -1));
		
		return $members;
	}
}

?>