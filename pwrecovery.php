<?php 

		require "files/header.php";
		//require "files/functions.php";
		//require "files/include.php";
		dbconnect();

if(isset($_POST['switch3'])) {

	
	$pw1 = mysql_real_escape_string($_POST['pw1']);
	$pw2 = mysql_real_escape_string($_POST['pw2']);
	
	$id = $_POST['id'];
	
	if(($pw1 == "") || ($pw2 == "")) {
		$error = "The password-fields cannot be empty!";
	}
	
	if($pw1 != $pw2) {
		$error = "The passwords do not match!";
	}
	else {		

	// change switch to 3
	unset($switch2);
	$switch3 = 1;
		
	$update = "UPDATE `users` SET `pass_enc` = AES_encrypt('$pw2', '$pw2'), `reset` = '', `expiry` = 0 WHERE `id` = '$id'";
	$pwupdate = mysql_query($update) or die('Could not write');
	
	$success = 1;
	}
	
}
		
if(isset($_GET['pwreset'])) {


	$ui = $_GET['pwreset'];
	$uu = "SELECT id,expiry FROM `users` WHERE reset = '$ui'";
	$uuq = mysql_query($uu);
	$uuq_a = mysql_fetch_assoc($uuq);


	
	// check if link has expired
	$expiry = $uuq_a['expiry'];
/*	
	$now = mktime();
	$expiry = mktime($expiry);
	$now2 = date('U', $now);
	$expiry2 = date('U', $expiry);
	
	echo($now);
	echo" > ";
	echo($expiry);
	echo"<br /> <br />";
	echo($now2);
	echo" > ";
	echo($expiry2);*/
	

	
	
	if((time()-(60*60*2)) > strtotime($expiry)) {
		$errorexp = "This link has expired.";
	}
	else {

	// switch output on website - enter newpassword
	unset($switch1);
	$switch2 = 1;	
	$id = $uuq_a['id'];
	}


}
		
