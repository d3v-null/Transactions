<?php

require_once 'includes/config.php';

if (isset($_GET['tkn'])) { // when a login token exists

	$user = User::findByToken($_GET['tkn']);

	if ($user) { // correct token, log in the user
		$user->login();
		redirect('search.php');
	}

	redirect('index.php'); // incorrect token
}


if (isset($_GET['logout'])) { // when logging out a user

	$user = new User();

	if($user->loggedIn()){
		$user->logout();
	}

	redirect('index.php');
}


// when a user is already logged in
$user = new User();

if($user->loggedIn()){
	redirect('search.php');
}


// login form with AJAX validation
try{

	if(!empty($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH'])){

		header('Content-type: application/json');

		if(!isset($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			throw new Exception('Please enter a valid email.');
		}

		rate_limit($_SERVER['REMOTE_ADDR']);
		rate_limit_tick($_SERVER['REMOTE_ADDR'], $_POST['email']);

		// send email to the user
		$message = '';
		$email = $_POST['email'];
		$subject = 'Access Granted!';

		if(!User::exists($email)){
			$subject = "Access Granted!";
			$message = "Your access to the ledger system is granted!\n\n";
		}

		// Attempt to login or register the person
		$user = User::loginOrRegister($_POST['email']);

		$message.= "You can access the ledger system from this URL:\n";
		$message.= get_page_url()."?tkn=".$user->generateToken()."\n\n";
		$message.= "The link is going expire automatically after 7 days.";
		$result = send_email($fromEmail, $_POST['email'], $subject, $message);

		$link = get_page_url()."?tkn=".$user->generateToken()."\n\n";

		if (!$result) {
			throw new Exception("There was an error sending your email. Please try again.");
		}

		die(json_encode(array(
			'message' => $link
		)));
	}
}
catch(Exception $e){

	die(json_encode(array(
		'error'=>1,
		'message' => $e->getMessage()
	)));
}

?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>Access Request</title>
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css">
		<!--[if lt IE 9]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
	</head>

	<body>
		<div class="jumbotron">
			<div class="container">
				<h2>Welcome to the Swedish Club of WA Ledger System</h2>
				<p>blah la blah la</p>
				<a data-toggle="modal" data-target="#register" href="#register" class="btn btn-primary btn-lg">Request Access</a>
			  <div class="modal fade" id="register" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			    <div class="modal-dialog">
			      <div class="modal-content">
			        <div class="modal-header">
			          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			          <h4 class="modal-title">Request Access</h4>
			        </div>
			        <form id="login-register" method="post" action="index.php" role="form">
				        <div class="modal-body">
				        	<small class="help-block">Enter your email address and we will send you a login link.</small>
									<input class="form-control" type="text" placeholder="your@email.com" name="email" autofocus/>
									<span></span>
				        </div>
				        <div class="modal-footer">
				          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				          <button type="submit" class="btn btn-primary">Submit</button>
				        </div>
			        </form>
			     	</div>
			   	</div>
				</div>
			</div>
		</div>

		<script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="js/script.js"></script>
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
	</body>
</html>