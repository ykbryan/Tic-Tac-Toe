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
	<h1>My Friends</h1>

	<div id="body">
		 <p><a href="<?php echo site_url(array('friend', 'index', $userId)); ?>">Back to FriendList</a></p>
		
         <p><a href="<?php echo site_url(array('welcome', 'home', $userId)); ?>">Back to Home</a></p>

		<form method="post">

			<p>
			<label for="title">Enter Friend's Username</label><br/>
			<input type="text" name="username" value="" />
			<?php if($found == -1): ?>
				<br /><span style="color:red">No such username</span>
			<?php endif; ?>
			</p>

			<p>
			<input type="submit" name="find" value="Find" />
			</p>

		</form>
		
		<?php if($found == 1): ?>
			
			<hr>
			
			<p>
				<li><strong><?php echo $foundMember['username'] ?></strong>
				[ 
				<?php if(!$isFriend): ?>
				<a href="<?php echo site_url(array('friend', 'sendFriendrequest', $userId, $foundMember['id'])); ?>">Send request to be friend</a>
				
				<?php else: ?>Your Friend
				<?php endif; ?>
				
				 ]</li>
			</p>
			
		<?php endif; ?>
		
		<?php if($result): ?>
			
			<hr>
			
			<p style="font-weight:bold"><?php echo $result; ?></p>
			
		<?php endif; ?>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
</div>

</body>
</html>