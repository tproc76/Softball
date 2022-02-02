<?php
session_start();
include 'db_setup.php';

$useremail = $_SESSION['login_user'];
$username = $_SESSION['full_name'];
$returnResults = array();
$returnStatus = "fail:no action";

$team_id=$_GET['teamid'];
$game_id=$_GET['gameNums'];
$successOK = false;

//$message = "<html><head><title>Softball Attendance</title><style type='text/css'> .centerText{text-align: center;}</style></head><body>Hey $username,<br><br>";
$message = "";	
	
$findGame = "SELECT * FROM attend_team t JOIN attend_games g ON t.team_id=g.team_id WHERE game_id='$game_id'";

if ($result = mysqli_query($link, $findGame))
	{
	if (mysqli_num_rows($result) > 0)
		{
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		$teamname = $row['team_name'];
		$glocation = $row['location'];
		$gdatetime = $row['game_datetime'];
		$gopponent = $row['opponent'];
		}
	else
		{
		echo "fail: Game ID = $game_id";
		exit();
		}
	}

// Need full team list.... not just those who replied..... - 2 select statements worked best
//   joins play nicer when you don't select all.
$findPlayers = "SELECT * FROM ";
$findPlayers .= "(SELECT m.player_id AS player_id, m.role AS role, p.name AS name, p.email AS email FROM attend_members m JOIN attend_players p ON m.player_id=p.player_id WHERE m.team_id=$team_id) AS s1 ";
$findPlayers .= " LEFT JOIN (SELECT player_id AS pida, attending, notes FROM attendance WHERE game_id=$game_id) AS s2 ON s1.player_id=s2.pida";

if ($result = mysqli_query($link, $findPlayers))
	{
	$maillist = "";
	$noreplylist = array();
	$yeslist = array();
	$nolist = array();
	$maybelist = array();
	$latelist = array();
	$totalCount = 0;
	$noreplycount = 0;
	$yescount = 0;
	$nocount = 0;
	$maybecount = 0;
	$latecount = 0;
	$role = 0;
	
	$yeslist[0] = $yeslist[1] = "";
	$nolist[0] = $nolist[1] = "";
	$maybelist[0] = $maybelist[1] = "";
	$noreplylist[0] = $noreplylist[1] = "";
	$latelist[0] = $latelist[1] = "";
	
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
		$returnResults[] = $row;
		
		if ($totalCount>0)
			{
			$maillist .= ", ";
			}
		$maillist .= $row['email'];
		$totalCount = $totalCount+1;
		$name = $row['name'];
		
		if ($row['role'] == 'S')
			{
			$role = 1;	
			}
			
		if ($row['notes']!=null)
			{
			$name .= " (" . $row['notes'] . ")";
			}

		if ($row['attending']==null)
			{
			$noreplylist[$role] .= $name . "<br>";
			$noreplycount = $noreplycount+1;
			}
		else if ($row['attending']=="Y")
			{
			$yeslist[$role] .= $name . "<br>";
			$yescount = $yescount+1;
			}
		else if ($row['attending']=="N")
			{
			$nolist[$role] .= $name . "<br>";
			$nocount = $nocount+1;
			}
		else if ($row['attending']=="L")
			{
			$latelist[$role] .= $name . "<br>";
			$latecount = $latecount+1;
			}
		else //if ($row['attending']=="?")
			{
			$maybelist[$role] .= $name . "<br>";
			$maybecount = $nocount+1;
			}
		}
		
	// - http://php.net/manual/en/function.mail.php	
	// simple email test below - production one should be HTML and include the link to the page - example #4.
//	$to      = 'proc@comcast.net';
	$subject = 'Softball Attendance for Upcoming Game';
	$headers[] = 'From: softball@proctorfamily.org';
	$headers[] = 'MIME-Version: 1.0';
	$headers[] = 'Content-type: text/html; charset=iso-8859-1';

	$message .= "Current status for this game as requested for the $teamname.<br>";
	$message .= "<b>Date/Time:</b> $gdatetime <br>";
	$message .= "<b>Location:</b> $glocation <br>";
	$message .= "<b>Opponent:</b> $gopponent <br><br>";
	$message .= "<h2><u>Yes ($yescount)</u></h2>" . $yeslist[0] . $yeslist[1] . "<br><br>";
	$message .= "<h2><u>No ($nocount)</u></h2>" . $nolist[0] . $nolist[1] . "<br><br>";
	$message .= "<h2><u>Late ($latecount)</u></h2>" . $latelist[0] . $latelist[1] . "<br><br>";
	$message .= "<h2><u>Maybe ($maybecount)</u></h2>" . $maybelist[0] . $maybelist[1] . "<br><br>";
	$message .= "<h2><u>No Reply ($noreplycount)</u></h2>" . $noreplylist[0] . $noreplylist[1] . "<br><br>";
	$message .= "<p style='text-align:center'>To update your status, please <a href='http://proctorfamily.org/softball/attend_login.html'>log here.</a>";
	$message .= "<br> Team Mail List - " . $maillist;
	$message .= "<br><br>The Softball Attendance Site<br></p></body></html>";
	
	echo "<html><head><title>Softball Attendance</title><style type='text/css'> .centerText{text-align: center;}</style></head><body>Hey $username,<br><br>";
	echo "To:";
	echo $maillist;
	echo "<br><br>Subject:";
	echo $subject;
	echo "<br><br>";
	echo $message;
	echo "</body>";
	
//echo json_encode($returnResults);
	}
	
mysqli_close($link);
?>
