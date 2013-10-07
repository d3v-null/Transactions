<?php

function send_email($from, $to, $subject, $message){

	// Helper function for sending email

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/plain; charset=utf-8' . "\r\n";
	$headers .= 'From: '.$from . "\r\n";

	return mail($to, $subject, $message, $headers);
}


function get_page_url(){

	// Find out the URL of a PHP file

	$url = 'http'.(empty($_SERVER['HTTPS'])?'':'s').'://'.$_SERVER['SERVER_NAME'];

	if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] != ''){
		$url.= $_SERVER['REQUEST_URI'];
	}
	else{
		$url.= $_SERVER['PATH_INFO'];
	}

	return $url;
}


function rate_limit($ip, $limit_hour = 20, $limit_168h = 168){

	// The number of login attempts for the last hour by this IP address

	$count_hour = ORM::for_table('login_attempt')
					->where('ip', sprintf("%u", ip2long($ip)))
					->where_raw("ts > SUBTIME(NOW(),'1:00')")
					->count();

	// The number of login attempts for the last 7 days by this IP address

	$count_168h =  ORM::for_table('login_attempt')
					->where('ip', sprintf("%u", ip2long($ip)))
					->where_raw("ts > SUBTIME(NOW(),'168:00')")
					->count();

	if($count_hour > $limit_hour || $count_168h > $limit_168h){
		throw new Exception('Too many login attempts!');
	}
}

function rate_limit_tick($ip, $email){

	// Create a new record in the login attempt table

	$login_attempt = ORM::for_table('login_attempt')->create();

	$login_attempt->email = $email;
	$login_attempt->ip = sprintf("%u", ip2long($ip));

	$login_attempt->save();
}


function remove($userID) {
	$user = ORM::for_table('users')
		->where('id',$userID)
		->find_one();
	$user->delete();
	redirect("admin.php");
}


function toAdmin($userID) {
	$user = ORM::for_table('users')
		->where('id',$userID)
		->find_one();

	if ($user->rank == 0 || $user->rank == 3) {
		$user->rank = 2;
	} else if ($user->rank == 1) {
		$user->rank = 3;
	}
	$user->save();
	redirect("admin.php");
}


function toTreasurer($userID) {
	$user = ORM::for_table('users')
		->where('id',$userID)
		->find_one();

	if ($user->rank == 0 || $user->rank == 3) {
		$user->rank = 1;
	} else if ($user->rank == 2) {
		$user->rank = 3;
	}
	$user->save();
	redirect("admin.php");
}


function toRegular($userID) {
	$user = ORM::for_table('users')
		->where('id',$userID)
		->find_one();
	$user->rank = 0;
	$user->save();
	redirect("admin.php");
}


function redirect($url){
	header("Location: $url");
	exit;
}
