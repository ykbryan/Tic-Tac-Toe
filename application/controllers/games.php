<?php

class Games extends CI_Controller{
	
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('date');
		$this->load->model('Rounds','',TRUE);
		$this->load->model('Users','',TRUE);
		$this->load->model('Gamemoves','',TRUE);
	}
	
	function start()
	{	
		$data = array();
		$data['userId'] = $this->uri->segment(3);
		$data['friendId'] = $this->uri->segment(4);
		$data['gameId'] = $this->Rounds->add($this->uri->segment(3), $this->uri->segment(4));
		
		$player = $this->Users->getByID($this->uri->segment(4));
		$data['playername'] = $player['username'];
		
		$data['status'] = '';
		
		$data['myturn'] = 1;
		$data['position11'] = 0;
		$data['position12'] = 0;
		$data['position13'] = 0;
		$data['position21'] = 0;
		$data['position22'] = 0;
		$data['position23'] = 0;
		$data['position31'] = 0;
		$data['position32'] = 0;
		$data['position33'] = 0;
		
		$this->load->view('game', $data);
	}
	
	function view()
	{
		$this->resume();
	}
	
	function addMove()
	{
		$data = array();
		$data['userId'] = $this->uri->segment(3);
		$data['gameId'] = $this->uri->segment(4);
		
		$this->Gamemoves->add($this->uri->segment(3), $this->uri->segment(4), $this->uri->segment(5));
		
		$this->resume();

	}
	
	function resume()
	{
		$data = array();
		
		$data['userId'] = $this->uri->segment(3);
		$data['gameId'] = $this->uri->segment(4);
		
		$game = $this->Rounds->getByID($this->uri->segment(4));
		$gamemoves = $this->Gamemoves->getAllMovesByGameID($this->uri->segment(4));
		
		$data['position11'] = 0;
		$data['position12'] = 0;
		$data['position13'] = 0;
		$data['position21'] = 0;
		$data['position22'] = 0;
		$data['position23'] = 0;
		$data['position31'] = 0;
		$data['position32'] = 0;
		$data['position33'] = 0;
		
		$numberOfMoves = 0;
		
		$data['end'] = 0;
		
		$winningMoves = array(
			array(11,12,13),
			array(21,22,23),
			array(31,32,33),
			array(11,21,31),
			array(12,22,32),
			array(13,23,33),
			array(11,22,33),
			array(13,22,31)
		);
		$winningMovesStatus = array();
		foreach($winningMoves as $winMove):
			$winningMovesStatus[] = 0;
		endforeach;
		
		foreach($gamemoves as $move):
			$numberOfMoves++;
			
			//check if anyone wins
			$winningMovesPosition = 0;
			foreach($winningMoves as $winningMove):
				foreach($winningMove as $wMove):
					
					if($move['move'] == $wMove)
					{
						if($move['userId'] == $data['userId']) $winningMovesStatus[$winningMovesPosition]++;
						else $winningMovesStatus[$winningMovesPosition]--;
					}
					
				endforeach;
				$winningMovesPosition++;
			endforeach;
			//end check
			
			if($move['move'] == '11')
			{
				if($move['userId'] == $data['userId'])
					$data['position11'] = 1;
				else
					$data['position11'] = -1;					
			}else if($move['move'] == '12')
			{
				if($move['userId'] == $data['userId'])
					$data['position12'] = 1;
				else
					$data['position12'] = -1;
			}else if($move['move'] == '13')
			{
				if($move['userId'] == $data['userId'])
					$data['position13'] = 1;
				else
					$data['position13'] = -1;
			}
			else if($move['move'] == '21')
			{
				if($move['userId'] == $data['userId'])
					$data['position21'] = 1;
				else
					$data['position21'] = -1;
			}
			else if($move['move'] == '22')
			{
				if($move['userId'] == $data['userId'])
					$data['position22'] = 1;
				else
					$data['position22'] = -1;
			}
			else if($move['move'] == '23')
			{
				if($move['userId'] == $data['userId'])
					$data['position23'] = 1;
				else
					$data['position23'] = -1;
			}
			else if($move['move'] == '31')
			{
				if($move['userId'] == $data['userId'])
					$data['position31'] = 1;
				else
					$data['position31'] = -1;
			}
			else if($move['move'] == '32')
			{
				if($move['userId'] == $data['userId'])
					$data['position32'] = 1;
				else
					$data['position32'] = -1;
			}
			else if($move['move'] == '33')
			{
				if($move['userId'] == $data['userId'])
					$data['position33'] = 1;
				else
					$data['position33'] = -1;
			}
			
		endforeach;
				
		$numberOfLines = 0;
		$winningMovesStatusPosition = 0;
		foreach($winningMovesStatus as $status):
			if(($status == 3)||($status == -3))
			{
				
				if($status == 3)
				{
					$numberOfLines++;
				}	
				else
				{
					$numberOfLines--;
				}
					
				foreach($winningMoves[$winningMovesStatusPosition] as $winningPosition):
								
				if($winningPosition == '11')
					$data['position11'] = 2;
				elseif($winningPosition == '12')
					$data['position12'] = 2;
				elseif($winningPosition == '13')
					$data['position13'] = 2;
				elseif($winningPosition == '21')
					$data['position21'] = 2;
				elseif($winningPosition == '22')
					$data['position22'] = 2;
				elseif($winningPosition == '23')
					$data['position23'] = 2;
				elseif($winningPosition == '31')
					$data['position31'] = 2;
				elseif($winningPosition == '32')
					$data['position32'] = 2;
				elseif($winningPosition == '33')
					$data['position33'] = 2;
					
				endforeach;
					
			}
			$winningMovesStatusPosition++;
		endforeach;
		
		if($numberOfLines != 0)
		{
			$data['myturn'] = 0;
			$data['end'] = 1;
			
			if($this->Rounds->validateEndRound($data['gameId']))
			{
				$this->Rounds->end($data['gameId'], $data['userId']);
				if($numberOfLines > 0)
					$this->Users->updateScore($data['userId'], $numberOfLines);
			}
			
			if($numberOfLines > 0)
				$data['status'] = 'You win! (+' . $numberOfLines.')';
			else
				$data['status'] = 'You lose!';
		}
		elseif($numberOfMoves == 9)
		{
			$this->Rounds->draw($data['gameId']);
		}
	
		
		if($data['userId'] == $game['userId'])
		{
			$player = $this->Users->getByID($game['friendId']);
			
			if($numberOfLines == 0)
			{
				if($numberOfMoves == 9)
				{
					$data['status'] = "No more moves available. Draw!";
					$data['myturn'] = 0;
				}
				//I start the game
				elseif($numberOfMoves % 2)
				{
					$data['status'] = "Awaiting Your Opponent";
					$data['myturn'] = 0;
				}else
				{
					$data['status'] = "It is your turn!";
					$data['myturn'] = 1;
				}
			}
			
		}
		else
		{
			$player = $this->Users->getByID($game['userId']);
		
			if($numberOfLines == 0)
			{
				if($numberOfMoves == 9)
				{
					$data['status'] = "No more moves available. Draw!";
					$data['myturn'] = 0;
				}
				//he start the game
				elseif($numberOfMoves % 2)
				{
					$data['status'] = "It is your turn!";
					$data['myturn'] = 1;
				}else
				{
					$data['status'] = "Awaiting Your Opponent";
					$data['myturn'] = 0;
				}
			}
			
		}
		$data['playername'] = $player['username'];
		$data['playerid'] = $player['id'];
		
		$this->load->view('game', $data);
	}
	
	
	
}

?>