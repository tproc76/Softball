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
$min_ab = "0";

if (isset($_GET["ab"]))
	{
	$min_ab = $_GET["ab"];
	}

$thegroup = null;
if (isset($_GET["group"]))
	{
	$thegroup = $_GET["group"];
	}
	
$findLeague  = "SELECT * FROM seasons WHERE league = '$league_name'";

if ($result = mysqli_query($link, $findLeague))
	{
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
		$seasons[] = $row;
		}
	}
else
	{
	echo json_encode("League Not Found - " . $league_name);
	exit();
	}

header('Content-Type: application/json; charset=utf-8', true,200);

$SQLquery  = "SELECT p.nickname, p.player_id, SUM(game) AS game, SUM(plateapp) AS plateapp, SUM(atbats) AS atbats, SUM(runs) AS runs, SUM(hits) AS hits, SUM(singles) AS singles, SUM(doubles) AS doubles, ";
 $SQLquery  .= "SUM(triples) AS triples, SUM(homeruns) AS homeruns, SUM(rbi) AS rbi, SUM(walks) AS walks,SUM(strikeout) AS strikeout, SUM(strikeoutswing) AS strikeoutswing, SUM(strikeoutlook) AS strikeoutlook, ";
 $SQLquery  .= "SUM(sacrifice) AS sacrifice, SUM(steal) AS steal, SUM(caughtstealing) AS caughtstealing, SUM(hitbypitch) AS hitbypitch, SUM(reachonerror) AS reachonerror ";
 $SQLquery  .= "FROM hitters h JOIN players p ON h.player_id=p.player_id JOIN games g ON g.game_id=h.game_id JOIN seasons ss ON g.season_id=ss.season_id WHERE ss.league = '$league_name' ";
if ($thegroup!=null) 
	{
    $SQLquery  .= "AND ss.grouping = '$thegroup' ";
		
	}
 $SQLquery  .= "GROUP BY h.player_id ";
 $SQLquery  .= "HAVING SUM(atbats)>$min_ab ";

if ($result = mysqli_query($link, $SQLquery)) 
	{
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
        $hitters[] = $row;
        }
    }
	
$seasonData['hitters'] = $hitters;
$seasonData['seasons'] = $seasons;

header("Access-Control-Allow-Origin: *");
echo json_encode($seasonData);

// Close connection
mysqli_close($link);
?>
