<!-- Google analytics tracking code -->

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-38994746-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>


<div class="nav">
<!-- <div class="header"> -->
<!-- <div class="menu"> --> 
 <ul id="nav" >
 	<li style="margin-left:-35px;	font-weight:bold; "><a href="/index.php" >schmittens.&beta;</a>
 	</li> 	
 	<li><a href="/eventlist.php" >Listings</a>
 		<ul>
 			<li><a href="/eventlist.php" >All</a></li>
 			<li><a href="/eventlist.php?eventtype=1&time=1&list=List+events">Party</a></li>
 			<li><a href="/eventlist.php?eventtype=2&time=1&list=List+events">Concert</a></li>
 			<li><a href="/eventlist.php?eventtype=3&time=1&list=List+events">Stage</a></li>
 			<li><a href="/eventlist.php?eventtype=4&time=1&list=List+events">Art</a></li>
 			<li><a href="/eventlist.php?eventtype=5&time=1&list=List+events">Other</a></li>
 			<!--<li> </li>
 			<li> </li>
 			<li><a href="/locationlist.php" >Locations</a></li> -->
 		</ul>
 	</li>
 	<?php if($_SESSION["in"] == TRUE) { ?>
 	<li><a href="/create_event.php">Create</a>
		<!--
		<ul>
			<li><a href="/create_event.php" >Event</a></li>
			<li><a href="/create_location.php" >Location</a></li>
		</ul> 
		-->	
 	</li> <?php } ?>
	<li><a href="/about.php" >FAQ</a></li>	
	<?php if($_SESSION["in"] == TRUE) { echo
	"<li><a href='useradmin.php'>Account</a></li>"; } ?>
	<?php if(($_SESSION['in']) == FALSE) { echo
	"<li><a href='/login.php' >Login/Sign up</a></li>"; }
	else { echo
 	"<li><a href='/logout.php' >Logout</a></li>"; } ?>	
 	<?php if($_SESSION['status'] >= 5) { echo
 	"<li><a href='/bo/index.php' target='_blank' >BO</a></li>"; } ?>	

 		<form action="search.php" method="post" style="padding-top: 8px; margin-left: 500px;">
		<input type="text" name="searchfield" value="<?php if (isset($_POST['searchfield'])) { echo $searchfield; } ?>" size="12" />
		<input type="submit" value="Search">
		</form> 	
 </ul> 
</div>

