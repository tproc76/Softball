<?php
include 'db_setup.php';

$sendEmail = true;

$returnResults = array();
$returnStatus = "fail:no action";

$successOK = false;

$findGames = "SELECT g.game_id,g.game_datetime,t.reminderdays FROM attend_games g JOIN attend_team t ON t.team_id=g.team_id WHERE DATEDIFF(g.game_datetime,CURDATE())=t.reminderdays";

if ($gresult = mysqli_query($link, $findGames))
	{
	$headers[] = 'From: softball@proctorfamily.org';
	$headers[] = 'MIME-Version: 1.0';
	$headers[] = 'Content-type: text/html; charset=iso-8859-1';
	$headers[] = 'CC: procgod@yahoo.com';
	$recount = mysqli_num_rows($gresult);
	echo $recount . "<br>";
	
	while ($gamerow = mysqli_fetch_array($gresult, MYSQLI_ASSOC)) 
		{
		$returnGateResults[] = $gamerow;
		$game_id = $gamerow['game_id'];

		$message = "<html><head><title>Softball Attendance</title><style type='text/css'> .centerText{text-align: center;}</style></head><body>Hey Team,<br><br>";
		
		$findPlayer = "SELECT * FROM attend_team t JOIN attend_games g ON t.team_id=g.team_id WHERE game_id='$game_id'";

		if ($sgresult = mysqli_query($link, $findPlayer))
			{
			if (mysqli_num_rows($sgresult) > 0)
				{
				$sgrow = mysqli_fetch_array($sgresult, MYSQLI_ASSOC);
				$teamid = $sgrow['team_id'];
				$teamname = $sgrow['team_name'];
				$glocation = $sgrow['location'];
				$gdatetime = date_create($sgrow['game_datetime']);
				$gopponent = $sgrow['opponent'];

				// Need full team list.... not just those who replied..... - 2 select statements worked best
				//   joins place nicer when you don't select all.
				$findPlayers = "SELECT * FROM ";
				$findPlayers .= "(SELECT m.player_id AS player_id, m.role AS role, p.name AS name, p.email AS email FROM attend_members m JOIN attend_players p ON m.player_id=p.player_id WHERE m.team_id=$teamid) AS s1 ";
				$findPlayers .= " LEFT JOIN (SELECT player_id AS pida, attending, notes FROM attendance WHERE game_id=$game_id) AS s2 ON s1.player_id=s2.pida";

				if ($result = mysqli_query($link, $findPlayers))
					{
					$maillist = "";
					$noreplylist = array();
					$yeslist = array();
					$nolist =  array();
					$maybelist = array();
					$latelist = array();
					$totalCount = 0;
					$noreplycount = 0;
					$yescount = 0;
					$nocount = 0;
					$maybecount = 0;
					$latecount = 0;
					$role = 0;
					
					$noreplylist[0] = $noreplylist[1] = "";
					$yeslist[0] = $yeslist[1] = "";
					$nolist[0] = $nolist[1] = "";
					$maybelist[0] = $maybelist[1] = "";
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
						else
							{
							$role = 0;	
							}

						if ($row['notes']!=null)
							{
							$name .= " (" . $row['notes'] . ")";
							}
							
						if ($row['attending']==null)
							{
							$noreplylist[$role] .= $row['name'] . "<br>";
							$noreplycount = $noreplycount+1;
							}
						else if ($row['attending']=="Y")
							{
							$yeslist[$role] .= $row['name'] . "<br>";
							$yescount = $yescount+1;
							}
						else if ($row['attending']=="N")
							{
							$nolist[$role] .= $row['name'] . "<br>";
							$nocount = $nocount+1;
							}
						else if ($row['attending']=="L")
							{
							$latelist[$role] .= $name . "<br>";
							$latecount = $latecount+1;
							}
						else //if ($row['attending']=="?")
							{
							$maybelist[$role] .= $row['name'] . "<br>";
							$maybecount = $maybecount+1;
							}
						}
						
					// - http://php.net/manual/en/function.mail.php	
					// simple email test below - production one should be HTML and include the link to the page - example #4.
//					$to      = 'proc@comcast.net';
					$subject = 'Softball Attendance for Upcoming Game';

					$message .= "This is a reminder/sense check for this week's game for $teamname.<br>";
					$message .= "<b>Date/Time:</b>". date_format($gdatetime,"M-d-Y G:i") . "<br>";
					$message .= "<b>Location:</b> $glocation <br>";
					$message .= "<b>Opponent:</b> $gopponent <br><br>";
					$message .= "<h2><u>Yes ($yescount)</u></h2>" . $yeslist[0] . $yeslist[1] . "<br><br>";
					$message .= "<h2><u>No ($nocount)</u></h2>" . $nolist[0] . $nolist[1] . "<br><br>";
					$message .= "<h2><u>Late ($latecount)</u></h2>" . $latelist[0] . $latelist[1] . "<br><br>";
					$message .= "<h2><u>Maybe ($maybecount)</u></h2>" . $maybelist[0] . $maybelist[1] . "<br><br>";
					$message .= "<h2><u>No Reply ($noreplycount)</u></h2>" . $noreplylist[0] . $noreplylist[1] . "<br><br>";
					$message .= "<p style='text-align:center'>To update your status, please <a href='http://proctorfamily.org/softball/attend_login.html'>log here.</a>";
					$message .= "<br><br>The Softball Attendance Site<br></p></body></html>";


					if ($sendEmail==true)
						{
						echo "<br><br><b><u>Send Email</b></u><br><br>";							
						if (mail($maillist, $subject, $message, implode("\r\n",$headers)) == false)
							{
							/* Just in case there are other steps later or debugging required*/
							$returnStatus = "fail:email - " . error_get_last()['message'];
							echo $returnStatus;
							echo print_r(error_get_last());
							}
						else
							{
							echo "Success Email sent for " . date_format($gdatetime,"M-d-Y G:i") . "<br><br>";
							$returnStatus = "Success";
							}
						}
					else
						{
						$returnStatus = "Email Bypassed<BR>";
						echo $message;
						}
					}
				else
					{
					$returnStatus = "fail:get players -" . mysqli_error($link) . "<BR>";
					echo $returnStatus;
					}
				}
			}
		else
			{
			$returnStatus = "fail:get team -" . mysqli_error($link) . "<BR>";
			echo $returnStatus;
			}
		}
	}
else
	{
	$returnStatus = "fail:reminder days-" . mysqli_error($link) . "<BR>";
	}
	
//echo $returnStatus;
//echo json_encode($returnResults);

mysqli_close($link);
?>