if(isset($_POST['mail'])) {
	
		// check if mail is valid
	   $m = mysql_real_escape_string($_POST['mail']);
      preg_match('/[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}/',$m,$match);
      $mail = $match[0];
      // set error if mail is invalid
	if($mail == FALSE) {
			$error = "Please enter a valid e-mail!";
	}
	else {
		// check if mail is in db
		$u = "SELECT id,mail,user FROM `users` WHERE mail = '$mail'";
		$result = mysql_query($u);
		$ur = mysql_num_rows($result);
		if($ur != 1) {
			$error = "The address entered was not found in the database. Please doublecheck.";		
			}
		// if mail was in db continue
		else {
		// switch output on website - display message that mail was sent
		$switch1 = 1;
		$message = "A mail has been sent to your address. Click the link in the mail to reset your password.";
		// get rid of POST-data
		unset($_POST['mail']);
		
		$user_a = mysql_fetch_assoc($result);
		$mail = $user_a['mail'];
		$id = $user_a['id'];
		$user = $user_a['user'];
		// generate hash with existing password and username
		
		$salt = mktime();
		$salt = md5($salt);
		//echo($salt);
		$md5 = $salt.$user;
		$uhash = md5($md5);
		// create url
		$urlhost = $_SERVER["HTTP_HOST"];
		$urlpath = $_SERVER['PHP_SELF'];
		$get = "?pwreset=";
		$resetlink = $urlhost.$urlpath.$get.$uhash;		
		// optional: change userpw to disable access
		
		// write timestamp and hash to user-entry
		// create timestamp for expiry (+2 hours)
		$now = date_create();
  		$clone = clone $now;   
  		$clone->modify( '+2 hour' );
		//echo($clone->format( 'Y-m-d H:i:s' ));
		$expiry = $clone->format( 'Y-m-d H:i:s' );
		$up = "UPDATE `users` SET `reset`='$uhash',`expiry`='$expiry' WHERE id = '$id'";
		//echo($upd);
		$upq = mysql_query($up) or die('Could not update');
		
		// send out mail
		
		$subject = "Password-reset - www.schmittens.net"; 
		$message = "
Dear $user

Somebody (hopefully you!) has requested a password-reset for your account on www.schmittens.net. 
To update your password click the following link:
---------------------------- 
Link: $resetlink
---------------------------- 
Please note that the link is only valid for two hours!

If you did not request that password-reset we suggest that you log in to your account and change the password manually. 
If you did not request this password-reset please feel free to contact us at support@schmittens.net.

Thank you and have a great day!		
Schmittens

This email was automatically generated.
		"; 
                       
          if(!mail($mail, $subject, $message,  "FROM: Schmittens.net <support@schmittens.net>")){ 
            $mailfail = "<p>Sending email failed, please contact support@schmittens.net!</p>"; 
          }else{ 
          	$mailconfirm = "<p>Mail was sent successfully.</p>";
         } 
		
		
		
		
		}
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Log In</title>
  </head>
  <body>
  
<?php require "files/nav.php";?>

<div class="columnleft"> 

<?php 
if ($_SESSION["in"] == TRUE)  {
  	echo("You are already logged in! Click <a href=\"index.php\">here!</a> to go back to start page, or go to your <a href='useradmin.php'>useradmin-page</a> to change your current password."); 
  	} 
  
else { 

if(isset($error))  { ?>
<div class="alerttext">
<p><?php echo($error); ?></p>
</div>	
	
	<?php } 

if((isset($errorexp)) && (isset($success) == FALSE)) { ?>
<div class="alerttext">
<p><?php echo($errorexp); ?></p>
</div>	
	
	<?php } ?>
   

	<?php if((isset($switch1) == FALSE) && (isset($switch2) == FALSE) && (isset($success) == FALSE) && (isset($errorexp) == FALSE)) {   ?>
<div class="form">
<h1>Recover password</h1>
  	<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
		<table >
			<tr>
				<td>Enter your e-mail address:</td>
				<td>
					<input name="mail" type="text" size="20"/>		
				</td>		
			<tr>
				<td></td>
				<td>
					<input type="submit" value="Recover password" />		
				</td>			
			</tr>			
		</table>  	
  	</form>
</div>
<br />
<div class="hint">
<p>Once you hit "Recover password" you will receive an e-mail with a link. Click the link to enter a new password. Your current password will become invalid. Please note that the link will only be valid for 2 hours. </p>
</div>
<?php 
	}
	
	if(isset($mailconfirm) == TRUE) { ?>
			
	<div class="hint"><?php echo($mailconfirm); ?></div>

<?php
	}
	
	if(isset($mailfail) == TRUE) { ?>
			
	<div class="alerttext"><?php echo($mailfail); ?></div>

<?php
	}

if(($switch2 == 1) && (isset($success) == FALSE) && (isset($errorexp) == FALSE)) { //echo($_GET['pwreset']); g?>
<div class="form">
<h1>Recover password</h1>
  	<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
		<table >
			<tr>
				<td>Enter your new password:</td>
				<td>
					<input name="pw1" type="password" size="20"/>		
				</td>	
			</tr>	
			<tr>
				<td>Repeat your new password:</td>
				<td>
					<input name="pw2" type="password" size="20"/>		
				</td>	
			</tr>	
			<input type="hidden" name="switch3" value="1" />
			<input type="hidden" name="id" value="<?php echo($id);?>" />
			<tr>
				<td></td>
				<td>
					<input type="submit" value="Save" />		
				</td>			
			</tr>			
		</table>  	
  	</form>
</div>		
	

<?php
	}
	
	if($success == 1) { ?>
	<div class="hint">
	<?php echo"<p>Your password has been updated successfully. Click <a href='login.php'>here</a> to log in. </p>"; ?>
	</div>
	<?php
	}
	
} ?>

</div>
  
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
<?php // ob_flush(); ?>