<!DOCTYPE html>
<html>
    <head>
        <title>Load Stats Page</title>
		
		<!-- Menu CSS Stuff -->
		<link rel="stylesheet" type="text/css" href="menu_leftside.css">
		
		<script type="text/javascript" src="menu_leftsideslide.js"></script>
    </head>
<body>

<!--top-header-->
<div class="row justify-content-md-center">
	<h1 style="text-align:center" id="pageheader">Load Stats Page</h1>
</div>	

<!-- Side MENU Start -->
<?php
	include 'maint_sidemenu.php';
?>
<!-- Side MENU End -->

<!-- Add all page content inside this div if you want the side nav to push page content to the right (not used if you only want the sidenav to sit on top of the page -->
<div id="main" class="main">		
	<table border='0'>
		<col width="33%">
		<col width="34%">
		<col width="33%">		
		<tr><td></td><td>
			<form action="loadstatsfile.php" method="post" enctype="multipart/form-data">
				Select Stat File to upload a season:
				<input type="file" name="fileToUpload" width="100" size="60" id="fileToUpload"><br>
				<input type="radio" name = "league" value = "Mens#Weeknight" required/>Mens - Weeknight<br>
				<input type="radio" name = "league" value = "Mens#Sunday" required/>Mens - Sunday<br>
				<input type="radio" name = "league" value = "MPG#Bakers"/>MPG - Bakers<br>
				<input type="radio" name = "league" value = "MPG#Pizza"/>MPG - Pizza<br>
				<input type="radio" name = "league" value = "Co-ed"/>Co-ed<br>
				<input type="submit" value="Upload Stat File" name="submit">
			</form>
		</td><td></td></tr>
	</table>
</div>
<script type="text/javascript">window.addEventListener('load', closeNav);</script>
</body>
</html> 