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

$findLeague  = "SELECT s.year, s.season_name, s.team_name, s.season_id, SUM(IF(g.result='W',1,0)) AS wins, SUM(IF(g.result='L',1,0)) AS losses, SUM(IF(g.result='T',1,0)) AS ties, SUM(g.run_scored) AS scored, SUM(g.run_allowed) AS allowed";
$findLeague  .= " FROM seasons s JOIN games g ON s.season_id=g.season_id WHERE s.league='$league_name'";
$findLeague  .= " GROUP BY s.season_id ORDER BY s.year";

header('Content-Type: application/json; charset=utf-8', true,200);

$result = mysqli_query($link, $findLeague);
if (mysqli_num_rows($result) == 0)
{
	echo "League Not Found - " . $findLeague;
	exit();
}

while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
	{
	$seasons[] = $row;
	}

$SQLquery  = "SELECT s.year, s.season_id, ";
$SQLquery  .= " SUM(h.atbats) AS atbats, SUM(h.hits) AS hits, SUM(h.doubles) AS doubles, SUM(h.triples) AS triples, SUM(h.homeruns) AS homeruns, SUM(h.walks) AS walks, SUM(h.sacrifice) AS sacrifice";
$SQLquery  .= " FROM seasons s JOIN games g ON s.season_id=g.season_id JOIN hitters h ON h.game_id=g.game_id WHERE s.league='$league_name'";
$SQLquery  .= " GROUP BY s.season_id ORDER BY s.year";

if ($result = mysqli_query($link, $SQLquery)) 
	{
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
        $hitters[] = $row;
        }
    }

$seasonData['season'] = $seasons;
$seasonData['hitting'] = $hitters;

header("Access-Control-Allow-Origin: *");
echo json_encode($seasonData);

// Close connection
mysqli_close($link);
?>
