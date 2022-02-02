<!DOCTYPE html>
<html>
    <head>
        <title>Softball Stats Page</title>

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
				<h1 id="pageheader">Welcome to My Softball Site</h1>
			</div>	
			<BR><BR>
				<?php
					$pictMax = 6;
					$buildTabel = "<table width='99%' border='0' cellspacing='10' cellpadding='10' class='center' id='standings'>";
					$buildTabel .= "<col width='33%'><col width='33%'><col width='33%'>";
					$pict1 = rand(0,$pictMax);
					$pict2 = rand(0,$pictMax);
					
					while ($pict1==$pict2)
						{
						$pict2 = rand(0,$pictMax);
						}
						
					$pictureTag1 = "<img src='img\home" . $pict1 . ".jpg' alt='comic1' height='300' width='300'>";
					$pictureTag2 = "<img src='img\home" . $pict2 . ".jpg' alt='comic1' height='300' width='300'>";
						
					$buildTabel .= "<tr><td style='text-align:center'>$pictureTag1</td><td style='text-align:center'></td><td style='text-align:center'>$pictureTag2</td></tr>";
					$buildTabel .= "</table>";
					
					echo $buildTabel;
				?>
			<p align="center">Welcome to my softball pages and the Home of
			the our Great Softball Teams.</p>

			<p align="center">Team stats from the GM MPG (Bakers) and Men's&nbsp;at
			CSC (Pizza & Beer) team are available on the left.</p>
			<br>
			<table border="0" width="99%">
			  <tr>
				<td valign="middle" align="center" width="50%">MPG Schedule</td>
				<td valign="middle" align="center" width="50%">CSC Schedule</td>
			  </tr>
			</table>
			
			<p align="center">&nbsp;</p>
			<p align="center">If you have Free time check out some of these links:</p>
			<table border="0" width="99%">
			  <tr>
				<td valign="middle" align="center" width="33%"><a href="http://www.slugger.com/">Louisville Slugger</a></td>
				<td valign="middle" align="center" width="33%"><a href="http://www.softballbats.com/">softballbats.com</a></td>
				<td valign="middle" align="center" width="33%"><a href="http://www.batreviews.com/">Bat Reviews</a></td>
			  </tr>
			  <tr>
				<td valign="middle" align="center" width="33%"><a href="http://www.worthsports.com/">Worth Sports</a></td>
				<td valign="middle" align="center" width="33%"><a href="http://www.wilson.com/">Wilson Sports</a></td>
				<td valign="middle" align="center" width="33%"><a href="http://www.easton.com/">Easton</a></td>
			  </tr>
			  <tr>
				<td valign="middle" align="center" width="33%"></td>
				<td valign="middle" align="center" width="33%"></td>
				<td valign="middle" align="center" width="33%"></td>
			  </tr>
			</table>
			<br><br>
			
			<div class="row justify-content-md-center">
				<a href="mailto:proc@comcast.net" pbzloc="0">Email me</a>
			</div>
		</div>
	</div>
	</div>     
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

