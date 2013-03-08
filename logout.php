<?php
//session_start();





// done in header.php
// session_start();

setcookie("user", "", time() - 3600);
setcookie("pass", "", time() - 3600);

setcookie(session_name(), "", time() - 3600);


// require "files/header.php";

			// redirect user to home page
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "index.php";
			header("Location: http://$host$path$site");
			session_destroy();
			exit;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Log Out</title>
  </head>
  <body>
    <h1>You are logged out!</h1>
    <p><a href="index.php">Back to Index</a></p>

<div class="footer">
<?php include"files/footer.php";  ?>
</div>
  </body>
</html>