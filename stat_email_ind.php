<?php
include 'db_setup.php';
echo '<link rel="stylesheet" type="text/css" href="page_format.css">';
$sendEmail = true;

$returnResults = array();
$returnStatus = "fail:no action";

$successOK = false;

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
		
		if ($theStatTeam != null)
			{		
			$statteamid = $theStatTeam['team_id'];
			
			$findStatInfo = "SELECT p.email AS email FROM attend_players p JOIN attend_members m ON p.player_id=m.player_id WHERE m.team_id='$statteamid'";
			
			if ($result = mysqli_query($link, $findStatInfo))
				{
				echo "<br><br><b><u>Sending Emails</b></u><br><br>";							
				
				while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
					{
					$returnResults[] = $row;
					
					$maillist = $row['email'];
						
					$headers[] = 'From: softball@proctorfamily.org';
					$headers[] = 'MIME-Version: 1.0';
					$headers[] = 'Content-type: text/html; charset=iso-8859-1';
					// - http://php.net/manual/en/function.mail.php	
					// simple email test below - production one should be HTML and include the link to the page - example #4.
					//		$maillist      = 'proc@comcast.net';
					$subject = 'Stats for $team_name';

					$message = "Hey Team,<br><br>";
					$message .= "<br>Stats for $team_name was updated for $season_name.<br>";
					$message .= "<a href='http://proctorfamily.org/softball/season.php?season=$season_name'> Stats Page </a> ";
		//			$message .= "<a href='http://localhost/season.php?season=$season_name'> Stats Page </a> ";
					$message .= "<br><br>The Softball Attendance Site<br></p></body></html>";

					echo $maillist." - ";
					
					if ($sendEmail==true)
						{
						if (mail($maillist, $subject, $message, implode("\r\n",$headers)) == false)
							{
							/* Just in case there are other steps later or debugging required*/
							$returnStatus = "fail:email - " . error_get_last()['message'];
							echo $returnStatus."<br>";
							}
						else
							{
							echo "Success Email sent for " . date_format($gdatetime,"M-d-Y G:i") . "<br><br>";
							$returnStatus = "success";
							}
						}
					else
						{
						$returnStatus = "Email Bypassed<BR>";
						echo $maillist."<br>";
						echo $message;
						}
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
			echo "<b><u>FAIL - STAT TEAM not associated with Attendace team(email addresses) </b></u><br>";
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
	
echo "<br><a href='maintainance.php'>Return to Maintainance Page</a><br>";


echo "<br>";
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

	
//echo $returnStatus;
//echo json_encode($returnResults);

mysqli_close($link);
?>
