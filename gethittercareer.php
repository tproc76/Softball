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
$league_name = $_GET["league"];
$player_num = $_GET["player"];

$findLeague  = "SELECT * FROM seasons WHERE league = '$league_name'";

$result = mysqli_query($link, $findLeague);
if (mysqli_num_rows($result) == 0)
{
	echo "League Not Found - " . $league_name;
	exit();
}

$prows = mysqli_fetch_array($result,MYSQLI_ASSOC);
$season_row = $prows;

header('Content-Type: application/json; charset=utf-8', true,200);

$SQLquery  = "SELECT p.nickname, p.player_id, SUM(game) AS game, SUM(plateapp) AS plateapp, SUM(atbats) AS atbats, SUM(runs) AS runs, SUM(hits) AS hits, SUM(singles) AS singles, SUM(doubles) AS doubles, ";
 $SQLquery  .= "SUM(triples) AS triples, SUM(homeruns) AS homeruns, SUM(rbi) AS rbi, SUM(walks) AS walks,SUM(strikeout) AS strikeout, SUM(strikeoutswing) AS strikeoutswing, SUM(strikeoutlook) AS strikeoutlook, ";
 $SQLquery  .= "SUM(sacrifice) AS sacrifice, SUM(steal) AS steal, SUM(caughtstealing) AS caughtstealing, SUM(hitbypitch) AS hitbypitch, SUM(reachonerror) AS reachonerror, season_name, ss.team_name AS team_name ";
 $SQLquery  .= "FROM hitters h JOIN players p ON h.player_id=p.player_id JOIN games g ON g.game_id=h.game_id JOIN seasons ss ON g.season_id=ss.season_id ";
 $SQLquery  .= "WHERE ss.league='$league_name' AND p.player_id=$player_num GROUP BY g.season_id";

if ($result = mysqli_query($link, $SQLquery)) 
	{
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
        $hitters[] = $row;
        }
    }
else
	{
	echo mysqli_error($link);
	}

$seasonData['hitters'] = $hitters;
	
header("Access-Control-Allow-Origin: *");
echo json_encode($seasonData);

// Close connection
mysqli_close($link);
?>
