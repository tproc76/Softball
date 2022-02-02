<?php
session_start();
include 'db_setup.php';
$returnResults = "fail:Unknown";

if(isset($_GET['add_mem']))
	{
	$email=$_GET['user'];
	$fname=$_GET['fname'];
	$manager=$_GET['mgr'];
	$subst=$_GET['sub'];
	$team_id=$_GET['teamid'];
	$mgrStat='X';
	$successOK = false;
	$sendEmail = false;
	$bypassEmail = false;
	
	$message = "<html><head><title>Softball Attendance</title></head><body>Welcome $fname,<br><br>";
	
	// t@h.ca <- Still not valid, but this is where 6 is coming from, other validation should happen before this call
	if (strlen($email) < 6)
		{
		echo json_encode("fail:Email Length Too Short " . $email);
		exit();
		}
		
	if ($manager=='true')
		{
		$mgrStat='M';
		}
	else if ($subst=='true')
		{
		$mgrStat='S';
		}
		
	$findPlayer = "SELECT * FROM attend_team WHERE team_id = '$team_id'";
	if ($result = mysqli_query($link, $findPlayer))
		{
		if (mysqli_num_rows($result) > 0)
			{
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			$teamname = $row['team_name'];
			}
		else
			{
			echo json_encode("fail:Team Not Found.  Team ID = $team_id");
			exit();
			}
		}
		
	$findPlayer = "SELECT * FROM attend_players WHERE email = '$email'";
	if ($result = mysqli_query($link, $findPlayer))
		{
		if (mysqli_num_rows($result) == 0)
			{
			if (strlen($fname) < 2)
				{
				echo json_encode("fail:Full Name Too Short");
				exit();
				}
				
			$pwdhash = hash("sha256","pass1234");
			$insertPlay = "INSERT INTO attend_players VALUES(default, '$fname','$email','$pwdhash', null, CURRENT_TIMESTAMP);";	
			if ($result = mysqli_query($link, $insertPlay))
				{
				$player_id = mysqli_insert_id($link);
			
				$successOK = true;
				$sendEmail = true;

				$message .= "A new account was created on for you on the attendance tracking portion of the proctorfamily Softball Atendance Site.<br>";
				$message .= "Your email address is your log in and your initial password is <b>pass1234</b>.  You may change it once you log in under the account settings.<br><br>";
				}
			else
				{
				$successOK = false;
				$returnResults = "fail:" . mysqli_error($link);
				}
			}
		else
			{
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
				
			$player_id = $row["player_id"];
			$successOK = true;
			}
		}
	else
		{
		echo json_encode("fail:" . mysqli_error($link));
		exit();
		}			
			
	if ($successOK == true)
		{
		$findMember = "SELECT * FROM attend_members WHERE team_id='$team_id' AND player_id='$player_id'";
		if ($result = mysqli_query($link, $findMember))
			{
			if (mysqli_num_rows($result) == 0)
				{
				$insertMember = "INSERT INTO attend_members VALUES($team_id, $player_id, '$mgrStat', CURRENT_TIMESTAMP)";
				if ($result = mysqli_query($link, $insertMember))
					{
					$message .= "You have been added to the $teamname team by " . $_SESSION['full_name'] . ".<br>";
					$message .= "The <a herf='http://proctorfamily.org/softball/attend_login.html'>Login page</a> is http://proctorfamily.org/softball/attend_login.html to fill in your game attendance.";
					$sendEmail = true;
					$returnResults = "success";
					}
				else
					{
					$successOK = false;
					$returnResults = "fail:" . mysqli_error($link);
					}
				}
			else
				{
				$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			
				if ($row['role']!=$mgrStat)
					{					
					$updateMember = "UPDATE attend_members SET role='$mgrStat', updated=CURRENT_TIMESTAMP WHERE team_id='$team_id' AND player_id='$player_id'";
					if ($result = mysqli_query($link, $updateMember))
						{
						// Do not send email for just a role change
						$returnResults = "success";
						}
					else
						{
						$successOK = false;
						$returnResults = "fail:" . error_get_last();
						}
					}
				else
					{
					$returnResults = "success";
					}
				}
			}
		
		if	($successOK == true)
			{
			if ($sendEmail == true)
				{
				// Moved for Bypass output success.
				$message .= "<br><br>The Softball Attendance Site<br></body></html>";
				// - http://php.net/manual/en/function.mail.php	
				// simple email test below - production one should be HTML and include the link to the page - example #4.
				$subject = "Welcome to Softball Attendance - $teamname";
				
				if ($bypassEmail == false)
					{
					$headers[] = 'From: softball@proctorfamily.org';
					$headers[] = 'MIME-Version: 1.0';
					$headers[] = 'Content-type: text/html; charset=iso-8859-1';
					
					if (mail($email, $subject, $message, implode("\r\n",$headers)) == false)
						{
						// Failing to get good errors here.
						$returnResults = "fail:email"; //error_get_last
						// Just in case there are other steps later.
						$successOK = false;
						}
					}
				else
					{
					echo "Send Email Bypassed<br>";
					echo "Subject: " . $subject . "<br>";
					echo $message;
					}
				}
			}
		}
	}
	
if(isset($_GET['get_mem']))
	{
	$team_id=$_GET['teamid'];
	
	$findPlayer = "SELECT m.player_id 'player_id', m.role 'role', p.name 'name', p.email 'email' FROM attend_members m JOIN attend_players p ON p.player_id=m.player_id WHERE team_id = '$team_id'";
	
	if ($result = mysqli_query($link, $findPlayer))
		{
		if (mysqli_num_rows($result) > 0)
			{
			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
				{
				$players[] = $row;
				}
			}
		else
			{
			$players = "no results";
			}
			$returnResults = $players;
		}
	else
		{
		$returnResults = "fail:" . mysqli_error($link);
		}
	}

