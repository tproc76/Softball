<?php
session_start();
if(isset($_SESSION['login_user']) == false)
	{
	// redirect to login if not logged in
	echo '<script type="text/javascript">window.location = "attend_login.html"</script>';
	exit();
	}

$commitData = true;
$playersAdded = 0;

include 'db_setup.php';

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

$copyseason = $_POST["copysea"];
echo $copyseason . "<br>";

$linkstatseason = $_POST["linksea"];
echo $linkstatseason . "<br>";

$player_id = $_SESSION['player_id'];
echo $player_id . "<br>";

if ($reminder < 0 || $reminder > 15)
	{
	echo "Error: Reminder Days not valid";
	exit();
	}
	
if ($lockout <0 || $lockout > 48)
	{
	echo "Error: Lockout hours not valid";
	exit();
	}

/* disable autocommit */
mysqli_autocommit($link, FALSE);

// Add Team
if ($linkstatseason=='-1')
	$insertTeam = "INSERT INTO attend_team VALUES(default, '$teamname', '$season', $reminder, null, $lockout, $player_id, null, CURRENT_TIMESTAMP);";
else
	$insertTeam = "INSERT INTO attend_team VALUES(default, '$teamname', '$season', $reminder, null, $lockout, $player_id, $linkstatseason, CURRENT_TIMESTAMP);";


if(mysqli_query($link, $insertTeam) == false)
	{
	echo "ERROR: Could not able to execute. " . mysqli_error($link) . "<BR>";
	}

$team_id = mysqli_insert_id($link);
echo $team_id . "<br>";

if ($commitData)	
	{
	/* commit insert */
	if (mysqli_commit($link))
		{
		$_SESSION['team_id'] = $team_id;

		//https://stackoverflow.com/questions/768431/how-to-make-a-redirect-in-php
           // window.location = "http://www.google.com/"
		$nextPath = "attend_members_main.php?team=$team_id&create=true&copyteam=$copyseason";
		echo '<script type="text/javascript">window.location = "' . $nextPath . '"</script>';
		}
	else
		{
		echo "Commit FAILED!!";
		}
	}
else
	{
	echo "Commit Skipped";
	}	
	
?>
