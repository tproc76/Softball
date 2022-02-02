<?php
include 'db_setup.php';

if(isset($_POST['edit_row']))
{
	$pid=$_POST['row_id'];
	$nickname=$_POST['name_nick'];
	$fullname=$_POST['name_full'];
	$notes=$_POST['name_note'];

	mysqli_query($link, "UPDATE players SET nickname='$nickname',fullname='$fullname',notes='$notes' WHERE player_id='$pid'");
	echo "success";
	exit();
}

if(isset($_POST['delete_row']))
	{
	$row_no=$_POST['row_id'];
	
	$findHitter = "SELECT * FROM hitters WHERE player_id = '$row_no'";
	$findPitcher = "SELECT * FROM pitchers WHERE player_id = '$row_no'";
	
	$result = mysqli_query($link, $findHitter);
	
	if (mysqli_num_rows($result) > 0)
		{
		echo "hitter";
		exit();
		}
		
 	$result = mysqli_query($link, $findPitcher);
	
	if (mysqli_num_rows($result) > 0)
		{
		echo "pitcher";
		exit();
		}
		
	mysqli_query($link, "DELETE FROM players WHERE player_id='$row_no'");
	echo "success";
	exit();
	}

if(isset($_POST['insert_row']))
	{
	$nickname=$_POST['name_nick'];
	$fullname=$_POST['name_full'];
	$notes=$_POST['name_note'];
	mysqli_query($link, "INSERT INTO players VALUES(default,'$nickname','$fullname','$notes')");
	
	echo mysqli_insert_id($link);
	exit();
	}
?>
