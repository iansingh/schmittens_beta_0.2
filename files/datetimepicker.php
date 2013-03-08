<?php function drawDateTimePicker_start() { ?>
	<select name="month_s">
		<?php for($x=1;$x<=12;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("n") == $x ? ' selected="selected"' : '') ?>><?= date('F', mktime(0,0,0,$x,1)) ?></option>	
		<?php } ?>
	</select>
	<select name="day_s">
		<?php for($x=1;$x<=31;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("j") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>
	</select>
	<select name="year_s">
		<?php for($x=2010;$x<=2015;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("Y") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>		
	</select> / 
	<select name="hour_s">
		<?php for($x=0;$x<=23;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("H") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>
	</select>	
	<select name="minute_s">
		<option value="00">00</option>
		<option value="15">15</option>
		<option value="30">30</option>
		<option value="45">45</option>
	</select>
	<?php 

 } 

function drawDateTimePicker_date() { ?>
	<select name="month_s">
		<?php for($x=1;$x<=12;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("n") == $x ? ' selected="selected"' : '') ?>><?= date('F', mktime(0,0,0,$x,1)) ?></option>	
		<?php } ?>
	</select>
	<select name="day_s">
		<?php for($x=1;$x<=31;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("j") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>
	</select>
	<select name="year_s">
		<?php for($x=2010;$x<=2015;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("Y") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>		
	</select> 
<?php } 


function drawDateTimePicker_start_short() { ?>
	<select name="hour_s">
		<?php for($x=0;$x<=23;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("H") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>
	</select>	
	<select name="minute_s">
		<option value="00">00</option>
		<option value="15">15</option>
		<option value="30">30</option>
		<option value="45">45</option>
	</select>
	<?php } 

function drawDateTimePicker_end() { ?>
	<select name="month_e">
		<?php for($x=1;$x<=12;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("n") == $x ? ' selected="selected"' : '') ?>><?= date('F', mktime(0,0,0,$x,1)) ?></option>	
		<?php } ?>
	</select>
	<select name="day_e">
		<?php for($x=1;$x<=31;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("j") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>
	</select>
	<select name="year_e">
		<?php for($x=2010;$x<=2015;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("Y") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>		
	</select> / 
	<select name="hour_e">
		<?php for($x=0;$x<=23;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("H") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>
	</select>	
	<select name="minute_e">
		<option value="00">00</option>
		<option value="15">15</option>
		<option value="30">30</option>
		<option value="45">45</option>
	</select>
	<?php  } 
	
function drawDateTimePicker_end_short() { ?>
	<select name="hour_e">
		<?php for($x=0;$x<=23;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("H") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>
	</select>	
	<select name="minute_e">
		<option value="00">00</option>
		<option value="15">15</option>
		<option value="30">30</option>
		<option value="45">45</option>
	</select>
	<?php  } 

function drawDateTimePicker_date_e() { ?>
	<select name="month_s">
		<?php for($x=1;$x<=12;$x++) { ?>
			<option value="<?= $x ?>"<?php if($x == $_SESSION['month']) { echo"selected='selected'";} ?>><?= date('F', mktime(0,0,0,$x,1)) ?></option>	
		<?php } ?>
	</select>
	
	<select name="day_s">
		<?php for($x=1;$x<=31;$x++) { ?>
			<option value="<?= $x ?>"<?php if($x == $_SESSION['day']) { echo"selected='selected'";} ?>><?= $x ?></option>	
		<?php } ?>
	</select>
	<select name="year_s">
		<?php for($x=2012;$x<=2015;$x++) { ?>
			<option value="<?= $x ?>"<?php if($x == $_SESSION['year']) { echo"selected='selected'";} ?>><?= $x ?></option>	
		<?php } ?>		
	</select> 
<?php }

function drawDateTimePicker_start_short_e() { ?>
	<select name="hour_s">
		<?php for($x=0;$x<=23;$x++) { ?>
			<option value="<?= $x ?>"<?php if($x == $_SESSION['hour']) { echo"selected='selected'";} ?>><?= $x ?></option>	
		<?php } ?>
	</select>	
	<select name="minute_s">
		<?php for($x=0;$x<=45;$x = $x+ 15) { ?>
			<option value="<?= $x ?>"<?php if($x == $_SESSION['minute']) { echo"selected='selected'";} ?>><?= $x ?></option>	
		<?php } ?>
	</select>
	<?php  } 
	
function drawDateTimePicker_end_short_e() { ?>
	<select name="hour_e">
		<?php for($x=0;$x<=23;$x++) { ?>
			<option value="<?= $x ?>"<?php if($x == $_SESSION['hour_e']) { echo"selected='selected'";} ?>><?= $x ?></option>	
		<?php } ?>
	</select>	
	<select name="minute_e">
		<?php for($x=0;$x<=45;$x = $x+ 15) { ?>
			<option value="<?= $x ?>"<?php if($x == $_SESSION['minute_e']) { echo"selected='selected'";} ?>><?= $x ?></option>	
		<?php } ?>
	</select>
	<?php /*
	<select name="period_e">
		<option value="pm">pm</option>
		<option value="am">am</option>
	</select>
	*/ ?>
<?php } 

function drawDateTimePicker_art_start() { ?>
	<select name="month_s">
		<?php for($x=1;$x<=12;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("n") == $x ? ' selected="selected"' : '') ?>><?= date('F', mktime(0,0,0,$x,1)) ?></option>	
		<?php } ?>
	</select>
	<select name="day_s">
		<?php for($x=1;$x<=31;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("j") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>
	</select>
	<select name="year_s">
		<?php for($x=2010;$x<=2015;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("Y") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>		
	</select> 
	<?php 
 } 
 
function drawDTP_art_start_e($date) { 

$m=date('F',strtotime($date));
$d=date('j',strtotime($date));
$y=date('Y',strtotime($date));

?>
	<select name="month_s">
		<?php for($x=1;$x<=12;$x++) { 
		$m2 = date('F', mktime(0,0,0,$x,1)); ?>
			<option value="<?= $x ?>" <?php if($m == $m2) {echo"selected='selected'";}?>><?php echo($m2); ?></option>	
		<?php } ?>
	</select>
	<select name="day_s">
		<?php for($x=1;$x<=31;$x++) { ?>
			<option value="<?= $x ?>"<?php if($d == $x) {echo"selected='selected'";} ?>><?= $x ?></option>	
		<?php } ?>
	</select>
	<select name="year_s">
		<?php for($x=2010;$x<=2015;$x++) { ?>
			<option value="<?= $x ?>"<?php if($y == $x) {echo"selected='selected'";} ?>><?= $x ?></option>	
		<?php } ?>		
	</select> 
	<?php 
 } 

function drawDateTimePicker_art_end() { ?>
	<select name="month_e">
		<?php for($x=1;$x<=12;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("n") == $x ? ' selected="selected"' : '') ?>><?= date('F', mktime(0,0,0,$x,1)) ?></option>
		<?php } ?>
	</select>
	<select name="day_e">
		<?php for($x=1;$x<=31;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("j") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>
	</select>
	<select name="year_e">
		<?php for($x=2010;$x<=2015;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("Y") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>		
	</select> 
	<?php 
 } 
 
function drawDTP_art_end_e($date) { 

$m=date('F',strtotime($date));
$d=date('j',strtotime($date));
$y=date('Y',strtotime($date));

?>
	<select name="month_e">
		<?php for($x=1;$x<=12;$x++) { 
		$m2 = date('F', mktime(0,0,0,$x,1)); ?>
			<option value="<?= $x ?>" <?php if($m == $m2) {echo"selected='selected'";}?>><?php echo($m2); ?></option>
		<?php } ?>
	</select>
	<select name="day_e">
		<?php for($x=1;$x<=31;$x++) { ?>
			<option value="<?= $x ?>"<?php if($d == $x) {echo"selected='selected'";} ?>><?= $x ?></option>	
		<?php } ?>
	</select>
	<select name="year_e">
		<?php for($x=2010;$x<=2015;$x++) { ?>
			<option value="<?= $x ?>"<?php if($y == $x) {echo"selected='selected'";} ?>><?= $x ?></option>	
		<?php } ?>		
	</select> 
	<?php 
 } 
 
function drawDateTimePicker_art_vernissage() { ?>
	<select name="month_vs">
		<?php for($x=1;$x<=12;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("n") == $x ? ' selected="selected"' : '') ?>><?= date('F', mktime(0,0,0,$x,1)) ?></option>	
		<?php } ?>
	</select>
	<select name="day_vs">
		<?php for($x=1;$x<=31;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("j") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>
	</select>
	<select name="year_vs">
		<?php for($x=2010;$x<=2015;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("Y") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>		
	</select> / 
	<select name="hour_vs">
		<?php for($x=0;$x<=23;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("H") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>
	</select>	
	<select name="minute_vs">
		<option value="00">00</option>
		<option value="15">15</option>
		<option value="30">30</option>
		<option value="45">45</option>
	</select>
	<?php 
}

function drawDateTimePicker_art_finissage() { ?>
	<select name="month_fs">
		<?php for($x=1;$x<=12;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("n") == $x ? ' selected="selected"' : '') ?>><?= date('F', mktime(0,0,0,$x,1)) ?></option>	
		<?php } ?>
	</select>
	<select name="day_fs">
		<?php for($x=1;$x<=31;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("j") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>
	</select>
	<select name="year_fs">
		<?php for($x=2010;$x<=2015;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("Y") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>		
	</select> / 
	<select name="hour_fs">
		<?php for($x=0;$x<=23;$x++) { ?>
			<option value="<?= $x ?>"<?= (date("H") == $x ? ' selected="selected"' : '') ?>><?= $x ?></option>	
		<?php } ?>
	</select>	
	<select name="minute_fs">
		<option value="00">00</option>
		<option value="15">15</option>
		<option value="30">30</option>
		<option value="45">45</option>
	</select>
	<?php 
}

function drawDateTimePicker_art_openinghours($time_s,$time_e) { 

if($time_s != '00:00:00') {
$h_s = date('H',strtotime($time_s));
$h_e = date('H',strtotime($time_e));
$m_s = date('j',strtotime($time_s));
$m_e = date('j',strtotime($time_e));
}

?>
	<select name="hour_s[]" size="1">
		<?php for($x=0;$x<=23;$x++) { ?>
			<option value="<?= $x ?>"<?php if($h_s == $x) {echo "selected='selected'";} ?>><?= $x ?></option>	
		<?php } ?>
	</select>	
	<select name="minute_s[]">
		<option value="00">00</option>
		<option value="15">15</option>
		<option value="30">30</option>
		<option value="45">45</option>
	</select> to<br /> 
	<select name="hour_e[]">
		<?php for($x=0;$x<=23;$x++) { ?>
			<option value="<?= $x ?>"<?php if($h_e == $x) {echo "selected='selected'";} ?>><?= $x ?></option>	
		<?php } ?>
	</select>
	<select name="minute_e[]">
		<option value="00">00</option>
		<option value="15">15</option>
		<option value="30">30</option>
		<option value="45">45</option>
	</select> 
	<?php 

 } 

function drawDateTimePicker_art_openinghours_ex($time_s,$time_e) { 

if($time_s != '') {
$h_s = date('H',strtotime($time_s));
$h_e = date('H',strtotime($time_e));
$m_s = date('j',strtotime($time_s));
$m_e = date('j',strtotime($time_e));
}

?>
	<select name="hour_s[]" size="1">
		<?php for($x=0;$x<=23;$x++) { ?>
			<option value="<?= $x ?>"<?php if($h_s == $x) {echo "selected='selected'";} ?>><?= $x ?></option>	
		<?php } ?>
	</select>	
	<select name="minute_s[]">
		<option value="00">00</option>
		<option value="15">15</option>
		<option value="30">30</option>
		<option value="45">45</option>
	</select> to<br /> 
	<select name="hour_e[]">
		<?php for($x=0;$x<=23;$x++) { ?>
			<option value="<?= $x ?>"<?php if($h_e == $x) {echo "selected='selected'";} ?>><?= $x ?></option>	
		<?php } ?>
	</select>
	<select name="minute_e[]">
		<option value="00">00</option>
		<option value="15">15</option>
		<option value="30">30</option>
		<option value="45">45</option>
	</select> 
	<?php 

 } 

?>