if(isset($_GET['del_mem']))
	{
	$team_id=$_GET['teamid'];
	$email=$_GET['email'];
	
	// Find player ID
	$findPlayer = "SELECT player_id FROM attend_players WHERE email = '$email'";
	
	if ($result = mysqli_query($link, $findPlayer))
		{
		if (mysqli_num_rows($result) > 0)
			{
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			$player_id = $row['player_id'];
			$deleteRecord = "DELETE FROM attend_members WHERE team_id=$team_id AND player_id=$player_id";

			if ($result = mysqli_query($link, $deleteRecord))
				{
				$returnResults = "success:removed";
				}	
			else
				{
				$returnResults = "fail:" . mysqli_error($link);
				}			
			}
		else
			{
			$returnResults = "success:unknown email";
			}
		}			
	else
		{
		$returnResults = "fail:" . mysqli_error($link);
		}
	}	
	
if(isset($_POST['update_game']))
	{
	$pid = $_POST['player_id'];
	$gid = $_POST['game_id'];
	$status =$_POST['status'];
	$note =$_POST['note'];
	
	if ($status=='Yes')
		{
		$db_stat = 'Y';
		}
	else if ($status=='No')
		{
		$db_stat = 'N';
		}	
	else if ($status=='Late')
		{
		$db_stat = 'L';
		}	
	else
		{
		$db_stat = '?';
		}	
		
		
	$findRecord = "SELECT * FROM attendance WHERE game_id=$gid AND player_id=$pid";
	
	if ($result = mysqli_query($link, $findRecord ))
		{
		if (mysqli_num_rows($result) > 0)
			{
			if ($note=="")
				$setAttending = "UPDATE attendance SET attending='$db_stat',notes=null,updated=CURRENT_TIMESTAMP WHERE game_id=$gid AND player_id=$pid";
			else
				$setAttending = "UPDATE attendance SET attending='$db_stat',notes='$note',updated=CURRENT_TIMESTAMP WHERE game_id=$gid AND player_id=$pid";
			}
		else
			{
			if ($note=="")
				$setAttending = "INSERT INTO attendance VALUES($gid,$pid,'$db_stat',null,CURRENT_TIMESTAMP)";
			else
				$setAttending = "INSERT INTO attendance VALUES($gid,$pid,'$db_stat','$note',CURRENT_TIMESTAMP)";
			}
			
		if ($result = mysqli_query($link, $setAttending))
			{
			$returnResults = "success";
			}
		else
			{
			$returnResults = "fail:" . mysqli_error($link);
			}
		}
	else
		{
		$returnResults = "fail:" . mysqli_error($link);
		}
	
	// echo and return here being this is an AJAX call - do not use JSON
	echo $returnResults;
	exit();
	}
	
if(isset($_POST['update_comment']))
	{
	$pid = $_POST['player_id'];
	$gid = $_POST['game_id'];
	$note =$_POST['note'];
	
	$findRecord = "SELECT * FROM attendance WHERE game_id=$gid AND player_id=$pid";
	
	if ($result = mysqli_query($link, $findRecord ))
		{
		if (mysqli_num_rows($result) > 0)
			{
			if ($note=="")
				$setAttending = "UPDATE attendance SET notes=null,updated=CURRENT_TIMESTAMP WHERE game_id=$gid AND player_id=$pid";
			else
				$setAttending = "UPDATE attendance SET notes='$note',updated=CURRENT_TIMESTAMP WHERE game_id=$gid AND player_id=$pid";
			}
		else
			{
			// echo and return here being this is an AJAX call - do not use JSON
			$returnResults = "fail: no Attendance status for this game";
			echo $returnResults;
			exit();
			}
			
		if ($result = mysqli_query($link, $setAttending))
			{
			$returnResults = "success";
			}
		else
			{
			$returnResults = "fail:" . mysqli_error($link);
			}
		}
	else
		{
		$returnResults = "fail:" . mysqli_error($link);
		}
	
	// echo and return here being this is an AJAX call - do not use JSON
	echo $returnResults;
	exit();
	}
	

if(isset($_POST['update_player']))
	{
	$pid = $_SESSION['player_id'];
	$fname = $_POST['fname'];
	$pwdupdate = $_POST['pup'];
	$pwdhash = $_POST['pwdhash'];
	
	$updateName = "UPDATE attend_players SET name='$fname',updated=CURRENT_TIMESTAMP WHERE player_id='$pid'";
	if ($result = mysqli_query($link, $updateName))
		{
		if ($pwdupdate=="true")
			{
			$updatePwd = "UPDATE attend_players SET passhash='$pwdhash',updated=CURRENT_TIMESTAMP WHERE player_id='$pid'";
			if ($result = mysqli_query($link, $updatePwd))
				{
				echo "success:pwd";
				exit();
				}
			else
				{
				echo "fail:" . mysqli_error($link);
				exit();
				}
			}
		else
			{
			echo "success:nopwd";
			exit();
			}
		}
	else
		{
		echo "fail:" . mysqli_error($link);
		exit();
		}
	}
	
header('Content-Type: application/json; charset=utf-8', true,200);
header("Access-Control-Allow-Origin: *");

echo json_encode($returnResults);

mysqli_close($link);
?>
