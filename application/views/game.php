<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Welcome to CodeIgniter</title>

	<style type="text/css">

	::selection{ background-color: #E13300; color: white; }
	::moz-selection{ background-color: #E13300; color: white; }
	::webkit-selection{ background-color: #E13300; color: white; }

	body {
		background-color: #fff;
		margin: 0px;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #4F5155;
	}

	a {
		color: #003399;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #444;
		background-color: transparent;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 10px 0;
		padding: 14px 15px 0px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body{
		margin: 0 15px 0 15px;
	}
	
	p.footer{
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}
	
	#container{
		margin: 10px;
		border: 1px solid #D0D0D0;
		-webkit-box-shadow: 0 0 8px #D0D0D0;
	}
	p{
		margin-left:16px;
	}
	td{
		font-size:30px;
		color:#FFFFFF;
		text-align: center;
	}
	.gamebox{
		background-color: #888888;
		height:50px;
		width:50px;
	}
	.notgamebox{
		background-color: #AAAAAA;
		height:50px;
		width:50px;
	}
	.winbox{
		background-color: #AAAAAA;
		height:50px;
		width:50px;
		color:red;
	}
	</style>
</head>
<body>

<div id="container">
	<h1>My Games</h1>

	<div id="body">
         <p><a href="<?php echo site_url(array('friend', 'random', $userId)); ?>">Back to Home</a></p>
		<hr>
		
		<strong style="font-size:18px; font-weight:400">My Game with <?php echo $playername; ?></strong>
		<?php if($status): ?>
		<p style="color:#900000; font-weight:bold">
			<?php echo $status; ?>
			<?php if($end): ?>
				<br /><a href="<?php echo site_url(array('friend', 'random', $userId)); ?>">Back to My Games!</a>
			<?php endif; ?>
		</p>
		<?php endif; ?>
		<p>
		
		<table cellspacing="5">
			<tr>
				<td class="<?php if($position11 == 2) echo 'winbox'; elseif($myturn) echo 'gamebox'; else echo 'notgamebox';  ?>" <?php if($myturn && ($position11 == 0)): ?> onclick="document.location='<?php echo site_url(array('games', 'addMove', $userId, $gameId, 11)); ?>'" <?php endif; ?>>
					<?php if(($position11 == 1)||($position11 == 2)): ?>
						X
					<?php elseif(($position11 == -1)||($position11 == 2)): ?>
						O
					<?php endif; ?>
				</td>
				<td class="<?php if($position12 == 2) echo 'winbox'; elseif($myturn) echo 'gamebox'; else echo 'notgamebox';  ?>" <?php if($myturn && ($position12 == 0)): ?> onclick="document.location='<?php echo site_url(array('games', 'addMove', $userId, $gameId, 12)); ?>'" <?php endif; ?>>
					<?php if(($position12 == 1)||($position12 == 2)): ?>
						X
					<?php elseif(($position12 == -1)||($position12 == 2)): ?>
						O
					<?php endif; ?>
				</td>
				<td class="<?php if($position13 == 2) echo 'winbox'; elseif($myturn) echo 'gamebox'; else echo 'notgamebox';  ?>" <?php if($myturn && ($position13 == 0)): ?> onclick="document.location='<?php echo site_url(array('games', 'addMove', $userId, $gameId, 13)); ?>'" <?php endif; ?>>
					<?php if(($position13 == 1)||($position13 == 2)): ?>
						X
					<?php elseif(($position13 == -1)||($position13 == 2)): ?>
						O
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td class="<?php if($position21 == 2) echo 'winbox'; elseif($myturn) echo 'gamebox'; else echo 'notgamebox';  ?>" <?php if($myturn && ($position21 == 0)): ?> onclick="document.location='<?php echo site_url(array('games', 'addMove', $userId, $gameId, 21)); ?>'" <?php endif; ?>>
					<?php if(($position21 == 1)||($position21 == 2)): ?>
						X
					<?php elseif(($position21 == -1)||($position21 == 2)): ?>
						O
					<?php endif; ?>
				</td>
				<td class="<?php if($position22 == 2) echo 'winbox'; elseif($myturn) echo 'gamebox'; else echo 'notgamebox';  ?>" <?php if($myturn && ($position22 == 0)): ?> onclick="document.location='<?php echo site_url(array('games', 'addMove', $userId, $gameId, 22)); ?>'" <?php endif; ?>>
					<?php if(($position22 == 1)||($position22 == 2)): ?>
						X
					<?php elseif(($position22 == -1)||($position22 == 2)): ?>
						O
					<?php endif; ?>
				</td>
				<td class="<?php if($position23 == 2) echo 'winbox'; elseif($myturn) echo 'gamebox'; else echo 'notgamebox';  ?>" <?php if($myturn && ($position23 == 0)): ?> onclick="document.location='<?php echo site_url(array('games', 'addMove', $userId, $gameId, 23)); ?>'" <?php endif; ?>>
					<?php if(($position23 == 1)||($position23 == 2)): ?>
						X
					<?php elseif(($position23 == -1)||($position23 == 2)): ?>
						O
					<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td class="<?php if($position31 == 2) echo 'winbox'; elseif($myturn) echo 'gamebox'; else echo 'notgamebox';  ?>" <?php if($myturn && ($position31 == 0)): ?> onclick="document.location='<?php echo site_url(array('games', 'addMove', $userId, $gameId, 31)); ?>'" <?php endif; ?>>
					<?php if(($position31 == 1)||($position31 == 2)): ?>
						X
					<?php elseif(($position31 == -1)||($position31 == 2)): ?>
						O
					<?php endif; ?>
				</td>
				<td class="<?php if($position32 == 2) echo 'winbox'; elseif($myturn) echo 'gamebox'; else echo 'notgamebox';  ?>" <?php if($myturn && ($position32 == 0)): ?> onclick="document.location='<?php echo site_url(array('games', 'addMove', $userId, $gameId, 32)); ?>'" <?php endif; ?>>
					<?php if(($position32 == 1)||($position32 == 2)): ?>
						X
					<?php elseif(($position32 == -1)||($position32 == 2)): ?>
						O
					<?php endif; ?>
				</td>
				<td class="<?php if($position33 == 2) echo 'winbox'; elseif($myturn) echo 'gamebox'; else echo 'notgamebox';  ?>" <?php if($myturn && ($position33 == 0)): ?> onclick="document.location='<?php echo site_url(array('games', 'addMove', $userId, $gameId, 33)); ?>'" <?php endif; ?>>
					<?php if(($position33 == 1)||($position33 == 2)): ?>
						X
					<?php elseif(($position33 == -1)||($position33 == 2)): ?>
						O
					<?php endif; ?>
				</td>
			</tr>
		</table>
		
		</p>
		<p>
			<strong>Legends:</strong><br />
			X - Your Moves<br />
			O - Opponent's Moves<br />
		</p>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>

</body>
</html>