<?php
session_start();

// do we keep these?  If so, I can't schedule the back up, so that seems bad, but if I do it by manual trigger, this is desired
if(isset($_SESSION['login_user']) == false)
	{
	// redirect to login if not logged in
	echo '<script type="text/javascript">window.location = "attend_login.html"</script>';
	exit();
	}
if ($_SESSION['login_user']!="proc@comcast.net")
	{
	// redirect to login if not logged in
	echo '<script type="text/javascript">window.location = "attend_members_main.php"</script>';
	exit();
	}

include 'db_setup.php';

function checkFieldNames($theRow,$expectNames)
	{
	$arrayDiff = array_diff_key($theRow,$expectNames);
	$unknwonFields = array_keys($arrayDiff);
				
	if (sizeof($unknwonFields) > 0)
		{
		echo "ERROR MISSING FIELDS!! - Fields not included in table: ";
		//var_dump(array_keys($arrayDiff));
		for ($inx = 0; $inx < sizeof($unknwonFields); $inx++)
			{
			if ($inx > 0)
				echo ", ";
			echo $unknwonFields[$inx];
			}
		echo "\n\r";
		}
	}
	
function createFile($link,$tableName,$fileName,$fieldList)
	{
	$SQLquery = "SELECT * FROM $tableName";
	$firstTimeFlag = true;
	$resultstatus = "Failed: no path - $tableName";

	// create/open file (overwrite) - https://www.w3schools.com/php/php_file_create.asp
	$backupFile = fopen("backup/$fileName", "w") or die("Unable to open file!");

	fwrite($backupFile,"<?php\n");
	fwrite($backupFile,"include '..\\db_setup.php';\n\n");

	fwrite($backupFile,"\$insertSql = \"\";\n");

	$rowcount = 0;
	if ($result = mysqli_query($link, $SQLquery))
		{
	   while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
			{
			$rowcount = $rowcount+1;
			if ($firstTimeFlag == true)
				{
				checkFieldNames($row,$fieldList);
				$firstTimeFlag = false;
				}

			// Write data to file
			fwrite($backupFile,"\$insertSql .= \"INSERT INTO $tableName VALUES(");
			$cntField = 0;
			foreach($fieldList as $key => $value)
				{
				if ($cntField > 0)
					fwrite($backupFile,", ");
				else
					$cntField = 1;
				
				if ($row[$key] == NULL)
					{
					fwrite($backupFile,"NULL");
					}
				else if ($value == true)
					{
					fwrite($backupFile,"'" . $row[$key] . "'");
					}
				else
					{
					fwrite($backupFile,$row[$key]);
					}
				}
			fwrite($backupFile,");\";\n");		// Yes, there really are 3 semicolons needed
			}
			
		fwrite($backupFile,"\nif(mysqli_multi_query(\$link, \$insertSql) == false){\n");
		fwrite($backupFile,"echo \"ERROR: Could not execute. \" . mysqli_error(\$link) . \"<br>\";\n}\n");
		fwrite($backupFile,"while (mysqli_next_result(\$link)) {;} // flush multi_queries\n\n");
		fwrite($backupFile,"echo \"Complete\";\n");

		fwrite($backupFile,"// Close connection\n");
		fwrite($backupFile,"mysqli_close(\$link);\n");
		fwrite($backupFile,"?");
		fwrite($backupFile,">\n");

		fclose($backupFile);

		$resultstatus = "Success - $tableName\n";
		}	
	else
		{
		echo "ERROR: " . mysqli_error($link) . "<BR>";								
		$resultstatus = "Failed: SQL Error";
		}
		
	echo $resultstatus;
	
	return $rowcount;
	}

// Real Code - calling functions above
header('Content-Type: application/json; charset=utf-8', true,200);

//$nameList = array('player_id' => false,'nickname' => true,'fullname' => true,'notes' => true);
$teamList = array('team_id' => false,'team_name' => true,'season' => true,'reminderdays' => false,'secondreminderdays' => false,'lockouthours' => false,
					'creator_id' => false,'stats_team_id' => false,'updated' => true);
$gameList = array('team_id' => false,'game_id' => false,'game_datetime' => true,'location' => true,'opponent' => true,'updated' => true);
$memberList = array('team_id' => false,'player_id' => false,'role' => true,'updated' => true);
$playerList = array('player_id' => false,'name' => true,'email' => true,'passhash' => true,'phone' => true,'updated' => true);
$attendanceList = array('game_id' => false, 'player_id' => false,'attending' => true,'notes' => true,'updated' => true);

//$playerRows = createFile($link,"players","stat_players2.php",$nameList);
$teamRows = createFile($link,"attend_team","attend_teams.php",$teamList);
$gameRows = createFile($link,"attend_games","attend_games.php",$gameList);
$memberRows = createFile($link,"attend_members","attend_members.php",$memberList);
$playerRows = createFile($link,"attend_players","attend_players.php",$playerList);
$attendanceRows = createFile($link,"attendance","attend_attendance.php",$attendanceList);
// Did not backup the forgot password table	

echo "Team Rows saved - $teamRows\n";
echo "Games Rows saved - $gameRows\n";
echo "Members Rows saved - $memberRows\n";
echo "Players saved = $playerRows\n";
echo "Attendance Rows saved = $attendanceRows\n";

// Close connection
mysqli_close($link);
?>
