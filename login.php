<?php session_start();

		require "files/functions.php";
		require "files/include.php";

//echo($_POST['user']);
//echo($_POST['pass']);

if($_POST['check'] == 1) {
$user = mysql_real_escape_string($_POST['user']);
$pass = mysql_real_escape_string($_POST['pass']);

		//require "files/header.php";
		dbconnect();

               
      $user2 = mysql_real_escape_string($_POST['user']);
		$pass2 = mysql_real_escape_string($_POST['pass']);
      
      
		
      // prepare query
		$query = "SELECT * FROM users WHERE user = '$user2' AND pass_enc = AES_ENCRYPT('$pass2', '$pass2') AND active = 1"; 
		//echo($query);

			
		$result = mysql_query($query);
		/*if ($result === FALSE)
			die("<br/> <br/> Could not query database");
		*/
			
		// check if a row was found
		
		if (mysql_num_rows($result) == 1)
		{
			

			// remember that user is logged in
			$_SESSION["in"] = TRUE;
			
		// retrieve and store user information

			$result_array = mysql_fetch_assoc($result);


			
			$_SESSION["user"] = $result_array["user"];
			$_SESSION["id"] = $result_array["id"];
			$_SESSION["verification"] = $result_array["verification"];
			$_SESSION["status"] = $result_array["status"];
			
			// redirect user to home page
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "useradmin.php";
			//echo($host.$path.$site);
			header("Location: http://$host$path$site");
			exit;			
			// header("Location: $_SERVER['HTTP_REFERER']");

		}
		else 
		{
		$error = "Username and Password did not match, or this user does not exist. Please try again.";	
		}

	
}


?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Log In</title>
  </head>
  <body>
  
<?php include "files/nav.php"; ?>

<div class="columnleft">  

<?php 

if ($_SESSION["in"] == TRUE)  { echo("You are already logged in! Click <a href=\"index.php\">here</a> to go back to start page."); } 
  
  else { ?>  

<?php 
if (isset($error)) { ?>
<div class="alerttext"> 
<h1>Attention!</h1>
<?php 
	echo($error);?>
</div>	
<?php } ?>

<div class="form">
  <h1>Log in</h1>
  	<form action="login.php" method="post">
		<table >
			<tr>
				<td>Username:</td>
				<td>
					<input name="user" type="text" size="12" />		
				</td>			
			</tr>		
			<tr>
				<td>Password:</td>
				<td>
					<input name="pass" type="password" size="12"/>		
				</td>		
			<tr>
				<td></td>
				<td>
					<input type="submit" value="Log in" />	
					<input type="hidden" name="check" value="1" />	
				</td>			
			</tr>			
		</table>  	
  	</form>
</div>

<br />
<div class="hint">
<p>Don't have an account? Sign up <a href="/signup.php">here</a>!</p>
<p>Forgot your password? Click <a href="/pwrecovery.php">here</a>!</p>
</div>


</div>


  <?php  } ?>
  
<div class="columnright">
	<div class="ads">
		<p>Ads go here</p>	
	</div>
</div>  	
<div class="footer">
<?php include"files/footer.php";  ?>
</div>
  </body>
</html>
<?php ?>
<?php // ob_flush(); ?>
