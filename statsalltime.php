<!DOCTYPE html>
<html>
    <head>
        <title>Softball All Time Stats Page</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="softball,proctor" />

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="page_format.css">
       
		<style type="text/css">
		   .centerText{
			   text-align: center;
			}
		</style>
		<!-- Menu CSS Stuff -->
		<link rel="stylesheet" type="text/css" href="menu_leftside.css">
		
		<script type="text/javascript" src="sorttable.js"></script>
		<script type="text/javascript" src="softball_standard.js"></script>
		
    </head>
    <!-- slide-toggle-menu -->
    <body>
	<div class="container-fluid">
	<div class="row">		
		<br>

		<!-- Side MENU Start -->
		<?php
			include 'stats_sidemenu.php';
		?>
		<!-- Side MENU End -->

		<!-- Add all page content inside this div if you want the side nav to push page content to the right (not used if you only want the sidenav to sit on top of the page -->
		<div id="main" class="col-10">		
			<!--top-header-->
			<div class="row justify-content-md-center">
				<h1 id="seasonheader">All Time Stat List</h1>
				<BR><BR>
			</div>	
		
			<div class="row justify-content-md-center">
				<table width="80%" border='0' cellspacing='1' cellpadding='10' class='center' id='leaguelist'>
					<col width="20%">
					<col width="20%">
					<col width="20%">
					<col width="20%">
					<col width="20%">
					<tr>
						<td></td>
						<td style='text-align:center'><a href="statsalltime.php?league=Mens">Mens</a></td>
						<td style='text-align:center'><a href="statsalltime.php?league=MPG">MPG</a></td>
						<td style='text-align:center'><a href="statsalltime.php?league=Co-ed">Co-Ed</a></td>
						<td></td>
					</tr>
				</table>
			</div>
		
		<br>
		<br>
		<div class="row justify-content-md-center">
			<table border="1" cellspacing="1" cellpadding="10" class="center" id="standings">
				<tr>
					<th style="text-align:center">W</th><th style="text-align:center">L</th><th style="text-align:center">T</th><th style="text-align:center">PCT</th><th style="text-align:center">Scored</th><th style="text-align:center">Allowed</th><th>Pyth</th>
				</tr>
				<tr>
					<td style="text-align:center" id="teamwins">0</td><td style="text-align:center" id="teamloss">0</td><td style="text-align:center" id="teamties">0</td><td style="text-align:center" id="teampct"></td>
					<td style="text-align:center" id="teamscored">0</td><td style="text-align:center" id="teamallowed">0</td><td style="text-align:center" id="teampyth"></td>
				</tr>
			</table>
		</div>
		
		<BR><BR><BR>
		
		<div class="row justify-content-md-center" role="tab" id="hitterTableControl">
		  <h5 class="mb-0">
			<a data-toggle="collapse" href="#collapseHit" aria-expanded="true" aria-controls="collapseHit">
			  Hitters
			</a>
		  </h5>
		</div>
		<div id="collapseHit" class="collapse show" role="tabpanel" aria-labelledby="hitterTableControl" data-parent="#accordion">
			<div class="row justify-content-md-center">
				<table border='1' cellspacing='1' cellpadding='8' class='center' id='hitterlist' data-totals='true'>
					<thead>
						<tr>
							<th style='text-align:left'><h3><u>Season</u></h3></th>
						</tr>
					</thead>
					<tr>
						<td style='text-align:left'>None</td>
					</tr>
				</table>
			</div>
		</div>
		
		<BR><BR>
		<div class="row justify-content-md-center" role="tab" id="pitcherTableControl">
		  <h5 class="mb-0">
			<a data-toggle="collapse" href="#collapsePitch" aria-expanded="true" aria-controls="collapsePitch">
			  Pitchers
			</a>
		  </h5>
		</div>
		<div id="collapsePitch" class="collapse show" role="tabpanel" aria-labelledby="pitcherTableControl" data-parent="#accordion">
			<div class="row justify-content-md-center">
				<table border='1' cellspacing='1' cellpadding='8' class='center' id='pitcherlist' data-totals='true'>
					<thead>
						<tr>
							<th style='text-align:left'><h3><u>Season</u></h3></th>
						</tr>
					</thead>
					<tr>
						<td style='text-align:left'>None</td>
					</tr>
				</table>
			</div>
		</div>
		<br>
	</div>
	</div>
	
	<script id="seasons" src="readalltime.js"></script>    
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script type="text/javascript" src="dist-js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="dist-js/popper.min.js"></script>
	<script type="text/javascript" src="dist-js/tether.min.js"></script>
	<script type="text/javascript" src="dist-js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>  
	<div class="footer">
		<?php  
		$fullfilename = $_SERVER['REQUEST_URI']; 
		$fileparts = explode("?",basename($fullfilename));
		$filename = $fileparts[0];
		//echo $filename;
		//echo filemtime($filename);
		
		date_default_timezone_set('US/Eastern');
		// checking last time the contents of
		// a file were changed and formatting
		// the output of the date 
		//echo "Version:".date("y.md.Hi", filemtime($filename));
		echo "Updated: ".date("F d Y H:i:s", filemtime($filename));
		?>
	</div>	
    </body>
</html>
