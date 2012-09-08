<?php

class Friend extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('date');
		$this->load->model('Users','',TRUE);
		$this->load->model('Rounds','',TRUE);
		$this->load->model('Friends','',TRUE);
		$this->load->model('Gamemoves','',TRUE);
		$this->load->model('Friendrequests','',TRUE);
	}
	
	function index()
	{
		//list of friend
		$friends = $this->Friends->getAll($this->uri->segment(3)); 
		$data = array(
			'friends' => array(),
			'friendrequests' => array()
		);
		
		$data['userId'] = $this->uri->segment(3);
		
		while($friends->hasNext()){ // While we have results
			$friend = $friends->getNext();// Get the next result
			
			$friend_user = $this->Users->getByID($friend['friendId']);
			$hasFriendsGame = $this->Rounds->hasFriendsGame($data['userId'],$friend_user["_id"]);
			
			if($hasFriendsGame)
				$hasGame = TRUE;
			else
			{
				$hasGame = FALSE;
				$hasFriendsGame['_id'] = 0;
			}
			
			$data['friends'][] = array(
				'id' => $friend["_id"]->__toString(),
				'friendId' => $friend_user['id'],
				'username' => $friend_user['username'],
				'hasGame' => $hasGame,
				'roundId' => $hasFriendsGame['_id'],
				'score' => $friend_user['score']
			);
		}
		
		//find list of friend requests
		$friendrequests = $this->Friendrequests->getAll($this->uri->segment(3)); 
		
		while($friendrequests->hasNext()){ // While we have results
			$friendrequest = $friendrequests->getNext();// Get the next result
			
			$data['friendrequests'][] = array(
				'id' => $friendrequest["_id"]->__toString(),
				'userId' => $friendrequest['userId'],
				'username' => $friendrequest['username'],
				'friendId' => $friendrequest['friendId']
			);
		}
		
		$this->load->view('friendList', $data);
	}
	
	function findFriend()
	{
		$data = array();
		$data['userId'] = $this->uri->segment(3);
		$data['found'] = 0;
		$data['isFriend'] = 0;
		$data['result'] = '';
		
		if($this->input->post('find'))
		{
			$username = $this->input->post('username');
			$members = $this->Users->getByColumn('username', $username);
			
			if($members)
			{
				$member = array(
					'id' => $members["_id"]->__toString(),
					'username' => $members['username']
				);
				
				if($member['id'] != $data['userId'])
				{
					$data['found'] = 1;
					
					if($this->Friends->validateFriend($data['userId'], $member['id']))
						$data['isFriend'] = 1;
					
					$data['foundMember'] = $member;
				}else
				{
					$data['found'] = -1;
				}
				
			}else
			{
				$data['found'] = -1;
			}
			
		}
		
		$this->load->view('findFriend', $data);
		
	}
	
	function sendFriendrequest()
	{
		$data['userId'] = $this->uri->segment(3);
		$data['friendId'] = $this->uri->segment(4);
		
		$user = $this->Users->getByID($this->uri->segment(3));
		
		// add new member into array
		if($this->Friendrequests->validateRequest($this->uri->segment(3), $this->uri->segment(4)))
		{
			$request = array(
				'userId' => $this->uri->segment(3),
				'friendId' => $this->uri->segment(4),
				'username' => $user['username'],
				'status' => 0,
				'dateCreated' => time()
			);
			$this->Friendrequests->sendRequest($request); // Insert the member
		}
		
		
		$data['found'] = 0;
		$data['result'] = 'Your request has been sent!';
		
		$this->index();
	}
	
	function rejectFriendrequest()
	{
		//$data['userId'] = $this->uri->segment(3);
		//$data['requestId'] = $this->uri->segment(4);
		
		$this->Friendrequests->rejectRequest($this->uri->segment(4));
		
		$this->index();
	}
	
	function acceptFriendrequest()
	{
		//$data['userId'] = $this->uri->segment(3);
		//$data['requestId'] = $this->uri->segment(4);
		
		$request = $this->Friendrequests->acceptRequest($this->uri->segment(4));
		
		$this->Friends->addFriend($request['userId'], $request['friendId']);
		
		$this->index();
	}
	
	function random($limit)
	{
		$data['userId'] = $this->uri->segment(3);
		
		$randomFriends = $this->Users->random(5, $data['userId']);
		
		$data = array(
			'friends' => array(),
			'current' => array(),
			'old' => array()
		);
		
		$data['userId'] = $this->uri->segment(3);
		
		while($randomFriends->hasNext()){ // While we have results
			$friend = $randomFriends->getNext();// Get the next result

			if( ($data['userId'] != $friend['_id']) && ($this->Rounds->hasGames($data['userId'], $friend['_id'])) )
			{

				$data['friends'][] = array(
					'id' => $friend["_id"]->__toString(),
					'friendId' => $friend['_id'],
					'username' => $friend['username'],
					'score' => $friend['score']
				);
			}			
		}
		
		//Games I created
		
		$currentRounds = $this->Rounds->getAllByUserId($data['userId']);
		
		while($currentRounds->hasNext()){ // While we have results
			$currentRound = $currentRounds->getNext();// Get the next result
			
			if($currentRound['isOver'] == 0){
				
				if($data['userId'] == $currentRound['userId'])
					$friend_user = $this->Users->getByID($currentRound['friendId']);
				else
					$friend_user = $this->Users->getByID($currentRound['userId']);
				
				$count = 0;
				foreach($this->Gamemoves->getAllMovesByGameID($currentRound['_id']->__toString()) as $move){
					$count++;
				}
						
				if($count % 2 == 0)
					$myturn = TRUE;
				else
					$myturn = FALSE;
			
				$data['current'][] = array(
					'id' => $currentRound["_id"]->__toString(),
					'username' => $friend_user['username'],
					'myturn' => $myturn
				);
			}	
		}
		
		//Games people challenged
		
		$currentRounds = $this->Rounds->getAllByFriendId($data['userId']);
		
		while($currentRounds->hasNext()){ // While we have results
			$currentRound = $currentRounds->getNext();// Get the next result
			
			if($currentRound['isOver'] == 0){
			
				if($data['userId'] == $currentRound['userId'])
					$friend_user = $this->Users->getByID($currentRound['friendId']);
				else
					$friend_user = $this->Users->getByID($currentRound['userId']);
			
				$count = 0;
				foreach($this->Gamemoves->getAllMovesByGameID($currentRound['_id']->__toString()) as $move){
					$count++;
				}
						
				if($count % 2 == 1)
					$myturn = TRUE;
				else
					$myturn = FALSE;
				
				$data['current'][] = array(
					'id' => $currentRound["_id"]->__toString(),
					'username' => $friend_user['username'],
					'myturn' => $myturn
				);
			}
		}
		
		//Old games I created
		
		$oldRounds = $this->Rounds->getAllOldByUserId($data['userId']);
		
		while($oldRounds->hasNext()){ // While we have results
			$oldRound = $oldRounds->getNext();// Get the next result
			
			if($data['userId'] == $oldRound['userId'])
				$friend_user = $this->Users->getByID($oldRound['friendId']);
			else
				$friend_user = $this->Users->getByID($oldRound['userId']);

			$data['old'][] = array(
				'id' => $oldRound["_id"]->__toString(),
				'username' => $friend_user['username'],
				'isDraw' => $oldRound["isDraw"],
				'hasWinner' => $oldRound["hasWinner"]
			);	
		}
		
		//Old games people challenged
		
		$oldRounds = $this->Rounds->getAllOldByFriendId($data['userId']);
		
		while($oldRounds->hasNext()){ // While we have results
			$oldRound = $oldRounds->getNext();// Get the next result
			
			if($data['userId'] == $oldRound['userId'])
				$friend_user = $this->Users->getByID($oldRound['friendId']);
			else
				$friend_user = $this->Users->getByID($oldRound['userId']);

			$data['old'][] = array(
				'id' => $oldRound["_id"]->__toString(),
				'username' => $friend_user['username'],
				'isDraw' => $oldRound["isDraw"],
				'hasWinner' => $oldRound["hasWinner"]
			);	
		}
		
		$this->load->view('selectPlayer', $data);
	}
	
	function ranking()
	{
		$data = array();
		$data['friends'] = array();
		$data['globalUsers'] = array();
		$data['userId'] = $this->uri->segment(3);
		
		$globalUsersRaw = $this->Users->getAllScores();
		while($globalUsersRaw->hasNext()){ 
			$user = $globalUsersRaw->getNext();
			
			if(($this->Friends->validateFriend($this->uri->segment(3), $user["_id"]->__toString()))||($data['userId'] == $user["_id"]->__toString()))
			{
				$data['friends'][] = array(
					'id' => $user["_id"]->__toString(),
					'username' => $user['username'],
					'score' => $user["score"]
				);
			}
			
			if(count($data['globalUsers']) < 10)
				$data['globalUsers'][] = array(
					'id' => $user["_id"]->__toString(),
					'username' => $user['username'],
					'score' => $user["score"]
				);
		}
				
		
		
		$this->load->view('ranking', $data);
	}
}

?>