<?php
include 'db_setup.php';

if(isset($_POST['find_email']))
	{
	$email=$_POST['find'];

	$findPlayer = "SELECT * FROM attend_players WHERE email = '$email'";
	
	$result = mysqli_query($link, $findPlayer);
	
	if (mysqli_num_rows($result) > 1)
		{
		echo "multiple";
		exit();
		}
		
	if (mysqli_num_rows($result) == 0)
		{
		echo "none";
		exit();
		}
		
	$row = mysqli_fetch_array($result,MYSQLI_ASSOC);

	echo "success:" . $row['name'] . ":" . $row['player_id'];
	
	exit();
	}

if(isset($_POST['add_email']))
	{
	$email=$_POST['user'];
	$fname=$_POST['name'];
	
	// Add Team
	$insertPlayers = "INSERT INTO attend_players VALUES(default, '$fname', '$email', 'pass1234', CURRENT_TIMESTAMP)";

	if(mysqli_query($link, $insertPlayers))
		{
		$player_id = mysqli_insert_id($link);
		echo "success:$fname:$player_id";
		} 
	else
		{
		echo "ERROR: Could not able to execute. " . mysqli_error($link) . "<BR>";
		}
	exit();
	}

echo "No email post found";
?>
