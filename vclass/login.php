<?php 
	session_start();
	require "../vclassFiles/includes/functions.php";
	$vclass = new Vclass();
	$vclass->checkIfLoggedIn();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
  <title>Trendstones | VirtualClass</title>
	<?php include "../vclassFiles/includes/scripts.php"; ?>
	<link rel="stylesheet" type="text/css" href="css/signin.css"/>
</head>
<body>

	<div class="container">
       <form class="form-signin form well br" method="post" action="#" id="signin1">
           <center><a href="../index.html" data-placement="bottom" data-toggle="tooltip" title="Go to Home-page"><img src="images/logo.png" style="width: 120px; height: 100px;" /></a></center>
          <div id="displayRes" style="text-align: center;"></div>
          <h2 class="form-signin-heading"><center>Virtual-Classroom</center></h2>
          <label for="username" class="sr-only">User Name</label>
          <input type="text" id="username" name="username" class="form-control" placeholder="User Name" required autofocus>
          <label for="password" class="sr-only">Password</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
          <button class="btn btn-lg btn-primary btn-block br" name="loginBtn" type="submit" data-placement="bottom" data-toggle="tooltip" title="SignIn as Student"><span class="glyphicon glyphicon-log-in"></span> Sign in</button>
      </form>
    </div> <!-- /container -->
<?php
  if(isset($_POST['loginBtn'])){
    $vclass->verifyStudent($_POST['username'],$_POST['password']);
  }
?>
</body>
</html>