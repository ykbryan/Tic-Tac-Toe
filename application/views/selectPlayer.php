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
	</style>
</head>
<body>

<div id="container">
	<h1>My Games</h1>

	<div id="body">
         <p><a href="<?php echo site_url(array('welcome', 'home', $userId)); ?>">Back to Home</a></p>

		<?php if($current): ?>
		<hr>
		<strong style="font-size:18px; font-weight:400">Current Games</strong>
		<p>
			<?php foreach($current as $round): ?>
				<li><?php echo $round['username']; ?> [ <a href="<?php echo site_url(array('games', 'resume', $userId, $round['id'])); ?>">
					<?php if($round['myturn']): ?>
						Awaiting Your Move
					<?php else: ?>
						Awaiting Opponent's Move
					<?php endif; ?>
				</a> ]</li>
		</p>
		<?php endforeach; ?>
		<?php endif; ?>
		
		<?php if($friends): ?>
		<hr>
		<strong style="font-size:18px; font-weight:400">New Games</strong>
		<p>
		<?php foreach($friends as $friend): ?>
			<li><?php echo $friend['username']; ?> [ <a href="<?php echo site_url(array('games', 'start', $userId, $friend['friendId'])); ?>">Play</a> ]</li>
		</p>
		<?php endforeach; ?>
		<?php endif; ?>
		
		<?php if($old): ?>
		<hr>
		<strong style="font-size:18px; font-weight:400">Old Games</strong>
		<p>
			<?php foreach($old as $o): ?>
				<li><?php echo $o['username']; ?> (<?php if($o['isDraw']) echo 'Draw'; elseif($o['hasWinner'] == $userId) echo "Win"; else echo "Lose"; ?>) [ <a href="<?php echo site_url(array('games', 'view', $userId, $o['id'])); ?>">View</a> ]</li>
		</p>
		<?php endforeach; ?>
		<?php endif; ?>
		
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>

</body>
</html>