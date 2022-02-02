<?php
include 'db_setup.php';

$sendEmail = true;

$returnResults = array();
$returnStatus = "fail:no action";

$successOK = false;
$totalCount = 0;

$season_id=$_GET['seasonid'];


$findTeamInfo = "SELECT season_name,team_name FROM seasons WHERE season_id='$season_id'";

if ($result = mysqli_query($link, $findTeamInfo))
	{
	$theStatTeam = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$season_name = $theStatTeam['season_name'];
	$team_name = $theStatTeam['team_name'];

	$findStatInfo = "SELECT team_id FROM attend_team WHERE stats_team_id='$season_id'";

	if ($result = mysqli_query($link, $findStatInfo))
		{
		$theStatTeam = mysqli_fetch_array($result, MYSQLI_ASSOC);
		
		$statteamid = $theStatTeam['team_id'];
		
		$findStatInfo = "SELECT p.email AS email FROM attend_players p JOIN attend_members m ON p.player_id=m.player_id WHERE m.team_id='$statteamid'";
		
		if ($result = mysqli_query($link, $findStatInfo))
			{
			$maillist = "";
			
			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
				{
				$returnResults[] = $row;
				
				if ($totalCount>0)
					{
					$maillist .= ", ";
					}
					
				$maillist .= $row['email'];
				$totalCount = $totalCount+1;
				}
				
			$headers[] = 'From: softball@proctorfamily.org';
			$headers[] = 'MIME-Version: 1.0';
			$headers[] = 'Content-type: text/html; charset=iso-8859-1';
			// - http://php.net/manual/en/function.mail.php	
			// simple email test below - production one should be HTML and include the link to the page - example #4.
			//		$maillist      = 'proc@comcast.net';
			$subject = "Stats for $team_name";

			$message = "Hey Team,<br>";
			$message .= "<br>Stats for $team_name was updated for $season_name.<br>";
			$message .= "<a href='http://proctorfamily.org/softball/season.php?season=$season_name'> Stats Page </a> ";
//			$message .= "<a href='http://localhost/season.php?season=$season_name'> Stats Page </a> ";
			$message .= "<br><br>The Softball Attendance Site<br></p></body></html>";

			if ($sendEmail==true)
				{						
				if (mail($maillist, $subject, $message, implode("\r\n",$headers)) == false)
					{
					/* Just in case there are other steps later or debugging required*/
					$returnStatus = "fail:email - " . error_get_last()['message'];
					echo $returnStatus;
					echo print_r(error_get_last());
					}
				else
					{
					echo "Success Email sent <br><br>";
					$returnStatus = "success";
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
			$returnStatus = "fail:to get email addreses -" . mysqli_error($link) . "<BR>";
			echo $returnStatus;
			}
		}	
	else
		{
		$returnStatus = "fail:get attendance teamid  -" . mysqli_error($link) . "<BR>";
		echo $returnStatus;
		}
	}
else
	{
	$returnStatus = "fail:get stat team-" . mysqli_error($link) . "<BR>";
	}
	
//echo $returnStatus;
//echo json_encode($returnResults);

mysqli_close($link);
?>
