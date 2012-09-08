<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Friendrequests extends CI_Model {
	
	function __construct() {
        parent::__construct();
     	$tableName = 'friendrequests';
     	$primaryKey = 'id';
		
    	// Connect to Mongo
    	$this->connection = new Mongo('localhost:27017');

    	// Select a database
    	$this->db = $this->connection->tictactoe;
		
    	// Select a collection
    	$this->posts = $this->db->$tableName;
    }

	function validateRequest($userId, $friendId)
	{
		$request = $this->posts->findOne(array('userId' => $userId, 'friendId' => $friendId, 'status' => 0));
		if($request)
			return false;
		else
		{
			$request2 = $this->posts->findOne(array('userId' => $friendId, 'friendId' => $userId, 'status' => 0));
			if($request2)
				return false;
		}
		return true;
	}
	
	function sendRequest($request)
	{
		if ($request != ''){
			if (!isset($request['id'])){ // new record
				$this->posts->insert($request);
				return $request['_id'];
			} else { // edit existing record
				$requestid = $request['id'];

				$this->posts->update(array('_id' => new MongoId($requestid)), $request, array("multiple" => false));
				return $requestid;
			}
		}
    }

	function rejectRequest($requestid)
	{
		$request = $this->posts->findOne(array('_id' => new MongoId($requestid)));
		$request['status'] = -1;
		/*
		$this->posts->update(array('_id' => new MongoId($requestid), $request, array("multiple" => false)));
		return $requestid;
		*/
		$this->posts->update(array('_id' => new MongoId($requestid)), $request, array("multiple" => false));
		return $requestid;
	}
	
	function acceptRequest($requestid)
	{
		$request = $this->posts->findOne(array('_id' => new MongoId($requestid)));
		$request['status'] = 1;
		
		$this->posts->update(array('_id' => new MongoId($requestid)), $request, array("multiple" => false));
		return $request;
	}
	
	function getAll($userId='')
	{

		if($userId != '') $friendrequests = $this->posts->find(array('friendId' => $userId, 'status' => 0));
		else $friendrequests = $this->posts->find(array('status' => 0));
		
		return $friendrequests;

	}
}

?>