<?php
include 'db_setup.php';

if(isset($_POST['delete_row']))
	{
	$row_no=$_POST['row_id'];
	
	if (mysqli_query($link, "DELETE FROM games WHERE season_id = '$row_no'")==false)
		{
		echo "fail:" . mysqli_error($link);
		exit();
		}
				
	if (mysqli_query($link, "DELETE FROM seasons WHERE season_id = '$row_no'")==false)
		{
		echo "fail:" . mysqli_error($link);
		exit();
		}
				
	echo "success";
	exit();
	}

if(isset($_POST['edit_row']))
	{
	$team_id=$_POST['row_id'];
	$season=$_POST['team_season'];
	$name=$_POST['team_name'];
	$league=$_POST['team_league'];
	$lgroup=$_POST['team_group'];
	$year=$_POST['team_year'];
	$updated = "N/A";
	
	if (strlen($season) < 2)
		{
		echo "Fail:Season Name short";
		exit();
		}
	if (strlen($name) < 2)
		{
		echo "Fail:Team Name short";
		exit();
		}
	if (strlen($league) < 2)
		{
		echo "Fail:League Name short";
		exit();
		}
	if (strlen($year) != 4)
		{
		echo "Fail:Year length is not 4 digits";
		exit();
		}
		
	if(strlen($lgroup) < 1)
		$updateString = "UPDATE seasons SET season_name='$season',team_name='$name',league='$league',year='$year',grouping=NULL WHERE season_id='$team_id'";
	else
		$updateString = "UPDATE seasons SET season_name='$season',team_name='$name',league='$league',year='$year',grouping='$lgroup'	WHERE season_id='$team_id'";
	
	if (mysqli_query($link, $updateString) == false)
		{
		echo "fail:" . mysqli_error($link);
		exit();
		}
	else
		{
		if ($select = mysqli_query($link, "SELECT * FROM seasons WHERE season_id='$team_id'"))
			{
			$row=mysqli_fetch_array($select, MYSQLI_ASSOC);
			$updated = $row['updated'];
			}
		else
			{
			echo "fail:" . mysqli_error($link);
			exit();
			}
		}
		
	echo "success:" . $updated;
	exit();
	}

if(isset($_POST['get_games']))
	{
	$season_num=$_POST['row_id'];
	$SQLquery  = "SELECT * FROM games WHERE season_id=$season_num";

	$games = array();
	if ($result = mysqli_query($link, $SQLquery)) 
		{
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
			{
			$games[] = $row;
			}
		}
	
	header('Content-Type: application/json; charset=utf-8', true,200);
	header("Access-Control-Allow-Origin: *");
	echo json_encode($games);

	exit();
	}
	
if(isset($_POST['delete_game']))
	{
	$game_num=$_POST['game_id'];
	$SQLquery  = "DELETE FROM games WHERE game_id=$game_num";

	if ($result = mysqli_query($link, $SQLquery)) 
		{
		echo "success";
		}
	else
		{
		echo "fail:" . mysqli_error($link);
		exit();
		}
	
	exit();
	}
	
?>
