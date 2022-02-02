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

$SQLquery  = "SELECT p.nickname, p.player_id, SUM(game) AS game, SUM(plateapp) AS plateapp, SUM(atbats) AS atbats, SUM(runs) AS runs, SUM(hits) AS hits, SUM(singles) AS singles, SUM(doubles) AS doubles, ";
 $SQLquery  .= "SUM(triples) AS triples, SUM(homeruns) AS homeruns, SUM(rbi) AS rbi, SUM(walks) AS walks,SUM(strikeout) AS strikeout, SUM(strikeoutswing) AS strikeoutswing, SUM(strikeoutlook) AS strikeoutlook, ";
 $SQLquery  .= "SUM(sacrifice) AS sacrifice, SUM(steal) AS steal, SUM(caughtstealing) AS caughtstealing, SUM(hitbypitch) AS hitbypitch, SUM(reachonerror) AS reachonerror ";
 $SQLquery  .= "FROM hitters h JOIN players p ON h.player_id=p.player_id JOIN games g ON g.game_id=h.game_id WHERE g.season_id=$season_num ";
 $SQLquery  .= "GROUP BY h.player_id";

if ($result = mysqli_query($link, $SQLquery)) 
	{
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
        $hitters[] = $row;
        }
    }

$SQLquery  = "SELECT SUM(game) AS game, SUM(plateapp) AS plateapp, SUM(atbats) AS atbats, SUM(runs) AS runs, SUM(hits) AS hits, SUM(singles) AS singles, SUM(doubles) AS doubles, ";
 $SQLquery  .= "SUM(triples) AS triples, SUM(homeruns) AS homeruns, SUM(rbi) AS rbi, SUM(walks) AS walks,SUM(strikeout) AS strikeout, SUM(strikeoutswing) AS strikeoutswing, SUM(strikeoutlook) AS strikeoutlook, ";
 $SQLquery  .= "SUM(sacrifice) AS sacrifice, SUM(steal) AS steal, SUM(caughtstealing) AS caughtstealing, SUM(hitbypitch) AS hitbypitch, SUM(reachonerror) AS reachonerror ";
 $SQLquery  .= "FROM hitters h JOIN games g ON g.game_id=h.game_id WHERE g.season_id=$season_num ";

if ($result = mysqli_query($link, $SQLquery)) 
	{
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
        $hittertotals = $row;
        }
    }
	
$SQLquery  = "SELECT p.nickname, p.player_id, SUM(game) AS games, SUM(wins) as wins, SUM(losses) AS losses, SUM(innings) AS innings, SUM(saves) AS saves, sum(runsallowed) AS runsallowed, SUM(earnedruns) AS earnedruns, ";
 $SQLquery  .= "SUM(strikeout) AS strikeout, SUM(walks) AS walks, SUM(hitsallowed) AS hitsallowed, SUM(hitbatters) AS hitbatters, SUM(wildpitch) AS wildpitch, SUM(hold) AS hold, ";
 $SQLquery  .= "SUM(batface) AS batface, SUM(pitchcount) AS pitchcount FROM pitchers t JOIN players p ON t.player_id=p.player_id JOIN games g ON g.game_id=t.game_id WHERE g.season_id=$season_num "; 
 $SQLquery  .= "GROUP BY t.player_id";

$pitchers = array();
if ($result = mysqli_query($link, $SQLquery)) 
	{
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
        $pitchers[] = $row;
        }
    }
	
$SQLquery  = "SELECT SUM(game) AS games, SUM(wins) as wins, SUM(losses) AS losses, SUM(innings) AS innings, SUM(saves) AS saves, sum(runsallowed) AS runsallowed, SUM(earnedruns) AS earnedruns, ";
 $SQLquery  .= "SUM(strikeout) AS strikeout, SUM(walks) AS walks, SUM(hitsallowed) AS hitsallowed, SUM(hitbatters) AS hitbatters, SUM(wildpitch) AS wildpitch, SUM(hold) AS hold, ";
 $SQLquery  .= "SUM(batface) AS batface, SUM(pitchcount) AS pitchcount FROM pitchers t JOIN games g ON g.game_id=t.game_id WHERE g.season_id=$season_num "; 

if ($result = mysqli_query($link, $SQLquery)) 
	{
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
        $pitchertotals = $row;
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

$seasonData['hitters'] = $hitters;
$seasonData['pitchers'] = $pitchers;
$seasonData['games'] = $games;
$seasonData['season'] = $season_sett;
$seasonData['team'] = $team_name;
$seasonData['updated'] = $updated;
$seasonData['htotals'] = $hittertotals;
$seasonData['ptotals'] = $pitchertotals;
	
header("Access-Control-Allow-Origin: *");
echo json_encode($seasonData);

// Close connection
mysqli_close($link);
?>
