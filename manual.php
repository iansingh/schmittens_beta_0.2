<?php 
	require "files/header.php";
?>

<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>Manual</title>
   	<link rel="stylesheet" href="style.css" media="screen" />

 </head>
 <body>

<h1>Manual</h1> 
<a name="top"></a>
<ul>
	<li><a href="#1">Overview - 3 steps to your event</a></li>


	
	<li><a href="#2">Locations</a></li>
		<ul>
		<li><a href="#20">Search for your location</a></li>
		<li><a href="#21">Creating a location</a></li>
		<li><a href="#22">Editing a location</a></li>	
		</ul>
		
	<li><a href="#3">Events</a></li>
		<ul>
			<li><a href="#31">Creating an event</a></li>
			<li><a href="#31">Editing an event</a></li>			
		</ul>
		
	<li><a href="#4">Advanced features</a></li>

</ul>
 
<h3><a name="1">Overview - 3 steps to your event</a></h3> 
	
	<p>To create your own events you will need to register an account and be logged in! After that you can create your events in three steps:</p>	
	
	<ol>
		<li><b>Find your location</b></li>
		<li><b>Create a location if it does not yet exist</b></li>	
		<li><b>Create your event</b></li>
	</ol>

<a href="#top"><i>Top of the page</i></a>

<h3><a name="2">Locations</a></h3>
<p>Locations are important! Therefore it is also important that you add as much information as possible. The more information there is, the more useful a location is to the users.</p>

		<li><a name="20">Search for your location</a></li>
		<p>Go to "Locations" on the Nav-bar above the site and browse for your location. Alternatively you can use the search-field and enter the locations name.</p>
		<li><a name="21">Creating a location</a></li>
		<p>If you can't find your location it probably does not yet exist in our database. There is a button labeled "Create location" in the top-right corner of the "Locations"-Page (only visible when you are logged in) - click that and you can create your location.</p>
		<p>Next you will see the location creation screen. Fill out the necessary and - if possible - the additional fields. A quick overview of what the fields do:</p>
			<ul>
				<li><b>Location name</b> - required, the official name of the location</li>			
				<li><b>Street name</b> - required, name of the street, without number</li>	
				<li><b>Street number</b> - optional, just the number</li>						
				<li><b>Postal code</b> - required, in the format A1B 2C3</li>						
				<li><b>City</b> required</li>										
				<li><b>Province</b> - required, select from the list</li>
				<li><b>Website</b> - optional, use format http://www.location.com</li>																												
				<li><b>Mail</b> - optional, official public contact address</li>															
				<li><b>Type</b> - required, main type of event that takes place in this location</li>
				<li><b>Facebook</b> - optional, link to the facebook-page of the location</li>															
				<li><b>Twitter</b> - optional, link to the location's twitter-feed</li>
			</ul>		
		<p>After you entered all the information click "Submit". "Reset" empties all the fields on the page, so be careful!</p>
		
		<li><a name="22">Editing a location</a></li>
		<p>After you submit the information the location is created and you are redirected to the location's public page. If you are logged in and have created a location you will see a button "Edit location" in the top left corner. Click to edit your location.</p>
		<p>You can now upload a picture for the location. The pictures are automatically resized, but a 16:9 aspect ratio provides the best results. At this point only jpgs smaller than 1MB can be uploaded.</p>
		<p>It is also possible to change the location's information in this screen. Changes take effect immediately.</p>

<a href="#top"><i>Top of the page</i></a>

<h3><a name="3">Events</a></h3>
<p>Events are important too! And what is true for locations is also true for events: The more information you add, the more useful it is for other users.</p>
<p>Events are created from the hosting location's screen. If you're logged in you will see a button labeled "Create Event" in the top right of the page.</p>

			<li><a name="31">Creating an event</a></li> 
			<p>The first thing you will see when you click "Create event" is a list of existing events in this location. Make sure that your event does not yet exist before proceeding!</p>
			<p>Fill out the necessary information. Remember - the more the better! Here is a quick explanation of what's what:</p>
			<ul>
				<li><b>Title</b> - required, the event's official name. Keep as short as possible. eg if you have several bands for a concert only put the headliner.</li>			
				<li><b>Date</b> - required, when the event takes place</li>	
				<li><b>Start</b> - required, the time the event starts</li>						
				<li><b>End</b> - optional, time the event ends (only gets saved if "set endtime" is set)</li>						
				<li><b>Artists</b> - optional, a list of performers, seperated by "," (comma)</li>										
				<li><b>Description</b> - optional, verbose description of artists or the event</li>
				<li><b>Price (min)</b> - optional, the minimum ticket price. Use this if there is only one price</li>																												
				<li><b>Price (max)</b> - optional, allows you to set a price range</li>															
				<li><b>Free-Checkbox</b> - optional, marks the event as free (no entry fee)</li>
				<li><b>Type</b> - required, defines what kind of event it is</li>															
				<li><b>Link</b> - optional, custom link, eg to a performer's or location's website</li>
				<li><b>Ticketlink</b> - optional, use if you sell tickets online. NOTE: point to the specified event, if possible</li>
			</ul>					
			
			<li><a name="32">Editing an event</a></li> 
			<p>After you save the event you are redirected to the public event-page. If you are logged in you will see a button labeled "Edit event" on the bottom of the page. NOTE: This is true for all your events! Whenever one of your events is displayed when you are logged in, you will be able to edit them.</p>
			<p>Once you click edit you will be able to upload a picture and edit the event information. Again, only jpgs smaller than 1MB are allowed, and you will get the best results with pictures in the 16:9 format.</p>			

<a href="#top"><i>Top of the page</i></a>
			
<h3><a name="4">Advanced features</a></h3>
<p>This will be updated once these exist...</p>

<a href="#top"><i>Top of the page</i></a>

<?php
include "files/footer.php";
?>
 </body>
</html>