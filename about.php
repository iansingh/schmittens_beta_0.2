<?php
	
	// done in header.php
	// session_start();
	
	require "files/header.php";
	
	// done in header.php	
	// require "files/include.php";	
	
	//prepare eventtype variables

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>FAQ</title>
   	<link rel="stylesheet" href="style.css" media="screen" />

 </head>
<body>
<?php include "files/nav.php"?>

<div class="columnleft">

<h1>FAQ</h1>

<ul>
	<li><a href="#1">So what is schmittens?</a></li>
	
	<li><a href="#2">Is there a manual or something?</a></li>
		<ul>
		<li><a href="#20">How can I create my own events?</a></li>
		<li><a href="#21">The location my event takes place in does not exist!</a></li>
		<li><a href="#22">What kind of events?</a></li>
		<li><a href="#23">The event I wanted to create already exists.</a></li>
		<li><a href="#24">There is a mistake in an event, but I can't correct it because I did not create the event.</a></li>	
		<li><a href="#25">How can I upload pictures?</a></li>			
		</ul>
	<li><a href="#3">My venue has its own website - why should I bother?</a></li>
		<ul>
			<li><a href="#31">I don't want others to create events in my venue, what can I do?</a></li>
			<li><a href="#31">Somebody else already created my venue, what can I do?</a></li>			
			<li><a href="#32">Ok, I like what you do. Is there any way you can import my events?</a></li>
		</ul>
	<li><a href="#4">So is this all free?</a></li>
	<li><a href="#5">Sounds good, how can I help?</a></li>
	<li><a href="#6">What features will schmittens have?</a></li>
	<li><a href="#7">Est-ce qu'il y a une version française?</a></li>

</ul>

<h3><a name="1">So what is schmittens?</a></h3>
<p>Well, first of all schmittens is a public portal for advertising your events. You can create your own events, and see ones that other people created.</p>
<p>Second, schmittens is aiming to be a community for both organizers and visitors of all kinds of events and wants to bring the both together - to provide feedback, ideas, encouragement and whatnot.</p>
<p>Third, schmittens is a work in progress, and new features are being added all the time.</p>
<p>Last but not least, Schmittens is a rather fat cat.</p>

<h3><a name="2">Is there a manual or something?</a></h3>
<p>Yes, there is. After you log in, just go to "My account" and click the link. Alternatively, click here: <a href="" >Manual</a>. You need an account to access the manual.</p> 
<p>Some specific questions are discussed here, so read on!</p>


	<h4><a name="20">How can I create my own events?</a></h4>
	<p>Very easy! First you create a user account and log in (only registered users can create and modify events).</p>
	<p>Then you look for the location the event takes place in. If it does not exist you can create it.</p>
	<p>After you picked (or created) your location you (and every other registered user) can create events in that location. Just fill in all necessary and relevant information. <b>More is better!</b> The more information you provide the more useful it is to everyone.</p>

	<h4><a name="21">The location my event takes place in does not exist!</a></h4>
	<p>No problem! If you are a registered user you can create locations. You can also edit and change locations you created yourself.</p>

	<h4><a name="22">What kind of events?</a></h4>
	<p>Pretty much any kind! However, there are a few things to keep in mind:</p>
	<ul>
		<li><b>Events must be public!</b> If you don't want complete strangers to show up at your event - don't put it here!</li>	
		<li>While political events are ok, <b>we do not allow hateful, discrimenating, racist or otherwise amoral and/or criminal events!</b> Fun and information good, hate and crime bad. Easy.</li>	
	</ul>

	<h4><a name="23">The event I wanted to create already exists.</a></h4>
	<p>Good for you! Less work this way!</p>

	<h4><a name="24">But there is a mistake in an event, but I can't correct it because I did not create the event.</a></h4>
	<p>Ok, that's not so good. For the moment, drop us an email, and we'll look into it. For the future we plan on integrating a review- and notification-system.</p>	

	<h4><a name="25">How can I upload pictures?</a></h4>
	<p>It works the same way both for events and locations: After you create your event or location, click edit. There is a dialogue on the top of the page. Please not that at this point only .jpg files smaller than 1MB can be uploaded.</p>	


<h3><a name="3">My venue has its own website - why should I bother?</a></h3>
<p>Well, not everybody checks out your website. And people are more likely to regularly visit a place where they can see as many events as easily as possible. So schmittens can be a good place to meet new audiences and advertise your events.</p>

	<h4><a name="31">I don't want others to create events in my venue, what can I do?</a></h4>
	<p>We are working on giving users and organizers more control, but for the moment your best bet is to keep your venue up to date so nobody feels the need to add their own events.</p>
	
	<h4><a name="32">Somebody else already created my venue, what can I do?</a></h4>	
	<p>For now send us an email from an address that is officially affiliated with the location. We will then assign the location to you. You can then edit and modify it.</p>

	<h4><a name="33">Ok, I like what you do. Is there any way you can import my events?</a></h4>	
	<p>Glad you like it! If you have your events already on the net and use a CMS system or RSS feed - drop us a line! We can set up an automated import of your data.</p>

<h3><a name="4">So is this all free?</a></h3>
<p>Crazy, isn't it? We will never charge anyone for the basic use of the service, so everyone can enter their events for zilch. There might be paid premium services in the future, but they will not interfere with the free service.</p>

<h3><a name="5">Sounds good, how can I help?</a></h3>
<p>Oh boy, how *can't* you help? Let's see what we got...</p>
	<ul>
		<li><b>Every event helps!</b> So please enter your events with as much information as possible.</li>	
		<li><b>Spread the word!</b> Tell your friends, twitter about us, like us on facebook. Thanks!</li>
		<li><b>Join us!</b> If you are a PHP, Java/jQuery or HTML/CSS-person, if you know Bootstrap and jQuery, or if you want to help in any other way - we would love to have you on the team!</li>	
	</ul>

<h3><a name="6">What features will schmittens have?</a></h3>
<p>We're still at the very beginning, but have some ideas for the near and not so near future:</p>
	<ul>
		<li><b>Facebook-integration</b> - Login with your facebook-account, and publish events from schmittens on fb</li>	
		<li><b>Discussion-tools</b> - comment on events both past and future, share impressions and media</li>
		<li><b>User-experience customization</b> - nevermind the buzzwords, but we want you to be able to customize what kind of events you are interested in and filter search results by your preferences</li>
		<li><b>XML-export of your events</b> - use the events you created on schmittens on your own page - this one is for the organizers!</li>
		<li>... and many more</li>
	</ul>
<p>If you have ideas for features you want to see on schmittens - please tell us on support[at]schmittens.net]!</p>

<h3><a name="7">Est-ce qu'il y a une version française?</a></h3>
<p>Pas encore, malheureusement! And by the awful french you can probably tell why... If you want to help with the translation, please drop us a line!</p>
</div>



<div class="columnright">
Back to <a href="useradmin.php">my account</a>.
	<div class="ads"></div>
</div>
<div class="footer">
<?php include"files/footer.php";  ?>
</div>
</body>

</html>