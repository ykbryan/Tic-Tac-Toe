<?php

class Welcome extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('date');
		$this->load->model('Users','',TRUE);
		$this->load->model('Rounds','',TRUE);
		$this->load->model('Gamemoves','',TRUE);
	}


	function index()
	{
		// If form submitted
		$data['error'] = '';
		
		
		if($this->input->post('login'))
		{
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			
			$members = $this->Users->login($username, $password);
			
			if($members)
			{
				$member = array(
					'id' => $members["_id"]->__toString(),
					'username' => $members['username'],
					'password' => $members['password'],
					'score' => $members['score'],
					'dateCreated' => $members['dateCreated'],
					'dateLastLogin' => time()
				);

				$this->Users->save($member);
				
				$data = array();
				
				$data['userId'] = $members["_id"]->__toString();
				$data['inserted'] = FALSE;
				$data['message'] = '';
				
				//if login
				$this->load->view('home', $data);
			}else //if unmatch
			{
				$data['error'] = "no match found";
				$this->load->view('welcome', $data);
			}
				
			
		}else
			$this->load->view('welcome', $data);
	}
	
	function home()
	{
		$data = array();
		$data['userId'] = $this->uri->segment(3);
		$data['message'] = '';
		
		//check all rounds and number of rounds requires my move
		$numberOfMovesRequired = 0;
		$currentRounds = $this->Rounds->getAllByUserId($data['userId']); //games i created
		$count = 0;
		foreach($currentRounds as $currentRound){
			if($currentRound['isOver'] == 0)
			foreach($this->Gamemoves->getAllMovesByGameID($currentRound['_id']->__toString()) as $move){
				$count++;
			}
		}
		if(($count % 2 == 0) && ($count != 0)){
			$numberOfMovesRequired++;
		}
			
			
		$currentRounds = $this->Rounds->getAllByFriendId($data['userId']); //games friend created
		$count = 0;
		foreach($currentRounds as $currentRound){
			if($currentRound['isOver'] == 0)
			foreach($this->Gamemoves->getAllMovesByGameID($currentRound['_id']->__toString()) as $move){
				$count++;
			}
		}		
		if(($count % 2 == 1) && ($count != 0))
			$numberOfMovesRequired++;
			
		if($numberOfMovesRequired > 0)
			if($numberOfMovesRequired == 1)
				$data['message'] = '1 game requires your move';
			else
				$data['message'] = $numberOfMovesRequired . ' games requires your move';
			
		$this->load->view('home', $data);
	}
	
	function viewAll()
	{
		$members = $this->Users->getAll(5,array('date' => -1)); // Find all members, limit by 5, order by date
		$data = array(
			'members' => array()
		);
		while($members->hasNext()){ // While we have results
			$member = $members->getNext();// Get the next result
			
			$data['members'][] = array(
				'id' => $member["_id"]->__toString(),
				'username' => $member['username'],
				'password' => $member['password'],
				'dateCreated' => $member['dateCreated'],
				'dateLastLogin' => $member['dateLastLogin']
			);
		}
		
		$this->load->view('users/list', $data);
	}
	
	function add()
	{
		$data = array();
		$data['inserted'] = FALSE;
		$data['error'] = '';
		
		// If form submitted
		if($this->input->post('add'))
		{
			// add new member into array
			if($this->Users->validateUsername($this->input->post('username')))
			{
				$member = array(
					'username' => $this->input->post('username'),
					'password' => $this->input->post('password'),
					'score' => 0,
					'dateLastLogin' => time(),
					'dateCreated' => time()
				);
				$this->Users->save($member); // Insert the member

				$data['inserted'] = TRUE;
			}
			else
			{
				$data['inserted'] = FALSE;
				$data['error'] = 'Username has been taken!';
			}
		}
		
		$this->load->view('users/add', $data); // Load the form
	}
	
	function edit($memberid)
	{
		$members = $this->Users->getByID($memberid); // Find member details
		$data = array(
			'id' => $members["_id"]->__toString(),
			'username' => $members['username'],
			'password' => $members['password'],
			'inserted' => FALSE
		);
		
		// If form submitted
		if($this->input->post('edit'))
		{
			// add new member into array
			$member = array(
				'id' => $memberid,
				'username' => $this->input->post('username'),
				'password' => $this->input->post('password'),
				'score' => $members['score'],
				'dateCreated' => $members['dateCreated'],
				'dateLastLogin' => time()
			);
			$this->Users->save($member); // Insert the member
			
			$data['inserted'] = TRUE;
			$this->load->view('edit', $data); 
		}else
		
		$this->load->view('users/edit', $data); // Load the form
	}
	
	function view($memberid)
	{
		$members = $this->Users->getByID($memberid); // Find member details
		
		$data = array(
			'id' => $members['id'],
			'username' => $members['username'],
			'password' => $members['password'],
			'dateCreated' => $members['dateCreated'],
			'dateLastLogin' => $members['dateLastLogin']
		);
		
		$this->load->view('users/view', $data);
	}
	
	function delete($memberid)
	{
		$members = $this->Users->deleteById($memberid); // Find member details
		redirect('/', 'refresh');
	}

}