<?php
include 'db_setup.php';

if(isset($_POST['delete_row']))
	{
	$row_no=$_POST['row_id'];
	
	$SQLQuery = "DELETE FROM attend_games WHERE team_id='$row_no'";
	if (mysqli_query($link, $SQLQuery) == false)
		{
		echo "ERROR: Could not able to execute. " . mysqli_error($link);	
		}
				
	$SQLQuery = "DELETE FROM attend_members WHERE team_id='$row_no'";
	if (mysqli_query($link, $SQLQuery) == false)
		{
		echo "ERROR: Could not able to execute. " . mysqli_error($link);	
		}
	
	$SQLQuery = "DELETE FROM attend_team WHERE team_id='$row_no'";
	if (mysqli_query($link, $SQLQuery) == false)
		{
		echo "ERROR: Could not able to execute. " . mysqli_error($link);	
		}
		
	echo "success";
	exit();
	}
?>
