<?php

require_once 'includes/config.php';

$currentUser = new User(); // current logged in user

if (!$currentUser->loggedIn()) {
  redirect('index.php');
}

if ($currentUser->rank() != 'admin' && $currentUser->rank() != 'admin, treasurer') {
  redirect('search.php');
}

$users = ORM::for_table('users')->find_many(); // list of all users in the databse
$ranks = array (
    "Regular",
    "Treasurer",
    "Admin",
    "Admin, Threasurer"
  );

if (isset($_GET['remove'])) {
  remove($_GET['remove']);
} else if (isset($_GET['toAdmin'])) {
  toAdmin($_GET['toAdmin']);
} else if (isset($_GET['toTreasurer'])) {
  toTreasurer($_GET['toTreasurer']);
} else if (isset($_GET['toRegular'])) {
  toRegular($_GET['toRegular']);
}

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <title>Ledger Admin</title>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-theme.min.css">
    <!--[if lt IE 9]>
      <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
  </head>

  <body>
    <div class="container col-md-12">
      <div class="col-md-1"></div>
      <div class="col-md-10">
        <h1>Admin</h1>
        <form method="get" action="admin.php">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th>Email</th>
                <th>Current Privileges</th>
                <th>Actions</th>
              </tr>
            </thead>
            <?php foreach ($users as $user): ?>
              <tr>
                <td><?=$user->email?></td>
                <td><?=$ranks[$user->rank]?></td>
                <td>
                  <button type="submit" class="btn btn-default btn-xs" name="toAdmin" value="<?=$user->id?>">To Admin</button>
                  <button type="submit" class="btn btn-default btn-xs" name="toTreasurer" value="<?=$user->id?>">To Treasurer</button>
                  <button type="submit" class="btn btn-default btn-xs" name="toRegular" value="<?=$user->id?>">To Regular</button>
                  <?php if($user->rank != 2 && $user->rank != 3): ?>
                  <button type="submit" class="btn btn-default btn-xs" name="remove" value="<?=$user->id?>">Delete User</button>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </table>
          <a href="search.php" class="pull-left">&#60&#60 Back to Ledger</a>
          <a href="index.php?logout=1" class="pull-right">Logout &#62&#62</a>
        </form>
      </div>
      <div class="col-md-1"></div>
    </div>

    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src='js/script.js'></script>
  </body>
</html>