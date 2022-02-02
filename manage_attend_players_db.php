<?php
include 'db_setup.php';

if(isset($_POST['edit_row']))
{
	$pid=$_POST['row_id'];
	$email=$_POST['name_email'];
	$fullname=$_POST['name_full'];
	$phone=$_POST['name_phone'];
	
	$findMember = "SELECT * FROM attend_players WHERE email = '$email'";
	
	$result = mysqli_query($link, $findMember);
	
	$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
	
	if ((mysqli_num_rows($result) > 0) && ($row['player_id']!=$pid))
		{
		echo "duplicate";
		exit();
		}	

	mysqli_query($link, "UPDATE attend_players SET email='$email',name='$fullname',phone='$phone' WHERE player_id='$pid'");
	echo "success";
	exit();
}

if(isset($_POST['delete_row']))
	{
	$row_no=$_POST['row_id'];
	
	$findMember = "SELECT * FROM attend_members m JOIN attend_team t ON t.team_id=m.team_id WHERE player_id = '$row_no'";
	
	$result = mysqli_query($link, $findMember);
	
	if (mysqli_num_rows($result) > 0)
		{
		$row = mysqli_fetch_array($result,MYSQLI_ASSOC);
		echo "member:".$row['team_name'];
		exit();
		}
				
	mysqli_query($link, "DELETE FROM attend_players WHERE player_id='$row_no'");
	echo "success";
	exit();
	}

if(isset($_POST['insert_row']))
	{
	$email=$_POST['name_email'];
	$fullname=$_POST['name_full'];
	$phone=$_POST['name_phone'];
	$pwdhash = hash("sha256","pass1234");
	
	$findMember = "SELECT * FROM attend_players WHERE email = '$email'";
	
	$result = mysqli_query($link, $findMember);
	
	if (mysqli_num_rows($result) > 0)
		{
		echo "duplicate";
		exit();
		}	
	
	if(mysqli_query($link, "INSERT INTO attend_players VALUES(default,'$fullname','$email','$pwdhash','$phone',CURRENT_TIMESTAMP)")==false)
		{
		echo "ERROR: Could not able to execute. " . mysqli_error($link);
		}
	else
		{
		echo mysqli_insert_id($link);
		}
	exit();
	}
?>
