<?php
	session_start();
	if(isset($_SESSION['login_user']) == false)
		{
		// redirect to login if not logged in
		echo '<script type="text/javascript">window.location = "attend_login.html"</script>';
		exit();
		}
		
	$teamid = -1;

	if(isset($_GET['team']))
		{
		$teamid = $_GET["team"];
		$_SESSION['team_id'] = $teamid;
		}
	else if (isset($_SESSION['team_id']))
		{
		$teamid = $_SESSION['team_id'];
		}
	else
		{
		// redirect to team selection page
		echo '<script type="text/javascript">window.location = "attendance_home.php?display=teams"</script>';
		exit();
		}
	
	include 'db_setup.php';	
		
?>

<html>
	<head>
        <title>Update Team Settings</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="keywords" content="softball,proctor,attendance" />

		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="bootstrap-4.0.0-alpha.6-dist/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
        <link rel="stylesheet" type="text/css" href="page_format.css">

<!--		<script type="text/javascript" src="dist-js/jquery-3.2.1.min.js"></script> -->
		
	</head>
<body>

<!--top-header-->
<div class="row justify-content-md-center">
	<h1 id="pageheader">Update Team Settings</h1>
	<BR><BR>
</div>	

<br>

<?php
	$findTeam = "SELECT * FROM attend_team WHERE team_id = '$teamid'";
	if ($result = mysqli_query($link, $findTeam) )
		{
		$teamentry = mysqli_fetch_array($result,MYSQLI_ASSOC);
		}
	else
		{
		echo "ERROR: Could not execute. " . mysqli_error($link) . "<BR>";
		}
?>

<form action="attend_team_db.php" method="post" enctype="multipart/form-data">
	<div class="row justify-content-md-center">
		<table id="edittable" border='0' cellspacing='1' cellpadding='5' class='center' >
			<tr>
				<th></th>
				<th></th>
			</tr>
			<tr id="row_teamname">
				<td id="lbl_teamname">Team Name</td>
				<td id="input_teamname"> <input type="text" name="name_teamname" id="id_teamname" value="<?php echo $teamentry['team_name'];?>" required></td>
			</tr>
			<tr id="row_year">
				<td id="lbl_season">Season</td>
				<td id="input_teamname"> <input type="text" name="name_season" id="id_season" value="<?php echo $teamentry['season'];?>" required></td>
			</tr>
			<tr id="row_reminder">
				<td id="lbl_reminder">Reminder Days*</td>
				<td id="input_startdate"><input type="text" name="name_remind" id="id_remind" value="<?php echo $teamentry['reminderdays'];?>" required /></td>
			</tr>
				<tr id="row_lockout">
					<td id="lbl_teamname">Lockout Hours</td>
					<td id="input_lockout"><input type="text" name="name_lockout" id="id_lockout" value="<?php echo $teamentry['lockouthours'];?>" required /></td>
				</tr>
			<tr>
				<td>Link To Stats Team</td>
				<td>
					<?php
						$selectTeams = "SELECT season_id, season_name, team_name FROM seasons ORDER BY season_id DESC";
						
						if ($result = mysqli_query($link, $selectTeams) )
							{
							$selectOption = "none";
							
							if (mysqli_num_rows($result) > 0)
								{
								$selectOption = "<select name='linksea'>";
								$selectOption .= "<option value='-1'>None</option>";
								while ($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
									{
									$sid = $row['season_id'];
									$sname = $row['team_name'];
									$sseason = $row['season_name'];
									
									if ($sid == $teamentry['stats_team_id'])
										{
										$selectOption .= "<option value='$sid' selected>$sname - $sseason</option>";
										}
									else
										{
										$selectOption .= "<option value='$sid'>$sname - $sseason</option>";
										}
									}
								$selectOption .= "</select>";
								}
								
							echo $selectOption;
							}
						else
							{
							echo "ERROR: " . mysqli_error($link) . "<BR>";								
							}
					?>				
				</td>
			<tr>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align:center"><input type="submit" value="Save" name="submit"></td>
			</tr>
		</table>
		<br><br><br>* - Reminder days is the number of days before a game that an email will be sent to the team <br> indicating who will come, who will not, and who did not respond.
	</div>
</form>
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