<?php 

	require "files/header.php";
	
	if($_SESSION["in"] == FALSE) {
  	  	echo("You are not logged in! Click <a href=\"login.php\">here</a> to log in, or <a href=\"signup.php\">here</a> to open up an account."); 
   }  
   
// Connect to database

		// connect to database
		if (($connection = mysql_connect(HOST, USER, PASS)) === FALSE)
			die("Could not connect to database");
		
		// select database
		if (mysql_select_db(DB, $connection) === FALSE)
			die("Could not select database");
	

// Get info from database

$user = $_SESSION["id"];
	
	if ($_POST["delete"] == TRUE)
	{
		//echo"<br />";
		//var_dump($_POST);
		
		// check if pw matches
		
		$pass = mysql_real_escape_string($_POST["pass"]);	
		
		$query = "SELECT 1 FROM `users` WHERE id = '$user' AND pass_enc = AES_ENCRYPT('$pass', '$pass') AND active = 1"; 	
		//echo($query);
		$result = mysql_query($query);
		
		$n = mysql_num_rows($result);
		//echo($n);
		
		if($n == 1) {
			
		//prepare SQL query
		$delete = "UPDATE `users` SET `active`= 0 WHERE id = $user";

		// execute delete
		mysql_query($delete);
	
			// log out user
			setcookie("user", "", time() - 3600);
			setcookie("pass", "", time() - 3600);

			setcookie(session_name(), "", time() - 3600);

			// redirect user to home page
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "index.php";
			header("Location: http://$host$path$site");
			session_destroy();
			exit;	
		}	
		else {
			$err = "Password incorrect";
			}
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>User Administration</title>
 </head>
 <body>
<?php require "/files/nav.php"; ?>

<div class="columnleft">
<h1>Delete your account</h1>

<?php if(isset($err)) { ?>
<div class="alerttext">
<?php echo($err); ?>
</div>
<?php } ?>

<div class="form">
Enter your password to delete your account. This is permanent and cannot be undone!
<br /><br />
<FORM METHOD="POST" action="<?php $_SERVER['PHP_SELF'] ?>" > 
Pasword: <INPUT type="password" name="pass">
<INPUT name="delete" TYPE="submit" VALUE="Yes, delete!">
</FORM>
</div>
</div>

<div class="columnright"> 
	<div class="ads">Ads go here</div>
</div>

<div class="footer">
<?php include "files/footer.php"; ?></div>
</body>
