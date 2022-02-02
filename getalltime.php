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
//$league_name = "mens";

$findLeague  = "SELECT * FROM seasons WHERE league = '$league_name'";

$result = mysqli_query($link, $findLeague);
if (mysqli_num_rows($result) == 0)
{
	echo json_encode("League Not Found - " . $league_name);
	exit();
}
$season_count = mysqli_num_rows($result);

header('Content-Type: application/json; charset=utf-8', true,200);

$SQLquery  = "SELECT p.nickname, p.player_id, SUM(game) AS game, SUM(plateapp) AS plateapp, SUM(atbats) AS atbats, SUM(runs) AS runs, SUM(hits) AS hits, SUM(singles) AS singles, SUM(doubles) AS doubles, ";
 $SQLquery  .= "SUM(triples) AS triples, SUM(homeruns) AS homeruns, SUM(rbi) AS rbi, SUM(walks) AS walks,SUM(strikeout) AS strikeout, SUM(strikeoutswing) AS strikeoutswing, SUM(strikeoutlook) AS strikeoutlook, ";
 $SQLquery  .= "SUM(sacrifice) AS sacrifice, SUM(steal) AS steal, SUM(caughtstealing) AS caughtstealing, SUM(hitbypitch) AS hitbypitch, SUM(reachonerror) AS reachonerror ";
 $SQLquery  .= "FROM hitters h JOIN players p ON h.player_id=p.player_id JOIN games g ON g.game_id=h.game_id JOIN seasons ss ON g.season_id=ss.season_id WHERE ss.league = '$league_name' ";
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
 $SQLquery  .= "FROM hitters h JOIN games g ON g.game_id=h.game_id JOIN seasons ss ON g.season_id=ss.season_id WHERE ss.league = '$league_name' ";

if ($result = mysqli_query($link, $SQLquery)) 
	{
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
        $hittertotals = $row;
        }
    }

	
$SQLquery  = "SELECT p.nickname, p.player_id, SUM(game) AS games, SUM(wins) as wins, SUM(losses) AS losses, SUM(innings) AS innings, SUM(t.saves) AS saves, sum(t.runsallowed) AS runsallowed, SUM(t.earnedruns) AS earnedruns, ";
 $SQLquery  .= "SUM(strikeout) AS strikeout, SUM(walks) AS walks, SUM(hitsallowed) AS hitsallowed, SUM(hitbatters) AS hitbatters, SUM(wildpitch) AS wildpitch, SUM(hold) AS hold ";
 $SQLquery  .= "FROM pitchers t JOIN players p ON t.player_id=p.player_id JOIN games g ON g.game_id=t.game_id JOIN seasons ss ON g.season_id=ss.season_id WHERE ss.league='$league_name' ";
 $SQLquery  .= "GROUP BY t.player_id";

if ($result = mysqli_query($link, $SQLquery)) 
	{
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
        $pitchers[] = $row;
        }
    }
	
$SQLquery  = "SELECT SUM(game) AS games, SUM(wins) as wins, SUM(losses) AS losses, SUM(innings) AS innings, SUM(t.saves) AS saves, sum(t.runsallowed) AS runsallowed, SUM(t.earnedruns) AS earnedruns, ";
 $SQLquery  .= "SUM(strikeout) AS strikeout, SUM(walks) AS walks, SUM(hitsallowed) AS hitsallowed, SUM(hitbatters) AS hitbatters, SUM(wildpitch) AS wildpitch, SUM(hold) AS hold ";
 $SQLquery  .= "FROM pitchers t JOIN games g ON g.game_id=t.game_id JOIN seasons ss ON g.season_id=ss.season_id WHERE ss.league='$league_name' ";

if ($result = mysqli_query($link, $SQLquery)) 
	{
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
        $pitchertotals = $row;
        }
    }
	
$findSeasons  = "SELECT SUM(IF(g.result='W',1,0)) AS wins, SUM(IF(g.result='L',1,0)) AS losses, SUM(IF(g.result='T',1,0)) AS ties, SUM(g.run_scored) AS scored, SUM(g.run_allowed) AS allowed ";
$findSeasons  .= "FROM games g JOIN seasons ss ON g.season_id=ss.season_id WHERE league = '$league_name' GROUP BY league";

if ($result = mysqli_query($link, $findSeasons))
	{
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
        $seasons = $row;
        }
	}	
	
$seasonData['hitters'] = $hitters;
$seasonData['pitchers'] = $pitchers;
$seasonData['seasoncnt'] = $season_count;
$seasonData['season'] = $seasons;
$seasonData['htotals'] = $hittertotals;
$seasonData['ptotals'] = $pitchertotals;

header("Access-Control-Allow-Origin: *");
echo json_encode($seasonData);

// Close connection
mysqli_close($link);
?>
