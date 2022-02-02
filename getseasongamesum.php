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

//echo $season_num;

header('Content-Type: application/json; charset=utf-8', true,200);

$SQLquery  = "SELECT g.game_id, g.game_datetime, g.location, g. opponent, g.result, g.run_scored, g.run_allowed, SUM(game) AS game, SUM(plateapp) AS plateapp, SUM(atbats) AS atbats, SUM(runs) AS runs, SUM(hits) AS hits, SUM(singles) AS singles, ";
 $SQLquery  .= "SUM(doubles) AS doubles, SUM(triples) AS triples, SUM(homeruns) AS homeruns, SUM(rbi) AS rbi, SUM(walks) AS walks,SUM(strikeout) AS strikeout, SUM(strikeoutswing) AS strikeoutswing, SUM(strikeoutlook) AS strikeoutlook, ";
 $SQLquery  .= "SUM(sacrifice) AS sacrifice, SUM(steal) AS steal, SUM(caughtstealing) AS caughtstealing, SUM(hitbypitch) AS hitbypitch, SUM(reachonerror) AS reachonerror ";
 $SQLquery  .= "FROM hitters h JOIN games g ON g.game_id=h.game_id WHERE g.season_id=$season_num ";
 $SQLquery  .= "GROUP BY g.game_id";

if ($result = mysqli_query($link, $SQLquery)) 
	{
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
        $games[] = $row;
        }
    }
	
$seasonData['games'] = $games;
$seasonData['season']= $season_sett;
	
header("Access-Control-Allow-Origin: *");
echo json_encode($seasonData);

// Close connection
mysqli_close($link);
?>
