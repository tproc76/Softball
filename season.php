<!DOCTYPE html>
<html>
    <head>
        <title>Softball Stats Page</title>

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
		
		<!-- Side MENU Start -->
		<?php
			include 'stats_sidemenu.php';
		?>
		<!-- Side MENU End -->
		
        <!--top-header-->

		<!-- Add all page content inside this div if you want the side nav to push page content to the right (not used if you only want the sidenav to sit on top of the page -->
		<div id="main" class="col-10">
			<div class="row justify-content-md-center">
				<h1 id="pageheader">Softball Season Stats Page<BR>Team Name</h1>
			</div>	
				<BR><BR>
			<div class="row justify-content-md-center">
				<table border="1" cellspacing="1" cellpadding="10" class="center" id="standings">
					<tr>
						<th style="text-align:center">W</th><th style="text-align:center">L</th><th style="text-align:center">T</th><th style="text-align:center">PCT</th><th style="text-align:center">Scored</th><th style="text-align:center">Allowed</th><th>Pyth</th>
					</tr>
					<tr>
						<td style="text-align:center">0</td><td style="text-align:center">0</td><td style="text-align:center">0</td><td style="text-align:center"></td><td style="text-align:center">0</td><td style="text-align:center">0</td><td style="text-align:center"></td>
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
					<table border='1' cellspacing='1' cellpadding='10' class='center' id='hitterlist' data-totals='true'>
						<thead>
							<tr>
								<th style='text-align:left'>Player</th>
								<th style='text-align:center'>G</th>
								<th style='text-align:center'>AB</th>
								<th style='text-align:center'>R</th>
								<th style='text-align:center'>H</th>
								<th style='text-align:center'>2B</th>
								<th style='text-align:center'>3B</th>
								<th style='text-align:center'>HR</th>
								<th style='text-align:center'>RBI</th>
								<th style='text-align:center'>BB</th>
								<th style='text-align:center'>K</th>
								<th style='text-align:center'>AVG</th>
								<th style='text-align:center'>OBP</th>
								<th style='text-align:center'>SLG</th>
							</tr>
						</thead>
						<tr>
							<th style='text-align:left'>None</th>
							<th style='text-align:center'>0</th>
							<th style='text-align:center'>0</th>
							<th style='text-align:center'>0</th>
							<th style='text-align:center'>0</th>
							<th style='text-align:center'>0</th>
							<th style='text-align:center'>0</th>
							<th style='text-align:center'>0</th>
							<th style='text-align:center'>0</th>
							<th style='text-align:center'>0</th>
							<th style='text-align:center'>0</th>
							<th style='text-align:center'>0.000</th>
							<th style='text-align:center'>0.000</th>
							<th style='text-align:center'>0.000</th>
						</tr>
					</table>
				</div>
			</div>
			
			<BR><BR><BR>
			
			<div class="row justify-content-md-center" role="tab" id="pitcherTableControl">
			  <h5 class="mb-0">
				<a data-toggle="collapse" href="#collapsePitch" aria-expanded="true" aria-controls="collapsePitch">
				  Pitchers
				</a>
			  </h5>
			</div>
			<div id="collapsePitch" class="collapse show" role="tabpanel" aria-labelledby="pitcherTableControl" data-parent="#accordion">
				<div class="row justify-content-md-center">
					<table border='1' cellspacing='1' cellpadding='10' class='center' id='pitcherlist' data-totals='true'>
						<thead>
							<tr>
								<th style='text-align:left'>Name</th>
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
			</div>
			
			<BR><BR><BR>
			
			<div class="row justify-content-md-center" role="tab" id="gamesTableControl">
			  <h5 class="mb-0">
				<a data-toggle="collapse" href="#collapseGame" aria-expanded="true" aria-controls="collapseGame">
				  Games
				</a>
			  </h5>
			</div>
			<div id="collapseGame" class="collapse show" role="tabpanel" aria-labelledby="gameTableControl" data-parent="#accordion">
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
			</div>
			
			<br><br>
			<p class="text-right" id="updatetag">Last Updated:</p>
		</div>
        <script id="seasonname" dataname="Summer 2017" src="readseason.js"></script>    
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script type="text/javascript" src="dist-js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="dist-js/popper.min.js"></script>
		<script type="text/javascript" src="dist-js/tether.min.js"></script>
		<script type="text/javascript" src="dist-js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>

	</div>
	</div>
    </body>
</html>

