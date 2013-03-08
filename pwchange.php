<?php 

	// MySQL login data
	// done in header.php
	// require "files/include.php";
	require "files/header.php";
	
	// enable sessions
	// done in header.php	
	// session_start();

if(($_SESSION['in']) != TRUE) {
	// redirect user to home page
				
	$host = $_SERVER["HTTP_HOST"];
	$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
	$site = "index.php";
	header("Location: http://$host$path$site");
	exit;
	}


// connect to db
	if (($connection = mysql_connect(HOST, USER, PASS)) === FALSE)
			die("Could not connect to database");
		
// select database
	if (mysql_select_db(DB, $connection) === FALSE)
			die("Could not select database");
			
// get password from db
	$user = $_SESSION["user"];

	$query = "SELECT `pass` FROM `users` WHERE `user` = '$user'";

	$result = mysql_query($query) or die ("no query");


	$result_array = mysql_fetch_assoc($result);

	
	// extract info from query
	extract($result_array);
	$sessionuser = $_SESSION['id'];
	
	// check if data was submitted
	if (isset($_POST["oldpassword"]) && isset($_POST["newpassword1"]) && isset($_POST["newpassword2"]))
	{
		
	// prepare variables
	
      $oldpassword = mysql_real_escape_string($_POST["oldpassword"]);
      $newpassword1 = mysql_real_escape_string($_POST["newpassword1"]);
      $newpassword2 = mysql_real_escape_string($_POST["newpassword2"]);
 		
		
   // check if old password matches db entry
   $checkquery1 = "SELECT 1 FROM users WHERE pass_enc = AES_encrypt('$oldpassword', '$oldpassword') AND id = $sessionuser";
   $checkquery1res = mysql_query($checkquery1) or die ("Password did not match the DB");
   $checkquery1res_array = mysql_fetch_assoc($checkquery1res);

   
	$s = 0;   
   
		if ($checkquery1res_array['1'] != 1)
		{
			$err_old = "Your old password does not match the DB-entry";
			$s = $s + 1;
		}
		
			// check if old and new passwords are identical
		if (($oldpassword == $newpassword2) && ($oldpassword == $newpassword1))				
		{
			$err_same = "Your old and your new password can not be identical";
			$s = $s + 1;
		}		
	
		// check if new passwords match
		if ($newpassword1 != $newpassword2)
		{
				$err_match = "Your new passwords don't match";
				$s = $s + 1;
		}
		
		// check if new password is set
		if ($newpassword1 == "")
		{
				$err_np = "Password can't be blank";
				$s = $s + 1;
		}

			
			if($s == 0) {
					// prepare query
					$update = "UPDATE `users` SET `pass_enc` = AES_encrypt('$newpassword2', '$newpassword2') WHERE `user` = '$user'";

					
					// execute insert
					mysql_query($update);
	
	$_SESSION['pwupdate'] = "1";	
	
	// forward user to useradmin-page
	$host = $_SERVER["HTTP_HOST"];
	$path = rtrim(dirname($_SERVER["PHP_SELF"]). "");
	$site = "useradmin.php";
	header("Location: http://$host$path$site");
	exit;	}
}



// demand 1x old password, 2x new password in a form

// compare passwords
	// old password has to match db entry
		// if not: prompt again
	// new passwords have to match eatch other
		// if not: prompt again
	
// write new password into db
	
// redirect to index
	
	
?>

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Change Password</title>
  </head>
  <body>
  
<div class="columnleft">
   <h1>Change Password</h1>  

<?php if($s > 0) { ?>
<div class="alerttext">
<h1>Attention!</h1>
<?php 
if(isset($err_old)) { echo($err_old); echo"<br />"; }
if(isset($err_same)) { echo($err_same); echo"<br />"; }
if(isset($err_np)) { echo($err_np); echo"<br />"; }
if(isset($err_match)) { echo($err_match); }
?>
</div>
<?php } ?>

<div class="form">  

  <?php 
// check if logged in
 	if ($_SESSION["in"] == FALSE)
  	echo("You are not logged in! Click <a href=\"login.php\">here!</a> to log in.");
 
  ?>
 
 
		<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
			<table>
				<tr>
					<td>Enter old password:</td>
					<td <?php if(isset($err_old)) { echo"class='required'"; } ?> ><input name="oldpassword" type="password" value="" /></td>		
				</tr>
				<tr>
					<td>Enter new password:</td>
					<td <?php if(isset($err_same)) { echo"class='required'"; } ?> <?php if(isset($err_match)) { echo"class='required'"; } ?> <?php if(isset($err_np)) { echo"class='required'"; } ?> ><input name="newpassword1" type="password" value="" /></td>		
				</tr>
				<tr>
					<td>Repeat new password:</td>
					<td <?php if(isset($err_same)) { echo"class='required'"; } ?> <?php if(isset($err_match)) { echo"class='required'"; } ?> <?php if(isset($err_np)) { echo"class='required'"; } ?> ><input name="newpassword2" type="password" value="" /></td>		
				</tr>
				<tr>
					<td></td>			
					<td><input name="submit" type="submit" value="Submit" /></td>		
				</tr>
			</table>
		</form> 


  
</div></div>

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