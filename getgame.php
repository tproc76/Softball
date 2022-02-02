<?php

//Softball DB
include 'db_setup.php';

/* return name of current default database */
if ($result = mysqli_query($link, "SELECT DATABASE()")) {
    $row = mysqli_fetch_row($result);
    //printf("Default database is %s.\n", $row[0]);
    if ($row[0] != DB_DB) {
        printf("Connect failed: Wrong Database %s\n");
        exit();
    }
    mysqli_free_result($result);
}

$rows = array();
$game_name = $_GET["game"];

header('Content-Type: application/json; charset=utf-8', true,200);

$SQLquery  = "SELECT * FROM hitters h JOIN players p ON h.player_id=p.player_id JOIN games g ON g.game_id=h.game_id WHERE g.game_id=$game_name ORDER BY bat_order,sub";

if ($result = mysqli_query($link, $SQLquery)) 
	{
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
        $hitters[] = $row;
        }
    }

$SQLquery  = "SELECT * FROM pitchers t JOIN players p ON t.player_id=p.player_id JOIN games g ON g.game_id=t.game_id WHERE g.game_id=$game_name";

if ($result = mysqli_query($link, $SQLquery)) 
	{
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
        $pitchers[] = $row;
        }
    }
	
$SQLquery  = "SELECT * FROM games WHERE game_id=$game_name";

if ($result = mysqli_query($link, $SQLquery)) 
	{
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
		$games[] = $row;
		}
	}

$season_id = $games[0]['season_id'];
$findSeason  = "SELECT * FROM seasons WHERE season_id = '$season_id'";

$result = mysqli_query($link, $findSeason);
if (mysqli_num_rows($result) == 0)
{
	echo "Season Not Found - " . $findSeason;
	exit();
}

$season = mysqli_fetch_array($result,MYSQLI_ASSOC);

$seasonData['hitters'] = $hitters;
$seasonData['pitchers'] = $pitchers;
$seasonData['games'] = $games;
$seasonData['season'] = $season;
	
header("Access-Control-Allow-Origin: *");
echo json_encode($seasonData);

// Close connection
mysqli_close($link);
?>
