<!DOCTYPE html>
<html>
    <head>
        <title>Softball Pitcher Season Stats Page</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="softball,proctor" />

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        
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
				<h1 id="playerheader">Player Name</h1>
				<BR><BR>
			</div>	
			<div class="row justify-content-md-center">
				<table border='1' cellspacing='1' cellpadding='10' class='center' id='pitcherlist'>
					<thead>
							<tr>
								<th style='text-align:left'>Game</th>
								<th style='text-align:center'>W</th>
								<th style='text-align:center'>L</th>
								<th style='text-align:center'>PCT</th>
								<th style='text-align:center'>INN</th>
								<th style='text-align:center'>R</th>
								<th style='text-align:center'>RA</th>
								<th style='text-align:center'>K</th>
							</tr>
						</thead>
						<tr>
							<th style='text-align:left'>None</th>
							<th style='text-align:center'>0</th>
							<th style='text-align:center'>0</th>
							<th style='text-align:center'>0.000</th>
							<th style='text-align:center'>0.0</th>
							<th style='text-align:center'>0</th>
							<th style='text-align:center'>0.00</th>
							<th style='text-align:center'>0</th>
						</tr>
				</table>
			</div>
			
			<BR><BR>
					
			<br><br>
			<p class="text-right" id="updatetag">Last Updated:</p>
		</div>        
	</div>
	</div>
	<script id="seasonname" src="readpitcher.js"></script>    
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script type="text/javascript" src="dist-js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="dist-js/popper.min.js"></script>
	<script type="text/javascript" src="dist-js/tether.min.js"></script>
	<script type="text/javascript" src="dist-js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    </body>
</html>
