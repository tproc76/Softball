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
$season_name = $_GET["season"];
//$season_name = "Co-ed Fall 2003";

$findSeason  = "SELECT * FROM seasons WHERE season_name = '$season_name'";

$result = mysqli_query($link, $findSeason);
if (mysqli_num_rows($result) == 0)
{
	echo "Season Not Found - " . $findSeason;
	exit();
}

$prows = mysqli_fetch_array($result,MYSQLI_ASSOC);
$season_sett = $prows;
$season_num = $prows["season_id"];
$team_name = $prows["team_name"];
$updated = $prows["updated"];

//echo $season_num;

header('Content-Type: application/json; charset=utf-8', true,200);

$SQLquery  = "SELECT p.nickname, p.player_id, g.game_id, g.game_datetime, g.opponent, game, plateapp, atbats, runs, hits, singles, doubles, ";
 $SQLquery  .= "triples, homeruns, rbi, walks, strikeout, strikeoutswing, strikeoutlook, ";
 $SQLquery  .= "sacrifice, steal, caughtstealing, hitbypitch, reachonerror ";
 $SQLquery  .= "FROM hitters h JOIN players p ON h.player_id=p.player_id JOIN games g ON g.game_id=h.game_id WHERE g.season_id=$season_num ";
 $SQLquery  .= "ORDER BY p.nickname, g.game_datetime";

if ($result = mysqli_query($link, $SQLquery)) 
	{
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
        $hitters[] = $row;
        }
    }

$SQLquery  = "SELECT * FROM games WHERE season_id = $season_num ORDER BY game_datetime";

if ($result = mysqli_query($link, $SQLquery)) 
	{
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
		$games[] = $row;
		}
	}
	
$SQLquery  = "SELECT DISTINCT p.nickname FROM hitters h JOIN players p ON h.player_id=p.player_id JOIN games g ON g.game_id=h.game_id WHERE g.season_id=$season_num ";
$SQLquery  .= "ORDER BY p.nickname";

if ($result = mysqli_query($link, $SQLquery)) 
	{
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
        $players[] = $row;
        }
    }

$seasonData['hitters'] = $hitters;
$seasonData['games'] = $games;			
$seasonData['season'] = $season_sett;
$seasonData['team'] = $team_name;
$seasonData['updated'] = $updated;
$seasonData['players'] = $players;

header("Access-Control-Allow-Origin: *");
echo json_encode($seasonData);

// Close connection
mysqli_close($link);
?>
