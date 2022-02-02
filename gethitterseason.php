<?php
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
$season_num = $_GET["season"];
$player_num = $_GET["player"];

$findSeason  = "SELECT * FROM seasons WHERE season_id = '$season_num'";

$result = mysqli_query($link, $findSeason);
if (mysqli_num_rows($result) == 0)
{
	echo "Season Not Found - " . $findSeason;
	exit();
}

$prows = mysqli_fetch_array($result,MYSQLI_ASSOC);
$season_row = $prows;

header('Content-Type: application/json; charset=utf-8', true,200);

$SQLquery  = "SELECT * FROM hitters h JOIN players p ON h.player_id=p.player_id JOIN games g ON g.game_id=h.game_id WHERE g.season_id=$season_num AND p.player_id=$player_num ORDER BY g.game_datetime";

if ($result = mysqli_query($link, $SQLquery)) 
	{
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
        $hitters[] = $row;
        }
    }

$seasonData['hitters'] = $hitters;
$seasonData['season'] = $season_row;
	
header("Access-Control-Allow-Origin: *");
echo json_encode($seasonData);

// Close connection
mysqli_close($link);
?>
