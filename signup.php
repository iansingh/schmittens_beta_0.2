<?php
	session_start();
	require "files/functions.php";	
	require "files/include.php";	

	// MySQL login data
	// done in header.php
	// require "files/include.php";
	// require "files/header.php";
	
	// enable sessions - done in header.php
	// session_start();



	if (isset($_POST['user']) && isset($_POST['pass1']) && isset($_POST['pass2']) && isset($_POST['mail']))
	{
   // connect to db
		if (($connection = mysql_connect(HOST, USER, PASS)) === FALSE)
			die("Could not connect to database");
		
   // select database
		if (mysql_select_db(DB, $connection) === FALSE)
			die("Could not select database");
	
   // prepare variables
      $user = mysql_real_escape_string($_POST['user']);
      $pass1 = mysql_real_escape_string($_POST['pass1']);
      $pass2 = mysql_real_escape_string($_POST['pass2']);      
      $m = mysql_real_escape_string($_POST['mail']);
      preg_match('/[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}/',$m,$match);
      $mail = $match[0];

      $tou = $_POST['tou'];
      
   // check if username already exists
   	// prepare query
   	$namecheck = "SELECT 1 FROM `users` WHERE `user` = '$user'";
   	
   	// execute namecheck
   	$namecheckresult = mysql_query($namecheck);
   	
   	// echo($namecheckresult); 
   	
   		if (mysql_num_rows($namecheckresult) == 1)
   		{
   			$userexists = "This username already exists. Please chose another one.";
   			unset($user);
   			}
   		if($user == FALSE)
   		{
   			$username = "Username is missing";
   			unset($user);
   		}	
   		if ($pass1 == NULL) 
   		{
   			$pwempty1 = "The password field cannot be empty";
   			unset($pass1);
				}
   		if ($pass2 == NULL) 
   		{
   			$pwempty2 = "The repeat password field cannot be empty";
   			unset($pass2);
				}				   
			if ($pass1 != $pass2)
				{
				$pwmatch = "The passwords must match";
				unset($pass1);
				unset($pass2);
				}   
			if ($mail == NULL)
				{
				$mailempty = "Please enter a valid mail address";
				unset($mail);
				}		
   		if($tou == NULL)
   			{
   			$toumiss = "Terms of Use not accepted";		
   			}
   			
   		if(isset($user) && isset($pass1) && isset($pass2) && isset($mail) && isset($tou)) {
		
   	$clear = 1;
   // prepare query
		$insert = "INSERT INTO `users` (user, pass_enc, mail) VALUES ('$user', aes_encrypt('$pass1','$pass1'), '$mail')";
  		//echo"insert done";
   
   // execute insert
   	$result = mysql_query($insert);
   
   // validate insert & login
   	
		// prepare login query
		$loginquery = "SELECT * FROM users WHERE user = '$user' AND pass_enc = aes_encrypt('$pass1','$pass1')"; 
		
		// execute login query   	
		$loginresult = mysql_query($loginquery);
		if ($loginresult === FALSE)
			die("<br/> <br/> Could not query database");   	
			
		// check if row was found
		if (mysql_num_rows($loginresult) == 1)
		{	
			// remember that user is logged in
			$_SESSION["in"] = TRUE;
			
			$result_array = mysql_fetch_assoc($loginresult);

			$_SESSION["user"] = $result_array["user"];
			$_SESSION["id"] = $result_array["id"];
			$_SESSION["verification"] = $result_array["verification"];
			
			// redirect user to home page
			
			$host = $_SERVER["HTTP_HOST"];
			$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
			$site = "useradmin.php";
			header("Location: http://$host$path$site");
			exit;
			
		}
		else { echo"login failed"; }
   	}
    }
	require "files/nav.php";

	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Sign up</title>
  </head>
  <body>

<div class="columnleft">


  
  <?php 
  
       // echo($mail);
  
  if(($clear != 1) && ($_POST['su'] == 1)) { ?>
  	
  	<div class="alerttext">
  	<h1>Attention!</h1>
	<p>Some required information is missing:</p>
	<?php echo($username); if(isset($username) == TRUE) { echo"<br />"; } ?>
	<?php echo($userexists); if(isset($userexists) == TRUE) { echo"<br />"; } ?>
	<?php echo($pwempty1); if(isset($pwempty1)) { echo"<br />"; } ?>
	<?php echo($pwempty2); if(isset($pwempty2)) { echo"<br />"; } ?>
	<?php echo($pwmatch); if(isset($pwmatch)) { echo"<br />"; } ?>
	<?php echo($mailempty); if(isset($mailempty)) { echo"<br />"; } ?>
	<?php echo($toumiss);  ?>
  	</div>

<?php 
  	}
  
  if ($_SESSION["in"] == TRUE)  
  	echo("You are already logged in! Click <a href=\"index.php\">here!</a> to go back to start page.");
  
   else { ?>
   <h1>Sign up</h1>  
  
<div class="form">    
 
  	<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
  	<input type="hidden" name="su" value="1">
		<table>
			<tr>
				<td>Username:</td>
				<td>
					<input name="user" type="text" value="<?php echo($user); ?>" />		
				</td>			
			</tr>		
			<tr>
				<td>Password:</td>
				<td>
					<input name="pass1" type="password"  />		
				</td>	
			</tr>	
			<tr>
				<td>Repeat Password:</td>
				<td>
					<input name="pass2" type="password" />		
				</td>	
			</tr>	
			<tr>
				<td>E-Mail:</td>
				<td>
					<input name="mail" type="mail" value="<?php echo($mail); ?>" />		
				</td>	
			</tr>	
			<tr><td>I accept the <a href="tou.php" target="_blank">terms of use</a>: </td><td><input type="checkbox" name="tou" value="1" /></td></tr>
			<tr>
				<td></td>
				<td>
					<input type="submit" value="Sign Up" />		
				</td>			
			</tr>			

		</table>  	
					
  	</form>
</div>
  	<?php } ?>

</div>

<div class="columnright"></div>
  	
<div class="footer">
<?php include"files/footer.php";  ?>
</div>
  </body>
</html>
