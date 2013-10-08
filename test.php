<?php
// user these pieces of codes to detect an user type

require_once 'includes/config.php';

$user = new User();

if ($user->loggedIn()) { // if any user is logged in
  echo "Logged in<br/>";
}

if ($user->loggedIn() && $user->isAdmin()) { // if the logged in user is admin
  echo "Admin<br/>";
}

if ($user->loggedIn() && $user->isTreasurer()) { // if the logged in user is treasurer
  echo "Treasurer<br/>";
}

if ($user->loggedIn() && $user->isBoth()) { // if the logged in user is admin and treasurer
  echo "Both<br/>";
}

if ($user->loggedIn() && $user->isRegular()) { // if the logged in user is normal user
  echo "Regular<br/>";
}


?>