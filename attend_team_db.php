<?php
session_start();
if(isset($_SESSION['login_user']) == false)
	{
	// redirect to login if not logged in
	echo '<script type="text/javascript">window.location = "attend_login.html"</script>';
	exit();
	}

include 'db_setup.php';

$team_id = $_SESSION['team_id'];

$teamname = $_POST["name_teamname"];
echo $teamname . "<br>";

$season = $_POST["name_season"];
echo $season . "<br>";

$reminder_post = $_POST["name_remind"];
if (is_numeric($reminder_post)==false)
	{
	echo "Error: Reminder not numeric." . $reminder_post . "<br>";
	exit();
	}
$reminder = (int)$reminder_post;
echo $reminder . "<br>";

$lockout_post = $_POST["name_lockout"];
if (is_numeric($lockout_post)==false)
	{
	echo "Error: Lockout not numeric." . $lockout_post . "<br>";
	exit();
	}
$lockout = (int)$lockout_post;
echo $lockout . "<br>";

$linkstatseason = $_POST["linksea"];
echo $linkstatseason . "<br>";
if ($linkstatseason=='-1')
	$linkstatseason = null;

$player_id = $_SESSION['player_id'];
echo $player_id . "<br>";

// Add Team
if ($linkstatseason == null)
	$updateTeam = "UPDATE attend_team SET team_name='$teamname', season='$season', reminderdays=$reminder, lockouthours=$lockout, updated=CURRENT_TIMESTAMP WHERE team_id=$team_id";
else
	$updateTeam = "UPDATE attend_team SET team_name='$teamname', season='$season', reminderdays=$reminder, stats_team_id=$linkstatseason, lockouthours=$lockout, updated=CURRENT_TIMESTAMP WHERE team_id=$team_id";
	
if(mysqli_query($link, $updateTeam) == true)
	{
	//https://stackoverflow.com/questions/768431/how-to-make-a-redirect-in-php
	   // window.location = "http://www.google.com/"
	echo '<script type="text/javascript">window.location = "attendance_home.php?display=teams"</script>';
	}
else
	{
	echo "ERROR: " . mysqli_error($link) . "<BR>";								
	echo "Updated FAILED!!";
	}
	
?>
