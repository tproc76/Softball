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
		
	include 'db_setup.php';	
?>

<html>
	<head>
        <title>Easy Maintainance Page</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="softball,proctor,attendance" />

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="page_format.css">

		<!-- Menu CSS Stuff -->
		<link rel="stylesheet" type="text/css" href="menu_leftsideslide.css">
        <link rel="stylesheet" type="text/css" href="page_format.css">
		
		<script type="text/javascript" src="dist-js/jquery-3.2.1.min.js"></script>
		<script type="text/javascript" src="softball_standard.js"></script>
		<script type="text/javaScript">
			function sendStatEmail()
				{
				event.preventDefault();
				var XMLHttpRequestObject = false;			
				XMLHttpRequestObject = new XMLHttpRequest();
				
				var theDropDown = document.getElementById("statemail");
				var seasonId = theDropDown.options[theDropDown.selectedIndex].value;
				
				if (XMLHttpRequestObject) 
					{
					XMLHttpRequestObject.open("GET","http://"+ WEBPATH +"/stat_email.php?seasonid=" + seasonId  );
				
					XMLHttpRequestObject.onreadystatechange=function()
						{
						if (XMLHttpRequestObject.readyState==4 && XMLHttpRequestObject.status==200)
							{
							var result = XMLHttpRequestObject.responseText.split(":");
							
							if (result[0] == "success")
								{
								alert("Email Sent");
								}
							else
								{
								alert(XMLHttpRequestObject.responseText);
								}
							} 
						}
					XMLHttpRequestObject.send();
					}
				}

		</script>
	</head>
<body>

<!--top-header-->
<div class="row justify-content-md-center">
	<h1 id="pageheader">My Maintainance Page</h1>
	<BR><BR>
</div>	

<br>

	<div class="row justify-content-md-center">
		<table width="80%" id="maintaintable" border='0' cellspacing='1' cellpadding='5' class='center' >
			<col width="33%">
			<col width="34%">
			<col width="33%">		
		<tr><th style="text-align:center; text-decoration: underline">Loading Stats</th><th style="text-align:center; text-decoration: underline">Stat Stuff</th><th style="text-align:center; text-decoration: underline">Attendance Stuff</th><tr>
		<tr>
			<td style='text-align:center'><a href="loadgamefile_main.php">Load Single Game File</a><br>
				<a href="loadstatsfile_main.php">Load Stats File</a><br>
				<a href="loadcsv_main.php">Load CSV File</a><br>
				<br>
					<?php 
						$curYear = date('Y');						
						$selectOption = "<select name='statemail' id='statemail'>";
						
						$selectCurr = "SELECT * FROM seasons WHERE year=$curYear";
//							$selectOption .= "<option value='0' selected>Test - Test</option>";
						$result =mysqli_query($link, $selectCurr);
						while ($row=mysqli_fetch_array($result, MYSQLI_ASSOC)) 
							{
							$sid = $row['season_id'];
							$tname = $row['team_name'];
							$sseason = $row['season_name'];
							$selectOption .= "<option value='$sid' selected>$tname - $sseason</option>";
							}						
				
						$selectOption .= "</select>";
						echo $selectOption;
						
					?>
				<br>
				<button type='button' onclick='sendStatEmail()'>Send Stat Email</button>
			</td>
			<td style='text-align:center'><a href="manage_players_main.php">Manage Stat Players</a><br>
			     <a href="manage_stat_teams_main.php">Manage Team Seasons</a><br>
			     <a href="loadteamcfg_main.php">Create New Team/Season Config</a><br>
				 <a href="loadteamcfg_update.php">Update Configuration of Team in Above</a><br>
			</td>
			<td style='text-align:center'><a href="attend_create_team_main.php">Create Attendance Team</a><br>
				<a href="manage_attend_players_main.php">Manage Attendance Players</a><br>
				<a href="manage_attend_teams_main.php">Manage Attendance Teams</a><br>
			</td>
		</tr>
		<tr><td colspan='3'><br><br><br></td></tr>
		<tr><th colspan='3' style='text-align:center; text-decoration: underline'>Data Backup</th></tr>
		<tr><td></td><td style='text-align:center'><a href="backup_stats_players.php">Stats Player Table</a><br>
													<a href="backup_attend_tables.php">All Attendance Tables</a>
					</td><td></td></tr>
		</table>
	</div>
	<br><br><br>
	<div class="row justify-content-md-center">
		<a href="softball_home.php">Softball Stats</a><br>
	</div>
	<br>
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