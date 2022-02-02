<?php
	session_start();
	if(isset($_SESSION['login_user']) == false)
		{
		// redirect to login if not logged in
		echo '<script type="text/javascript">window.location = "attend_login.html"</script>';
		exit();
		}
		
	if (($_SESSION['login_user']!="proc@comcast.net") &&
		($_SESSION['login_user']!="timothy.proctor@gm.com"))
		{
		// redirect to login if not logged in
		echo '<script type="text/javascript">window.location = "attend_members_main.php"</script>';
		exit();
		}
?>

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
			<form action="loadcsv.php" method="post" enctype="multipart/form-data">
				Select CSV to upload a season:
				<input type="file" name="fileToUpload" id="fileToUpload" width="100" size="60" required><br>
				<input type="radio" name = "league" value = "Mens#Sunday" required/>Mens - Sunday<br>
				<input type="radio" name = "league" value = "MPG#Pizza"/>MPG - Pizza<br>
				<input type="radio" name = "league" value = "Co-ed"/>Co-ed<br>
				<input type="submit" value="Upload CSV" name="submit">
			</form>
		</td><td></td></tr>
	</table>
</div>
<script type="text/javascript">window.addEventListener('load', closeNav);</script>
</body>
</html> 