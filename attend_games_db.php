<?php
include 'db_setup.php';

if(isset($_POST['edit_row']))
{
	$team_id=$_POST['att_team'];
	$gdate=$_POST['att_date'];
	$loc=$_POST['att_loc'];
	$opp=$_POST['att_opp'];
	$gid=$_POST['row_id'];
	
	if (strlen($gdate) < 2)
		{
		echo "date";
		exit();
		}
	if (strlen($loc) < 2)
		{
		echo "loc";
		exit();
		}
	if (strlen($opp) < 2)
		{
		echo "opp";
		exit();
		}
	
	mysqli_query($link, "UPDATE attend_games SET game_datetime='$gdate',location='$loc',opponent='$opp' WHERE game_id='$gid'");
	echo "success";
	exit();
}

if(isset($_POST['delete_row']))
	{
	$row_no=$_POST['row_id'];
			
	mysqli_query($link, "DELETE FROM attend_games WHERE game_id='$row_no'");
	echo "success";
	exit();
	}

if(isset($_POST['insert_row']))
	{
	$team_id=$_POST['att_team'];
	$gdate=$_POST['att_date'];
	$loc=$_POST['att_loc'];
	$opp=$_POST['att_opp'];
	
	if (strlen($gdate) < 2)
		{
		echo "date";
		exit();
		}
	if (strlen($loc) < 2)
		{
		echo "loc";
		exit();
		}
	if (strlen($opp) < 2)
		{
		echo "opp";
		exit();
		}
		
	mysqli_query($link, "INSERT INTO attend_games VALUES($team_id, default,'$gdate','$loc','$opp',CURRENT_TIMESTAMP)");
	
	echo mysqli_insert_id($link);
	exit();
	}
	
echo "failed";
?>
