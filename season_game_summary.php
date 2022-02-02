<!DOCTYPE html>
<html>
    <head>
        <title>Softball Game Stats Page</title>

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
				<h1 id="pageheader">Softball Season Game Stats Page<BR>Team Name</h1>
				<BR><BR>
			</div>	
			<div class="row justify-content-md-center">
				<div id="gamelistview">                               
					<table border="1" cellspacing="0" cellpadding="8" class="center" id="gamelist">
						<tr>
							<th style="text-align:center">Date</th>
							<th style="text-align:center">Opponent</th>
							<th style="text-align:center">RS</th>
							<th style="text-align:center">RA</th>
							<th style="text-align:center">Result</th>
							<th style="text-align:center">Details</th>
						</tr>
						<tr>
							<td style="text-align:left">####-##-## ##:##</td>
							<td style="text-align:left">vs ##########</td>
							<td style="text-align:center">##</td>
							<td style="text-align:center">##</td>
							<td style="text-align:center">#</td>
							<td style="text-align:center"></td>
						</tr>
					</table>
				</div>
			</div>
		
			<br><br>
			<p class="text-right" id="updatetag">Last Updated:</p>
		</div>        
	</div>
	</div>
	<script id="seasonname" src="readgamesummary.js"></script>    
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script type="text/javascript" src="dist-js/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="dist-js/popper.min.js"></script>
	<script type="text/javascript" src="dist-js/tether.min.js"></script>
	<script type="text/javascript" src="dist-js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    </body>
</html>

