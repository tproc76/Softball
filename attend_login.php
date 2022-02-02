<?php
include 'db_setup.php';

if(isset($_POST['logcheck']))
	{
	$username=$_POST['user'];
	$password=$_POST['pwd'];
	
	if (strlen($username) < 2)
		{
		echo "fail";
		exit();
		}
	// should always be a 64 byte SHA256 hash
	if (strlen($password) < 62)
		{
		echo "fail";
		exit();
		}
	
	$findPass = "SELECT * FROM attend_players WHERE email = '$username'";

	if ($result = mysqli_query($link, $findPass) )
		{
		if($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
			if ($row['passhash'] == $password)
				{
				//https://www.formget.com/login-form-in-php/
				session_start();
				$_SESSION['login_user']=$username;
				$_SESSION['full_name']=$row['name'];
				$_SESSION['player_id']=$row['player_id'];
				echo "success";
				exit();
				}
			}	
		}
		
	echo "fail";
	exit();
	}

if(isset($_POST['forgot']))
	{
	$returnResults = "fail:open path";
	$username=$_POST['user'];
	
	$findPass = "SELECT * FROM attend_players WHERE email = '$username'";

	if ($result = mysqli_query($link, $findPass) )
		{
		if (mysqli_num_rows($result) > 0)
			{
			if($row = mysqli_fetch_array($result,MYSQLI_ASSOC))
				{				
				$fname = $row['name'];
				$pid = $row['player_id'];
				$message = "<html><head><title>Forgot Password</title></head><body>Hi $fname,<br><br>";
				$message .= "You requested to have your password reset on the proctorfamily softball website.<br>If this was you, click on the link below.  If not, you do not need to do anything.<br>";
				$message .= "The Link is only good for 24 hours.<br>";
				
				$datestamp = date('m-d-y H:i:s');
				$thestring = $username . $row['passhash'] . $datestamp;
				$linkhash = hash("sha256",$thestring);
				
				$message .= "http://proctorfamily.org/softball/attend_pwdreset.php?reset=" . $linkhash;
				$message .= "<br><br>The Softball Attendance Site<br></body></html>";
				
				$findExisting = "SELECT * FROM forgotpass WHERE player_id='$pid'";
				if ($result = mysqli_query($link, $findExisting) )
					{
					if (mysqli_num_rows($result) > 0)
						{
						$forgotPwd = "UPDATE forgotpass SET passhash='$linkhash',requested=CURRENT_TIMESTAMP WHERE player_id='$pid'";
						}
					else
						{
						$forgotPwd = "INSERT INTO forgotpass VALUES($pid,'$linkhash',CURRENT_TIMESTAMP)";
						}
				
					if ($result = mysqli_query($link, $forgotPwd) )
						{
						$subject = 'Password Reset for Softball Attendance';
						$headers[] = 'From: softball@proctorfamily.org';
						$headers[] = 'MIME-Version: 1.0';
						$headers[] = 'Content-type: text/html; charset=iso-8859-1';
						
						if (mail($username, $subject, $message, implode("\r\n",$headers)) == false)
							{
							$returnResults = "fail:sending email unsuccessful";
							}
						else
							{
							$returnResults = "success";
							}
						}
					}
				else
					{
					$returnResults = "fail:" . mysqli_error($link);
					}
				}
			}
		else
			{
			echo "fail:Email Not Found";
			exit();
			}
		}
	echo $returnResults;
	exit();
	}

if(isset($_POST['reset']))
	{
	$returnResults = "fail:open path";
	$resetID=$_POST['resetID'];
	$newPass=$_POST['pwd'];
	
	$findExisting = "SELECT * FROM forgotpass WHERE passhash='$resetID'";
	if ($result = mysqli_query($link, $findExisting) )
		{
		if (mysqli_num_rows($result) > 0)
			{
			$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
			$requestedTime = $row['requested'];
			$player_id = $row['player_id'];
			
			$returnResults = "fail:code not written";
			// CHeck that request was within the last 24 hours
			$comp_time = strtotime($requestedTime);
			$curr_time = time();
			
			if (($curr_time-$comp_time) < (30*60*60))  // 24 hours * 60 minutes * 60 seconds -- seems to be 6 hours off, so made it 30 hours
				{
				$updatePwd = "UPDATE attend_players SET passhash='$newPass' WHERE player_id='$player_id'";
				if ($result = mysqli_query($link, $updatePwd) )
					{
					$deleteRecord = "DELETE FROM forgotpass WHERE passhash='$resetID'";
					if ($result = mysqli_query($link, $deleteRecord) )
						{
						$returnResults = "success";
						}
					else
						{
						$returnResults = "fail:update del-" . mysqli_error($link);
						}
					}
				else
					{
					$returnResults = "fail:update pwd-" . mysqli_error($link);
					}
				}
			else
				{
				$returnResults = "fail:link expired";
				// maybe delete the entry
				}
			}
		else
			{
			echo "fail:Reset ID not found";
			exit();
			}
		}
	else
		{
		$returnResults = "fail:" . mysqli_error($link);
		}
	echo $returnResults;
	exit();
	}		
	
echo "unknown";
?>
